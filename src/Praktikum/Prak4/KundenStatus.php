<?php declare(strict_types=1);

require_once 'page.php';

class KundenStatus extends Page
{
    protected function processReceivedData(): void
    {
        // No form data to process
    }

    protected function getViewData(): array
    {
        session_start();

        // Retrieve the ordering ID from the session
        $orderingId = $_SESSION['last_order_id'] ?? 0;

        // Check if the ordering ID is valid
        if ($orderingId === 0) {
            // Handle the case where the session or pizza data is missing
            throw new Exception("Invalid ordering ID. Session or pizza data may be missing.");
        }

        // Fetch status data from the database based on the ordering ID
        $statusData = $this->getStatusData($orderingId);

        return $statusData;
    }

    protected function generateView(): void
    {
        try {
            // Set the content type header to JSON
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Expires: Sat, 01 Jul 2000 06:00:00 GMT");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header("Content-Type: application/json; charset=UTF-8");

            // Retrieve the view data
            $statusData = $this->getViewData();

            // Serialize the data to JSON
            $serializedData = json_encode($statusData);

            // Send the JSON response
            echo $serializedData;
        } catch (Exception $e) {
            // Handle exceptions
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }

    private function getStatusData(int $orderingId): array
    {
        // Fetch status data from the database based on the ordering ID
        $query = "SELECT oa.article_id, a.name AS article_name, oa.status
                  FROM ordered_article oa
                  JOIN article a ON oa.article_id = a.article_id
                  WHERE oa.ordering_id = $orderingId";

        $result = $this->_database->query($query);

        // Check if the query was successful
        if ($result) {
            $statusData = [];

            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                $statusData[] = [
                    'article_id' => (int)$row['article_id'],
                    'article_name' => htmlspecialchars($row['article_name']),
                    'status' => (int)$row['status']
                ];
            }

            // Free the result set
            $result->free();

            return $statusData;
        } else {
            // Log the detailed error information
            error_log("Database Error: " . $this->_database->error);

            // Provide a user-friendly message or redirect to an error page
            throw new Exception("Error retrieving status data. Please try again later.");
        }
    }

    public static function main(): void
    {
        try {
            $page = new KundenStatus();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            // Log the detailed error information
            error_log("Exception: " . $e->getMessage());

            // Provide a user-friendly message or redirect to an error page
            header("Content-type: text/html; charset=UTF-8");
            echo "An error occurred. Please try again later.";
        }
    }
}

KundenStatus::main();
