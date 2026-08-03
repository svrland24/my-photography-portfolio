<?php
require_once __DIR__ . '/admin_header.php';

$success = '';
$error = '';

// Fetch all categories for dropdown
$categories = [];
try {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $new_category_name = trim($_POST['new_category'] ?? '');

    $camera = trim($_POST['camera'] ?? 'Canon EOS R5');
    $lens = trim($_POST['lens'] ?? 'RF 24-70mm f/2.8L');
    $iso = trim($_POST['iso'] ?? '100');
    $shutter_speed = trim($_POST['shutter_speed'] ?? '1/250s');
    $aperture = trim($_POST['aperture'] ?? 'f/2.8');
    $location = trim($_POST['location'] ?? 'Sylhet, Bangladesh');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    $image_path = '';

    if (empty($title)) {
        $error = 'Photo title is required.';
    } else {
        // Handle On-the-Fly New Category Creation
        if (!empty($new_category_name)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $new_category_name), '-'));
            try {
                $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (:name, :slug)");
                $stmt->execute([':name' => $new_category_name, ':slug' => $slug]);
                $category_id = $pdo->lastInsertId();
            } catch (PDOException $e) {
                // If slug exists, fetch its ID
                $stmt = $pdo->prepare("SELECT id FROM categories WHERE slug = :slug");
                $stmt->execute([':slug' => $slug]);
                $cat = $stmt->fetch();
                if ($cat) $category_id = $cat['id'];
            }
        }

        if ($category_id <= 0) {
            $error = 'Please select or enter a category.';
        }

        // Handle File Upload or Image URL
        if (empty($error)) {
            if (isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['photo_file'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

                if (!in_array($ext, $allowed)) {
                    $error = 'Invalid image format. Allowed formats: JPG, PNG, WEBP, GIF.';
                } else {
                    if (!file_exists(UPLOAD_DIR)) {
                        mkdir(UPLOAD_DIR, 0777, true);
                    }
                    $filename = 'photo_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    $target_file = UPLOAD_DIR . $filename;

                    if (move_uploaded_file($file['tmp_name'], $target_file)) {
                        $image_path = UPLOAD_URL . $filename;
                    } else {
                        $error = 'Failed to move uploaded file. Check folder permissions.';
                    }
                }
            } else if (!empty($_POST['photo_url'])) {
                $image_path = trim($_POST['photo_url']);
            } else {
                $error = 'Please upload a photo file or provide an image URL.';
            }
        }

        // Insert into Database
        if (empty($error) && !empty($image_path)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO photos (title, description, category_id, image_path, camera, lens, iso, shutter_speed, aperture, location, is_featured) 
                                       VALUES (:title, :description, :category_id, :image_path, :camera, :lens, :iso, :shutter_speed, :aperture, :location, :is_featured)");
                $stmt->execute([
                    ':title' => $title,
                    ':description' => $description,
                    ':category_id' => $category_id,
                    ':image_path' => $image_path,
                    ':camera' => $camera,
                    ':lens' => $lens,
                    ':iso' => $iso,
                    ':shutter_speed' => $shutter_speed,
                    ':aperture' => $aperture,
                    ':location' => $location,
                    ':is_featured' => $is_featured
                ]);

                $success = 'Photograph uploaded successfully! It is now visible in the portfolio.';
            } catch (PDOException $e) {
                $error = 'Database insert failed: ' . $e->getMessage();
            }
        }
    }
}
?>

