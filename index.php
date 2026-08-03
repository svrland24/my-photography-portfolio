<?php
require_once __DIR__ . '/includes/header.php';

// Fetch a featured photo for the Hero Section
$featured_photo = null;
if (!isset($db_error)) {
    try {
        $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM photos p JOIN categories c ON p.category_id = c.id WHERE p.is_featured = 1 ORDER BY p.id DESC LIMIT 1");
        $featured_photo = $stmt->fetch();
    } catch (PDOException $e) {
        // Fallback handled gracefully
    }
}
?>

<!-- Database Connection Notice (if XAMPP MySQL is off) -->
<?php if (isset($db_error)): ?>
<div style="max-width: 1300px; margin: 2rem auto; padding: 1.5rem 2rem; background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 12px; color: #f87171;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
        <div>
            <h3 style="margin-bottom: 0.2rem;">XAMPP MySQL Database Connection Warning</h3>
            <p style="font-size: 0.9rem;">Unable to connect to MySQL database. Please make sure <strong>MySQL</strong> is started in your XAMPP Control Panel, and import <code>sql/database.sql</code> into phpMyAdmin.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-banner">
        <img src="<?php echo $featured_photo ? $featured_photo['image_path'] : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200'; ?>" alt="Featured Photograph" class="hero-bg">
        <div class="hero-content">
            <span class="hero-badge"><i class="fa-solid fa-star"></i> Featured Photograph</span>
            <h1 class="hero-title"><?php echo $featured_photo ? htmlspecialchars($featured_photo['title']) : 'Capturing Moments In Perfection'; ?></h1>
            <p class="hero-desc"><?php echo $featured_photo ? htmlspecialchars($featured_photo['description']) : 'Welcome to my photography showcase. Explore visual stories captured through light, composition, and emotion.'; ?></p>
            
            <?php if ($featured_photo): ?>
            <div class="hero-meta">
                <span class="meta-item"><i class="fa-solid fa-camera"></i> <?php echo htmlspecialchars($featured_photo['camera']); ?></span>
                <span class="meta-item"><i class="fa-solid fa-circle-dot"></i> <?php echo htmlspecialchars($featured_photo['lens']); ?></span>
                <span class="meta-item"><i class="fa-solid fa-sliders"></i> ISO <?php echo htmlspecialchars($featured_photo['iso']); ?></span>
                <span class="meta-item"><i class="fa-solid fa-clock"></i> <?php echo htmlspecialchars($featured_photo['shutter_speed']); ?></span>
                <span class="meta-item"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($featured_photo['location']); ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Gallery Controls (Search & Category Pills) -->
<div class="gallery-controls" id="gallery">
    <div class="controls-row">
        <div class="category-pills" id="categoryPills">
            <!-- Rendered dynamically by JavaScript -->
        </div>
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchInput" placeholder="Search photos by title, gear, location...">
        </div>
    </div>
</div>

<!-- Photo Gallery Grid -->
<section class="gallery-section">
    <div class="photo-grid" id="photoGrid">
        <!-- Rendered dynamically by JavaScript -->
    </div>
</section>

