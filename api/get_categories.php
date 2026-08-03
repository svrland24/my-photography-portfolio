<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';

if (isset($db_error)) {
    echo json_encode(['error' => 'Database error']);
    exit;
}

try {
    $stmt = $pdo->query("SELECT c.id, c.name, c.slug, c.created_at, COUNT(p.id) AS photo_count 
                         FROM categories c 
                         LEFT JOIN photos p ON c.id = p.category_id 
                         GROUP BY c.id, c.name, c.slug, c.created_at 
                         ORDER BY c.name ASC");
    $categories = $stmt->fetchAll();

    echo json_encode([
        'status' => 'success',
        'categories' => $categories
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