<div class="admin-header">
    <div>
        <h1 style="font-family: var(--font-heading); font-size: 2.2rem;">Upload New Photograph</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Add a new photograph, assign a category, and specify camera metadata.</p>
    </div>
    <div>
        <a href="photos.php" class="btn-admin" style="background: rgba(255,255,255,0.08); border: 1px solid var(--border-color); color: var(--text-main);"><i class="fa-solid fa-arrow-left"></i> Manage Photos</a>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div style="padding: 1rem 1.5rem; background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; border-radius: 10px; color: #34d399; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.8rem;">
        <i class="fa-solid fa-circle-check fa-1x"></i> <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div style="padding: 1rem 1.5rem; background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 10px; color: #f87171; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.8rem;">
        <i class="fa-solid fa-circle-exclamation fa-1x"></i> <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<form action="upload.php" method="POST" enctype="multipart/form-data" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 2rem; max-width: 900px;">
    
    <!-- Image Selection -->
    <div class="form-group">
        <label><i class="fa-solid fa-image"></i> Select Image File (Upload from PC)</label>
        <input type="file" name="photo_file" accept="image/*" class="form-control" style="padding: 0.6rem;">
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.4rem;">Supported extensions: .jpg, .jpeg, .png, .webp</p>
    </div>

    <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem; margin: 1rem 0;">--- OR ---</div>

    <div class="form-group">
        <label><i class="fa-solid fa-link"></i> Image URL (Optional Direct Link)</label>
        <input type="url" name="photo_url" class="form-control" placeholder="https://images.unsplash.com/photo-example...">
    </div>

    <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

    <!-- Photo Details -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="form-group">
            <label><i class="fa-solid fa-heading"></i> Photo Title *</label>
            <input type="text" name="title" required class="form-control" placeholder="e.g. Sunset Over Ratargul">
        </div>

        <div class="form-group">
            <label><i class="fa-solid fa-folder"></i> Category *</label>
            <select name="category_id" class="form-control">
                <option value="">-- Select Existing Category --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="new_category" class="form-control" style="margin-top: 0.6rem;" placeholder="Or type a new category name...">
        </div>
    </div>

    <div class="form-group">
        <label><i class="fa-solid fa-paragraph"></i> Description</label>
        <textarea name="description" rows="3" class="form-control" placeholder="Write a short backstory or description about this capture..."></textarea>
    </div>

    <!-- Camera EXIF Settings -->
    <h3 style="font-size: 1.1rem; font-weight: 700; margin: 2rem 0 1rem 0; color: var(--accent-glow);"><i class="fa-solid fa-sliders"></i> Camera EXIF Metadata</h3>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
        <div class="form-group">
            <label>Camera Model</label>
            <input type="text" name="camera" class="form-control" value="Sony A7 IV" placeholder="e.g. Canon EOS R5">
        </div>

        <div class="form-group">
            <label>Lens Used</label>
            <input type="text" name="lens" class="form-control" value="FE 24-70mm f/2.8 GM" placeholder="e.g. 50mm f/1.4">
        </div>

        <div class="form-group">
            <label>ISO Speed</label>
            <input type="text" name="iso" class="form-control" value="100" placeholder="e.g. 100, 400, 1600">
        </div>

        <div class="form-group">
            <label>Shutter Speed</label>
            <input type="text" name="shutter_speed" class="form-control" value="1/250s" placeholder="e.g. 1/1000s, 2s">
        </div>

        <div class="form-group">
            <label>Aperture</label>
            <input type="text" name="aperture" class="form-control" value="f/2.8" placeholder="e.g. f/1.8, f/8.0">
        </div>

        <div class="form-group">
            <label>Capture Location</label>
            <input type="text" name="location" class="form-control" value="Sylhet, Bangladesh" placeholder="e.g. Jaflong, Sylhet">
        </div>
    </div>

    <div class="form-group" style="margin-top: 1rem; display: flex; align-items: center; gap: 0.6rem;">
        <input type="checkbox" name="is_featured" id="is_featured" value="1" style="width: 18px; height: 18px; cursor: pointer;">
        <label for="is_featured" style="margin-bottom: 0; cursor: pointer;"><i class="fa-solid fa-star" style="color: #f59e0b;"></i> Mark as Featured Photograph (Showcase on Hero Banner)</label>
    </div>

    <button type="submit" class="btn-submit" style="margin-top: 1.5rem; width: 100%;">
        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Photograph Now
    </button>
</form>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
