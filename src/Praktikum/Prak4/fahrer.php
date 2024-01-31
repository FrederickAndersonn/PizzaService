<?php declare(strict_types=1);

require_once 'page.php';

class Fahrer extends Page
{
    public function generateView(): void
    {
        $title = 'Fahrer';
        $this->generatePageHeader($title);

        echo '<section class="fahrer" id="fahrer">';
        echo '<h1>Fahrer</h1>';
        echo '<form action="" method="post" id ="formid">'; 
    
        $this->displayCustomerOrders();

        echo '</form>';
        echo '</section>';
        echo '<script>';
        echo 'setTimeout(function() {';
        echo '    window.location.href = "backer.php";';
        echo '}, 10000);'; 
        echo '</script>';
        
        $this->generatePageFooter();
    }

    protected function processReceivedData(): void
    {
        parent::processReceivedData();

        // Process form submission if needed
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleFormSubmission();
        }
    }

    private function displayCustomerOrders(): void
    {
        // Fetch delivery information and total price from the ordering database
        $query = "SELECT o.ordering_id, o.address, SUM(a.price) AS total_price
                  FROM ordering o
                  JOIN ordered_article oa ON o.ordering_id = oa.ordering_id
                  JOIN article a ON oa.article_id = a.article_id
                  GROUP BY o.ordering_id";
    
        $result = $this->_database->query($query);
        // Check if the query was successful
        if ($result) {
            echo '<ul class="customer-list">';
            $hasArticles = false;
    
            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                $orderingId = $row['ordering_id'];
    
                // Check if there are articles with status between 2 and 5
                if ($this->hasArticlesWithStatus($orderingId)) {
                    $hasArticles = true;
                    echo '<li class="customer-item">';
                    echo '<div class="order-status" style="border: 1px solid #000; padding: 10px; width: 720px;">';
                    echo '<br>';
                    echo '<span class="delivery_info">Address: ' . htmlspecialchars($row['address']) . '</span>';
                    echo '<br>';
                    echo '<span class="total_price">Total Price: ' . number_format((float)$row['total_price'], 2) . ' $</span>';
                    echo '<br>';
    
                    // Output radio buttons for each status for pizzas with status >= 4
                    $this->generateRadioButtonsForOrdering($orderingId);
                    echo '</div>';
                    echo '</li>';
                }

            }
            echo '</ul>';
            if (!$hasArticles) {
                echo '<p>Nothing to send.</p>';
            }
            // Free the result set
            $result->free();
        } else {
            // Handle query error
            throw new Exception("Error executing query: " . $this->_database->error);
        }
    }
    
    private function hasArticlesWithStatus(string $orderingId): bool
    {
        // Check if there are articles with status between 2 and 5 for the specific ordering
        $query = "SELECT COUNT(*) AS num_articles
                  FROM ordered_article
                  WHERE ordering_id = '$orderingId' AND status >= 2 AND status < 5";
    
        $result = $this->_database->query($query);
    
        if ($result) {
            $row = $result->fetch_assoc();
            $numArticles = (int)$row['num_articles'];
            return $numArticles > 0;
        } else {
            // Handle query error
            throw new Exception("Error executing query: " . $this->_database->error);
        }
    }
    
    

private function generateRadioButtonsForOrdering(string $orderingId): void
{
    // Fetch ordered articles for the specific ordering with status >= 3
    $query = "SELECT oa.article_id, oa.status, a.name AS article_name
              FROM ordered_article oa
              JOIN article a ON oa.article_id = a.article_id
              WHERE oa.ordering_id = '$orderingId' AND oa.status >=2 AND oa.status <5";

    $result = $this->_database->query($query);

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $articleId = $row['article_id'];
                $status = (int)$row['status'];
                $articleName = htmlspecialchars($row['article_name']);

                echo '<span class="article-info">' . $articleName . '</span>';
                $this->generateRadioButtons($status, $orderingId, $articleId);
            }
        } else {
            // Display a message when there are no ordered articles
            echo '<p>Nothing to deliver.</p>';
        }

        // Free the result set
        $result->free();
    }  else {
        // Handle query error
        throw new Exception("Error executing query: " . $this->_database->error);
    }
}


    private function generateRadioButtons(int $status, string $orderingId, string $articleId): void
    {
        $statusOptions = [
            3 => 'Ready for Delivery',
            4 => 'On Delivery',
            5 => 'Delivered'
        ];

        foreach ($statusOptions as $value => $label) {
            echo '<input type="radio" name="fahrer_status[' . $orderingId . '][' . $articleId . ']" value="' . $value . '" ';
            echo 'id="fahrer_status-' . $orderingId . '-' . $articleId . '-' . $value . '" ';
            echo 'onclick="document.forms[\'formid\'].submit();" ';  // Add onclick event
            echo ($status === $value) ? 'checked' : '';
            echo '>';
            echo '<label for="fahrer_status-' . $orderingId . '-' . $articleId . '-' . $value . '" class="order-status-label">' . $label . '</label>';
        }

        echo '<br>';
    }

    private function handleFormSubmission(): void
    {
        if (isset($_POST['fahrer_status']) && is_array($_POST['fahrer_status'])) {
            $statuses = $_POST['fahrer_status'];
    
            foreach ($statuses as $orderingId => $articleStatus) {
                foreach ($articleStatus as $articleId => $status) {
                    // Sanitize and validate input
                    $orderingId = (int)$orderingId;
                    $articleId = (int)$articleId;
                    $status = (int)$status;
    
                    // Update the ordered_article table with the selected status
                    $query = "UPDATE ordered_article SET status = ? WHERE ordering_id = ? AND article_id = ?";
                    $stmt = $this->_database->prepare($query);
    
                    if (!$stmt) {
                        throw new Exception("Error preparing statement: " . $this->_database->error);
                    }
    
                    // Bind parameters
                    $stmt->bind_param("iii", $status, $orderingId, $articleId);
    
                    // Execute the statement
                    if (!$stmt->execute()) {
                        throw new Exception("Error executing statement: " . $stmt->error);
                    }
    
                    // Close the statement
                    $stmt->close();
                }
            }
    
            // Redirect to the same page to avoid form resubmission
            header("Location: {$_SERVER['PHP_SELF']}");
            die();
        }
    }
    

    public static function main(): void
    {
        try {
            $page = new Fahrer();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

Fahrer::main();
