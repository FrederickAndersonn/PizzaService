<?php declare(strict_types=1);

require_once 'page.php';

class Kunde extends Page
{
    public function generateView(): void
    {
        $title = 'Kunde';
        $this->generatePageHeader($title);

        echo <<<HTML
        <section class="kunde" id="kunde">
            <h1>Kunde</h1>
            <form action="" method="post">
                <label for="ordering_id">Ordering ID:</label>
                <input type="text" id="ordering_id" name="ordering_id" required>
                <button type="submit" name="submit" value="submit">Submit</button>
            </form>
        HTML;

        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderingId = isset($_POST['ordering_id']) ? (int)$_POST['ordering_id'] : 0;
            $this->displayOrderArticles($orderingId);
        }

        echo '</section>';
        $this->generatePageFooter();
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
            echo '<div class="lieferstatus">';
            echo '<h3>Lieferstatus</h3>';

            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                echo '<p>' . htmlspecialchars($row['article_name']) . ': ' . $this->getStatusText((int)$row['status']) . '</p>';
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
