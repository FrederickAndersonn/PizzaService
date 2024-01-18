<?php declare(strict_types=1);

require_once 'page.php';

class Bestellung extends Page
{
    public function getData(): array
    {
        $pizzas = array();
    
        // Query to select pizza data from the database
        $query = "SELECT name, price, picture FROM article";
        $result = $this->_database->query($query);
    
        // Check if the query was successful
        if ($result) {
            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                // Add the pizza data to the $pizzas array
                $pizzas[$row['name']] = array(
                    "price" => (int)$row['price'],
                    "image" => $row['picture']
                );
            }
    
            $result->free();
        } else {
            // Handle query error
            throw new Exception("Error executing query: " . $this->_database->error);
        }
    
        return $pizzas;
    }
    public function generateView(): void
    {
        $title = 'Bestellung';
        $this->generatePageHeader($title);

        echo <<<HTML
        <section class="bestellung" id="bestellung">
            <h1 class="bestellung_title">Bestellung</h1>
            <div class="bestellung_form container">
                <div class="bestellung_items">
                    <div class="pizza_infos">
                        <h2>Speisekarte</h2>
                        <div class="speisekarte">
HTML;
        $pizzas = $this->getData();

        foreach ($pizzas as $pizzaName => $pizzaDetails) {
            echo '<div class="pizza_container">';
            echo '<img src="' . $pizzaDetails["image"] . '" alt="' . $pizzaName . '" width="100">';
            echo '<p>' . $pizzaName . '-' .  $pizzaDetails["price"] . ' $ </p>';
            echo '</div>';
        }
        echo '<script>';
echo 'const pizzaDetails = ' . json_encode($pizzas) . ';';
echo '</script>';

        echo <<<HTML
                        </div>
                    </div>
                    <div class="warenkorb">
                        <h2>Warenkorb</h2>
                        <form action="Bestellung.php" class="form" method="post">
                        <input type="hidden" name="selected_pizzas" id="selected_pizzas_input">
                            <div class="warenkorb_items container">
                                <ul id="selected_items"></ul>
                            </div>
                            <h2>Total: $<span id="total">0</span></h2>
                            <button type="button" class="btn delete_btn" id="deleteBtn">Delete All</button>
                            <input required type="text" placeholder="Name" class="input" name="user_name">
                            <input required type="email" placeholder="Email" class="input" name="user_email">
                            <input required type="text" placeholder="Adresse Haus Nr." class="input" name="user_address">
                            <button class="btn" id="bestellenBtn">Bestellen</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script src="script.js"></script>
HTML;

        $this->generatePageFooter();
    }

    protected function processReceivedData(): void
    {
        parent::processReceivedData();
        session_start();
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['selected_pizzas'])) {
            $selectedPizzas = json_decode($_POST['selected_pizzas'], true);
        
            // Check if $selectedPizzas is an array and not empty
            if (is_array($selectedPizzas) && !empty($selectedPizzas)) {
                $address = $this->_database->real_escape_string($_POST['user_address']);
        
                // Insert into "ordering" table
                $insertOrderingSQL = "INSERT INTO ordering (address) VALUES (?)";
                $stmt = $this->_database->prepare($insertOrderingSQL);
                $stmt->bind_param("s", $address);
                $stmt->execute();
                $stmt->close();
        
                // Get the ordering_id of the inserted row
                $orderingId = $this->_database->insert_id;

                $_SESSION['last_order_id'] = $orderingId;
        
                $pizzaNameToArticleId = [
                    'Margherita' => 1,
                    'Pepperoni' => 2,
                    'Vegetarian' => 3,
                    'Hawaiian' => 4,
                ];
        
                // Insert into "ordered_article" table for each selected pizza
                foreach ($selectedPizzas as $selectedPizza) {
                    // You may want to add additional validation for security here
                    $pizzaName = $this->_database->real_escape_string($selectedPizza['name']);
        
                    // Determine article_id based on the pizza name
                    $articleId = isset($pizzaNameToArticleId[$pizzaName]) ? $pizzaNameToArticleId[$pizzaName] : 0;
        
                    // Insert into "ordered_article" table
                    $insertOrderedArticleSQL = "INSERT INTO ordered_article (ordering_id, article_id, status) VALUES (?, ?, 0)";
                    $stmt = $this->_database->prepare($insertOrderedArticleSQL);
                    $stmt->bind_param("ii", $orderingId, $articleId);
                    $stmt->execute();
                    $stmt->close();
                }
        
                // PRG PATTERN
                header('Location: kunde.php');
                exit();
            }
        }
        
    }

    public static function main(): void
    {
        try {
            $page = new Bestellung();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Bestellung::main();
