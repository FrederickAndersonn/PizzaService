<?php declare(strict_types=1);

require_once 'page.php';

class Bestellung extends Page
{
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

        foreach ($pizzas as $pizzaName => $pizzaDetails) {
            echo '<div class="pizza_container">';
            echo '<img src="' . $pizzaDetails["image"] . '" alt="' . $pizzaName . '" width="100px">';
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
                            <select required name="warenkorb[]" id="pizza_select_id" size="4" multiple>
                                <option value="1">Margarita</option>
                                <option value="2">Pepperoni</option>
                                <option value="3">Vegetarian</option>
                                <option value="4">Hawaiian</option>
                            </select>
                            <div class="warenkorb_items container">
                                <ul name="selected_items" id="selected_items"></ul>
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

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['warenkorb'])) {
            $address = $this->_database->real_escape_string($_POST['user_address']);

            // Insert into "ordering" table
            $insertOrderingSQL = "INSERT INTO ordering (address) VALUES ('$address')";
            $this->_database->query($insertOrderingSQL);

            // Get the ordering_id of the inserted row
            $orderingId = $this->_database->insert_id;

            // Insert into "ordered_article" table for each selected article in the warenkorb
            foreach ($_POST['warenkorb'] as $articleId) {
                // Insert into "ordered_article" table with auto-incremented ordering_id
                $insertOrderedArticleSQL = "INSERT INTO ordered_article (ordering_id, article_id, status) VALUES ('$orderingId', '$articleId', 0)";
                $this->_database->query($insertOrderedArticleSQL);
            }

            // PRG PATTERN
            header('Location: Bestellung.php');
            exit();
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
