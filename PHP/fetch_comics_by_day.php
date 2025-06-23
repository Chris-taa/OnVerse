<?php
// php/fetch_comics_by_day.php
include 'db.php'; // Make sure this path is correct for your database connection file

header('Content-Type: application/json'); // Set header to indicate JSON response

$response = [
    'status' => 'error',
    'message' => 'Invalid request',
    'comics' => []
];

if (isset($_GET['day'])) {
    $dayParam = $_GET['day']; // e.g., "Sunday", "Monday"

    // Map the full day name to MySQL's DAYOFWEEK function
    // DAYOFWEEK returns 1 for Sunday, 2 for Monday, ..., 7 for Saturday
    $dayMap = [
        'sunday' => 1,
        'monday' => 2,
        'tuesday' => 3,
        'wednesday' => 4,
        'thursday' => 5,
        'friday' => 6,
        'saturday' => 7
    ];

    $dayOfWeek = $dayMap[strtolower($dayParam)] ?? null;

    if ($dayOfWeek === null) {
        $response['message'] = 'Invalid day parameter provided.';
        echo json_encode($response);
        exit();
    }

    try {
        // Query to fetch comics based on the day of the week they were created
        $stmt_daily = $pdo->prepare("
            SELECT w.work_id, w.title, w.description, w.thumbnail_url, w.author_id, u.username
            FROM works w
            JOIN users u ON w.author_id = u.id
            WHERE w.status = 'published' AND DAYOFWEEK(w.created_at) = ?
            ORDER BY w.created_at DESC
            LIMIT 8
        ");
        $stmt_daily->execute([$dayOfWeek]);
        $dailyComics = $stmt_daily->fetchAll(PDO::FETCH_ASSOC);

        // Process comics data (shorten description, ensure correct paths)
        foreach ($dailyComics as &$comic) {
            $comic['author_name'] = htmlspecialchars($comic['username']); // Use username directly from join
            $comic['thumbnail_url'] = htmlspecialchars($comic['thumbnail_url']);
            $comic['description'] = htmlspecialchars($comic['description']);
            if (strlen($comic['description']) > 100) {
                $comic['description'] = substr($comic['description'], 0, 97) . '...';
            }
            unset($comic['username']); // Remove raw username if not needed
            unset($comic['author_id']); // Remove author_id if not needed on frontend
        }
        unset($comic); // Break the reference

        $response['status'] = 'success';
        $response['message'] = 'Comics fetched successfully.';
        $response['comics'] = $dailyComics;

    } catch (PDOException $e) {
        error_log("Database error fetching comics by day: " . $e->getMessage());
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
}

echo json_encode($response);
?>