<!-- Lightbox Modal -->
<div class="modal-overlay" id="lightboxModal">
    <div class="lightbox-content">
        <button class="close-btn" id="closeModal" title="Close"><i class="fa-solid fa-xmark"></i></button>
        
        <div class="lightbox-media">
            <img id="modalImage" src="" alt="Full view">
        </div>
        
        <div class="lightbox-details">
            <span class="category-tag" id="modalCategory" style="align-self: flex-start; margin-bottom: 0.8rem;">Category</span>
            <h2 id="modalTitle" style="font-family: var(--font-heading); font-size: 2rem; margin-bottom: 0.5rem;">Photo Title</h2>
            <p id="modalDesc" style="color: var(--text-muted); font-size: 0.95rem;">Description goes here...</p>

            <div class="exif-grid">
                <div class="exif-box">
                    <i class="fa-solid fa-camera"></i>
                    <div class="exif-info">
                        <label>Camera</label>
                        <span id="modalCamera">N/A</span>
                    </div>
                </div>
                <div class="exif-box">
                    <i class="fa-solid fa-circle-dot"></i>
                    <div class="exif-info">
                        <label>Lens</label>
                        <span id="modalLens">N/A</span>
                    </div>
                </div>
                <div class="exif-box">
                    <i class="fa-solid fa-sliders"></i>
                    <div class="exif-info">
                        <label>ISO</label>
                        <span id="modalIso">N/A</span>
                    </div>
                </div>
                <div class="exif-box">
                    <i class="fa-solid fa-stopwatch"></i>
                    <div class="exif-info">
                        <label>Shutter Speed</label>
                        <span id="modalShutter">N/A</span>
                    </div>
                </div>
                <div class="exif-box">
                    <i class="fa-solid fa-eye"></i>
                    <div class="exif-info">
                        <label>Aperture</label>
                        <span id="modalAperture">N/A</span>
                    </div>
                </div>
                <div class="exif-box">
                    <i class="fa-solid fa-location-dot"></i>
                    <div class="exif-info">
                        <label>Location</label>
                        <span id="modalLocation">N/A</span>
                    </div>
                </div>
            </div>

            <div style="margin-top: auto; padding-top: 1rem; display: flex; gap: 1rem;">
                <a id="downloadBtn" href="#" target="_blank" download class="btn-admin" style="justify-content: center; width: 100%;">
                    <i class="fa-solid fa-download"></i> Open / Download Full Resolution
                </a>
            </div>
        </div>
    </div>
</div>

<!-- About Section -->
<section class="about-section" id="about">
    <div class="about-card">
        <img src="https://images.unsplash.com/photo-1554048612-b6a482bc67e5?q=80&w=800" alt="Photographer at Work" class="about-img">
        <div class="about-text">
            <span class="hero-badge"><i class="fa-solid fa-user"></i> About The Photographer</span>
            <h2>Visual Storyteller & Lens Explorer</h2>
            <p style="color: var(--text-muted); margin-bottom: 1rem;">Passionately capturing raw emotions, majestic landscapes, and vibrant city streetscapes. Every photo in this collection tells a story carved by lighting, geometry, and moment.</p>
            <p style="color: var(--text-muted);">Equipped with professional mirrorless systems and prime lenses to craft high-resolution visual art for exhibitions, editorial features, and private collections.</p>
            
            <div class="stats-grid">
                <div>
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Clicks Captured</div>
                </div>
                <div>
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Exhibitions</div>
                </div>
                <div>
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Original Art</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="about-section" id="contact" style="margin-top: 0;">
    <div class="about-card" style="grid-template-columns: 1fr;">
        <div style="max-width: 700px; margin: 0 auto; text-align: center; width: 100%;">
            <span class="hero-badge"><i class="fa-solid fa-envelope"></i> Get In Touch</span>
            <h2 style="font-family: var(--font-heading); font-size: 2.2rem; margin-bottom: 0.8rem;">Have a Project or Inquiry?</h2>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Feel free to reach out for photo print inquiries, booking assignments, or creative collaborations.</p>

            <form action="api/send_message.php" method="POST" style="display: flex; flex-direction: column; gap: 1rem; text-align: left;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group" style="margin: 0;">
                        <label>Your Name</label>
                        <input type="text" name="name" required class="form-control" placeholder="John Doe">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label>Your Email</label>
                        <input type="email" name="email" required class="form-control" placeholder="john@example.com">
                    </div>
                </div>
                <div class="form-group" style="margin: 0;">
                    <label>Subject</label>
                    <input type="text" name="subject" class="form-control" placeholder="Photo Print / Assignment Booking">
                </div>
                <div class="form-group" style="margin: 0;">
                    <label>Message</label>
                    <textarea name="message" rows="4" required class="form-control" placeholder="Write your message..."></textarea>
                </div>
                <button type="submit" class="btn-submit" style="align-self: center; width: 220px; margin-top: 0.5rem;">Send Message</button>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
