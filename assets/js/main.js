/* ========================================================
   Photography Portfolio - Main Gallery Logic
   Category Pills, Live Search, Lightbox EXIF & Theme Toggle
   ======================================================== */

document.addEventListener('DOMContentLoaded', () => {
    // Theme Toggle
    const themeBtn = document.getElementById('themeToggle');
    const themeIcon = themeBtn ? themeBtn.querySelector('i') : null;

    if (localStorage.getItem('theme') === 'light') {
        document.body.classList.add('light-mode');
        if (themeIcon) themeIcon.className = 'fa-solid fa-sun';
    }

    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            document.body.classList.toggle('light-mode');
            const isLight = document.body.classList.contains('light-mode');
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
            if (themeIcon) {
                themeIcon.className = isLight ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            }
        });
    }

    // State
    let currentCategory = 'all';
    let searchQuery = '';

    const categoryContainer = document.getElementById('categoryPills');
    const photoGrid = document.getElementById('photoGrid');
    const searchInput = document.getElementById('searchInput');

    // Render Categories
    async function loadCategories() {
        if (!categoryContainer) return;
        try {
            const categories = await db.getCategories();
            const totalCount = (await db.getPhotos('all')).length;

            let html = `
                <button class="pill-btn ${currentCategory === 'all' ? 'active' : ''}" data-category="all">
                    All Photos <span class="pill-count">${totalCount}</span>
                </button>
            `;

            categories.forEach(cat => {
                html += `
                    <button class="pill-btn ${currentCategory === cat.slug ? 'active' : ''}" data-category="${cat.slug}">
                        ${cat.name} <span class="pill-count">${cat.photo_count || 0}</span>
                    </button>
                `;
            });

            categoryContainer.innerHTML = html;

            // Category Click Handlers
            categoryContainer.querySelectorAll('.pill-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    categoryContainer.querySelectorAll('.pill-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    currentCategory = btn.dataset.category;
                    loadPhotos();
                });
            });
        } catch (err) {
            console.error('Error loading categories:', err);
        }
    }

    // Render Photos with Pulse Spinner & Shimmer Cards
    async function loadPhotos() {
        if (!photoGrid) return;

        // Render pulse camera spinner & skeleton shimmer cards
        let skeletonHtml = `
            <div class="spinner-container" style="grid-column: 1/-1;">
                <i class="fa-solid fa-camera-retro spin-camera"></i>
                <p style="font-size: 0.95rem; font-weight: 600; color: var(--text-main); margin-top: -0.5rem;">Loading Photographs...</p>
            </div>
        `;
        for (let i = 0; i < 6; i++) {
            skeletonHtml += `
                <div class="skeleton-card">
                    <div class="skeleton-img"></div>
                    <div class="skeleton-text"></div>
                    <div class="skeleton-text short"></div>
                </div>
            `;
        }
        photoGrid.innerHTML = skeletonHtml;

        // 300ms transition delay for smooth UX
        await new Promise(resolve => setTimeout(resolve, 300));

        try {
            const photos = await db.getPhotos(currentCategory, searchQuery);

            if (photos.length === 0) {
                photoGrid.innerHTML = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 5rem; background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color);" class="fade-in-up">
                        <i class="fa-solid fa-camera-retro fa-3x" style="color: var(--text-muted); opacity: 0.5;"></i>
                        <h3 style="margin-top: 1rem; font-size: 1.2rem;">No photographs found</h3>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.4rem;">Try searching for another keyword or upload new photos from Admin Panel.</p>
                    </div>
                `;
                return;
            }

            photoGrid.innerHTML = photos.map((photo, idx) => `
                <div class="photo-card fade-in-up" style="animation-delay: ${idx * 0.05}s;" data-photo='${JSON.stringify(photo).replace(/'/g, "&apos;")}'>
                    <div class="card-img-wrapper">
                        <img src="${photo.image_path}" alt="${escapeHtml(photo.title)}" loading="lazy">
                        <span class="category-tag">${photo.category_name || 'Gallery'}</span>
                        ${photo.is_featured ? '<span class="featured-badge" title="Featured Showcase"><i class="fa-solid fa-star"></i></span>' : ''}
                    </div>
                    <div class="card-info">
                        <h3 class="card-title">${escapeHtml(photo.title)}</h3>
                        <p class="card-desc">${photo.description ? escapeHtml(photo.description) : 'No description provided.'}</p>
                        <div class="card-footer">
                            <span class="location-tag"><i class="fa-solid fa-location-dot"></i> ${photo.location || 'Location Unspecified'}</span>
                            <span><i class="fa-solid fa-eye"></i> ${photo.views_count || 0}</span>
                        </div>
                    </div>
                </div>
            `).join('');

            // Attach Click Event for Lightbox Modal
            photoGrid.querySelectorAll('.photo-card').forEach(card => {
                card.addEventListener('click', () => {
                    const photoData = JSON.parse(card.dataset.photo);
                    openLightbox(photoData);
                });
            });
        } catch (err) {
            console.error('Error rendering photos:', err);
        }
    }

    // Debounced Search Input
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchQuery = e.target.value;
                loadPhotos();
            }, 300);
        });
    }

    // Lightbox Modal Logic
    const modal = document.getElementById('lightboxModal');
    const closeBtn = document.getElementById('closeModal');

    function openLightbox(photo) {
        if (!modal) return;
        document.getElementById('modalImage').src = photo.image_path;
        document.getElementById('modalTitle').textContent = photo.title;
        document.getElementById('modalCategory').textContent = photo.category_name || 'Category';
        document.getElementById('modalDesc').textContent = photo.description || 'No description provided.';
        document.getElementById('modalCamera').textContent = photo.camera || 'N/A';
        document.getElementById('modalLens').textContent = photo.lens || 'N/A';
        document.getElementById('modalIso').textContent = photo.iso || 'N/A';
        document.getElementById('modalShutter').textContent = photo.shutter_speed || 'N/A';
        document.getElementById('modalAperture').textContent = photo.aperture || 'N/A';
        document.getElementById('modalLocation').textContent = photo.location || 'N/A';
        document.getElementById('downloadBtn').href = photo.image_path;

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        if (!modal) return;
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeLightbox();
        });
    }

    // Helper: HTML Escaping
    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Initial Load
    window.loadPortfolio = function() {
        loadCategories();
        loadPhotos();
    };

    window.loadPortfolio();
});
