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
            <h2>Lieferstatus<h2>
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
    
        if ($result) {
            // Echo a container div for the lieferstatus
            echo '<div class="lieferstatus-container">';
    
            // Iterate through each row in the result set
            while ($row = $result->fetch_assoc()) {
                $statusInfo = $this->getStatus((int)$row['status']);
                echo '<div class="lieferstatus-item" data-article-id="' . $row['article_id'] . '">';
                echo '<p class="status-item">' . htmlspecialchars($row['article_name']) . ': ' . $statusInfo['label'] . '</p>';
                echo '<img src="' . $statusInfo['image'] . '" alt="' . $statusInfo['label'] . '" class="status-image"  width="150" height="150">';
                echo '</div>';
            }
    
            // Close the lieferstatus-container div
            echo '</div>';
    
            // Free the result set
            $result->free();
        } else {
            // Handle query error
            echo '<p>Error retrieving data</p>';
        }
    }
    
    
    private function getStatus(int $status): array
    {
        $statusOptions = [
            0 => ['label' => 'Ordered', 'image' => 'assets/ewa.gif'],
            1 => ['label' => 'In the Oven', 'image' => 'assets/giphy.gif'],
            2 => ['label' => 'Ready', 'image' => 'assets/ready.gif'],
            3 => ['label' => 'Ready for Delivery', 'image' => 'assets/readytogo.gif'],
            4 => ['label' => 'In Delivery', 'image' => 'assets/ondelivery.gif'],
            5 => ['label' => 'Delivered', 'image' => 'assets/delivered.gif'],
        ];
    
        return $statusOptions[$status] ?? ['label' => 'Unknown', 'image' => 'unknown_image_path'];
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
