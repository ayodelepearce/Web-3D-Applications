<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//Connect to SQLite 
$db_path = __DIR__ . '/db/models.db';

if (!file_exists($db_path)) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database not found. Please run database/setup_db.php first.'
    ]);
    exit;
}

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Query: single model by id, or all models 
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $stmt = $db->prepare("SELECT * FROM models WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => (int)$_GET['id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            http_response_code(404);
            echo json_encode(['error' => 'Model not found']);
            exit;
        }

        echo json_encode($result);

    } else {
        // Return all models (ordered by id to match index-based JS calls)
        $stmt = $db->query("SELECT * FROM models ORDER BY id ASC");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed: ' . $e->getMessage()]);
}
?>