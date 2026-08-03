<?php
require_once __DIR__ . '/admin_header.php';

$success = '';
$error = '';
$photo_id = intval($_GET['id'] ?? 0);

if ($photo_id <= 0) {
    header('Location: photos.php');
    exit;
}

// Fetch categories for select
$categories = [];
try {
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
} catch (PDOException $e) {}

// Handle Form Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $camera = trim($_POST['camera'] ?? '');
    $lens = trim($_POST['lens'] ?? '');
    $iso = trim($_POST['iso'] ?? '');
    $shutter_speed = trim($_POST['shutter_speed'] ?? '');
    $aperture = trim($_POST['aperture'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    if (!empty($title) && $category_id > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE photos SET title = :title, description = :description, category_id = :category_id, 
                                   camera = :camera, lens = :lens, iso = :iso, shutter_speed = :shutter_speed, 
                                   aperture = :aperture, location = :location, is_featured = :is_featured WHERE id = :id");
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':category_id' => $category_id,
                ':camera' => $camera,
                ':lens' => $lens,
                ':iso' => $iso,
                ':shutter_speed' => $shutter_speed,
                ':aperture' => $aperture,
                ':location' => $location,
                ':is_featured' => $is_featured,
                ':id' => $photo_id
            ]);
            $success = "Photograph details updated successfully.";
        } catch (PDOException $e) {
            $error = "Update failed: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in title and select a category.";
    }
}

// Fetch Photo Data
$photo = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM photos WHERE id = :id");
    $stmt->execute([':id' => $photo_id]);
    $photo = $stmt->fetch();
} catch (PDOException $e) {
    $error = $e->getMessage();
}

if (!$photo) {
    echo "<p>Photograph not found.</p>";
    require_once __DIR__ . '/admin_footer.php';
    exit;
}
?>

<div class="admin-header">
    <div>
        <h1 style="font-family: var(--font-heading); font-size: 2.2rem;">Edit Photograph Details</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Update title, category, or camera metadata for this photograph.</p>
    </div>
    <div>
        <a href="photos.php" class="btn-admin" style="background: rgba(255,255,255,0.08); border: 1px solid var(--border-color); color: var(--text-main);"><i class="fa-solid fa-arrow-left"></i> Back to Photos</a>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div style="padding: 1rem 1.5rem; background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; border-radius: 10px; color: #34d399; margin-bottom: 2rem;">
        <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div style="padding: 1rem 1.5rem; background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 10px; color: #f87171; margin-bottom: 2rem;">
        <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem; align-items: start;">
    
    <!-- Image Preview -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1rem; text-align: center;">
        <img src="../<?php echo htmlspecialchars($photo['image_path']); ?>" style="width: 100%; height: 260px; object-fit: cover; border-radius: 8px;" alt="">
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.8rem;">Path: <code><?php echo htmlspecialchars($photo['image_path']); ?></code></p>
    </div>

    <!-- Edit Form -->
    <form action="edit_photo.php?id=<?php echo $photo_id; ?>" method="POST" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 2rem;">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label>Photo Title *</label>
                <input type="text" name="title" required class="form-control" value="<?php echo htmlspecialchars($photo['title']); ?>">
            </div>

            <div class="form-group">
                <label>Category *</label>
                <select name="category_id" required class="form-control">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $photo['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3" class="form-control"><?php echo htmlspecialchars($photo['description']); ?></textarea>
        </div>

        <h3 style="font-size: 1.1rem; font-weight: 700; margin: 1.5rem 0 1rem 0; color: var(--accent-glow);"><i class="fa-solid fa-sliders"></i> Camera EXIF Metadata</h3>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            <div class="form-group">
                <label>Camera Model</label>
                <input type="text" name="camera" class="form-control" value="<?php echo htmlspecialchars($photo['camera']); ?>">
            </div>

            <div class="form-group">
                <label>Lens Used</label>
                <input type="text" name="lens" class="form-control" value="<?php echo htmlspecialchars($photo['lens']); ?>">
            </div>

            <div class="form-group">
                <label>ISO</label>
                <input type="text" name="iso" class="form-control" value="<?php echo htmlspecialchars($photo['iso']); ?>">
            </div>

            <div class="form-group">
                <label>Shutter Speed</label>
                <input type="text" name="shutter_speed" class="form-control" value="<?php echo htmlspecialchars($photo['shutter_speed']); ?>">
            </div>

            <div class="form-group">
                <label>Aperture</label>
                <input type="text" name="aperture" class="form-control" value="<?php echo htmlspecialchars($photo['aperture']); ?>">
            </div>

            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($photo['location']); ?>">
            </div>
        </div>

        <div class="form-group" style="margin-top: 1rem; display: flex; align-items: center; gap: 0.6rem;">
            <input type="checkbox" name="is_featured" id="is_featured" value="1" <?php echo $photo['is_featured'] ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
            <label for="is_featured" style="margin-bottom: 0; cursor: pointer;"><i class="fa-solid fa-star" style="color: #f59e0b;"></i> Mark as Featured Photograph</label>
        </div>

        <button type="submit" class="btn-submit" style="margin-top: 1.5rem; width: 100%;">
            <i class="fa-solid fa-floppy-disk"></i> Save Changes
        </button>
    </form>

</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
