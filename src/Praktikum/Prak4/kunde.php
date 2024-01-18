<?php declare(strict_types=1);

require_once 'page.php';

class Kunde extends Page
{
    public function generateView(): void
    {
        // Start the session at the beginning of the script
        session_start();

        $title = 'Kunde';
        $this->generatePageHeader($title);

        echo <<<HTML
        <div id="status-container"></div>
        <script src="statusupdate.js"></script>
        <section class="kunde" id="kunde">
            <h1>Kunde</h1>
        HTML;

        // Fetch the last order ID from the session
        $lastOrderId = $_SESSION['last_order_id'] ?? 0;

        // Display ordered items from the last order
        $this->displayOrderArticles($lastOrderId);

             // Call JavaScript function to update status
             echo <<<HTML
             <script>
                 // Fetch and display the status using AJAX
                 window.onload = function() {
                     updateStatus($lastOrderId);
                     requestData();
                     startPolling();
                 };
             </script>
             HTML;

        echo '</section>';
        $this->generatePageFooter();

    }

    private function displayOrderArticles(int $orderId): void
    {
        // Fetch articles related to the order from the database
        $query = "SELECT oa.article_id, a.name AS article_name, oa.status
                  FROM ordered_article oa
                  JOIN article a ON oa.article_id = a.article_id
                  WHERE oa.ordering_id = $orderId";

        $result = $this->_database->query($query);

        // Check if the query was successful
        if ($result) {
            echo '<div class="lieferstatus">';
            echo '<h3>Lieferstatus</h3>';

            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                echo '<p class="status-item" data-article-id="' . $row['article_id'] . '">' . htmlspecialchars($row['article_name']) . ': ' . $this->getStatusText((int)$row['status']) . '</p>';
            }

            // Free the result set
            $result->free();

            echo '</div>';
        } else {
            // Handle query error
            echo '<p>Error retrieving data</p>';
        }
    }

    private function getStatusText(int $status): string
    {
        $statusOptions = [
            0 => 'Bestellt',
            1 => 'In Bearbeitung',
            2 => 'Fertig zur Lieferung',
            3 => 'Unterwegs',
            4 => 'Geliefert'
        ];

        return $statusOptions[$status] ?? 'Unknown';
    }

    public static function main(): void
    {
        try {
            $page = new Kunde();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

Kunde::main();
?>
