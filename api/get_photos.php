<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';

if (isset($db_error)) {
    echo json_encode(['error' => 'Database connection failed. Please check Supabase credentials or XAMPP MySQL.']);
    exit;
}

$category_slug = $_GET['category'] ?? 'all';
$search = trim($_GET['search'] ?? '');

$sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug 
        FROM photos p 
        JOIN categories c ON p.category_id = c.id 
        WHERE 1=1";

$params = [];

if ($category_slug !== 'all' && !empty($category_slug)) {
    $sql .= " AND c.slug = :category_slug";
    $params[':category_slug'] = $category_slug;
}

if (!empty($search)) {
    $sql .= " AND (
        LOWER(p.title) LIKE LOWER(:search) OR 
        LOWER(p.description) LIKE LOWER(:search) OR 
        LOWER(p.location) LIKE LOWER(:search) OR 
        LOWER(p.camera) LIKE LOWER(:search) OR 
        LOWER(p.lens) LIKE LOWER(:search) OR 
        LOWER(c.name) LIKE LOWER(:search)
    )";
    $params[':search'] = '%' . $search . '%';
}

$sql .= " ORDER BY p.id DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $photos = $stmt->fetchAll();

    echo json_encode([
        'status' => 'success',
        'count' => count($photos),
        'photos' => $photos
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
