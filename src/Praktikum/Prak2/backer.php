<?php declare(strict_types=1);

require_once 'page.php';

class Baecker extends Page
{
    public function generateView(): void
    {
        $title = 'Bäcker';
        $this->generatePageHeader($title);

        echo <<<HTML
        <section class="backer" id="backer">
            <h1>Baecker</h1>
            <form action="" method="post">
            <meta http-equiv="Refresh" content="10; URL=backer.php">
                <ul class="customer-list">
        HTML;

        // Fetch and display orders from the database
        $this->displayOrders();

        echo <<<HTML
                </ul>
                <button class="btn submit_status_backer" name="submit" value="submit">Submit</button>
            </form>
        </section>
        HTML;

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

    private function displayOrders(): void
    {
        // Fetch order information from the database
        $query = "SELECT ordering_id FROM ordering";

        $result = $this->_database->query($query);

        // Check if the query was successful
        if ($result) {
            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                echo '<li class="customer-item">';
                echo '<div class="order-status">';
                echo '<span class="customer-name">Order ID: ' . $row['ordering_id'] . '</span>';
                echo '<br>';
                // Output articles and radio buttons for each order
                $this->displayOrderArticles((int)$row['ordering_id']);

                echo '</div>';
                echo '</li>';
            }

            // Free the result set
            $result->free();
        } else {
            // Handle query error
            throw new Exception("Error executing query: " . $this->_database->error);
        }
    }

    private function displayOrderArticles(int $orderingId): void
    {
        // Fetch articles related to the order from the database
        $query = "SELECT oa.article_id, a.name AS article_name, oa.status
                  FROM ordered_article oa
                  JOIN article a ON oa.article_id = a.article_id
                  WHERE oa.ordering_id = $orderingId";

        $result = $this->_database->query($query);

        // Check if the query was successful
        if ($result) {
            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                echo '<span class="article-info">' . htmlspecialchars($row['article_name']) . '</span>';
                echo '<br>';
                $this->generateRadioButtons((int)$row['status'], $orderingId, (int)$row['article_id']);
            }

            // Free the result set
            $result->free();
        } else {
            // Handle query error
            throw new Exception("Error executing query: " . $this->_database->error);
        }
    }

    private function generateRadioButtons(int $status, int $orderingId, int $articleId): void
    {
        $statusOptions = [
            0 => 'Ordered',
            1 => 'In the Oven',
            2 => 'Ready'
        ];
    
        foreach ($statusOptions as $value => $label) {
            echo '<input type="radio" name="baecker_status[' . $orderingId . '][' . $articleId . ']" value="' . $value . '" ';
            echo 'id="baecker_status-' . $orderingId . '-' . $articleId . '-' . $value . '" ';
    
            // Add the checked attribute if the status matches the current radio button value
            echo ($status === $value) ? 'checked' : '';
    
            echo '>';
            echo '<label for="baecker_status-' . $orderingId . '-' . $articleId . '-' . $value . '" class="order-status-label">' . $label . '</label>';
        }
    
        echo '<br>';
    }
    

    private function handleFormSubmission(): void
    {
        if (isset($_POST['submit'], $_POST['baecker_status']) && is_array($_POST['baecker_status'])) {
            $statuses = $_POST['baecker_status'];

            foreach ($statuses as $orderingId => $articles) {
                foreach ($articles as $articleId => $status) {
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
            $page = new Baecker();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

Baecker::main();
