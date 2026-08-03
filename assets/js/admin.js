/* ========================================================
   Photography Portfolio - Admin Panel JavaScript
   Passcode Security, Photo Uploader & Category Manager
   ======================================================== */

document.addEventListener('DOMContentLoaded', () => {
    const adminModal = document.getElementById('adminModal');
    const openAdminBtn = document.getElementById('openAdminBtn');
    const closeAdminBtn = document.getElementById('closeAdminBtn');
    const adminPinInput = document.getElementById('adminPinInput');
    const adminLoginBtn = document.getElementById('adminLoginBtn');
    const adminLoginScreen = document.getElementById('adminLoginScreen');
    const adminDashboardScreen = document.getElementById('adminDashboardScreen');
    const adminTabBtns = document.querySelectorAll('.admin-tab-btn');
    const adminTabContents = document.querySelectorAll('.admin-tab-content');

    let isAdminLoggedIn = false;

    // Open Admin Modal
    if (openAdminBtn) {
        openAdminBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (!adminModal) return;
            adminModal.classList.add('active');
            document.body.style.overflow = 'hidden';

            if (isAdminLoggedIn) {
                showDashboard();
            } else {
                showLogin();
            }
        });
    }

    // Close Admin Modal
    if (closeAdminBtn) {
        closeAdminBtn.addEventListener('click', () => {
            if (!adminModal) return;
            adminModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        });
    }

    // Login Handler
    if (adminLoginBtn) {
        adminLoginBtn.addEventListener('click', handleAdminLogin);
    }
    if (adminPinInput) {
        adminPinInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') handleAdminLogin();
        });
    }

    function handleAdminLogin() {
        const pin = adminPinInput.value.trim();
        const loginError = document.getElementById('loginError');

        if (pin === CONFIG.ADMIN_PIN) {
            isAdminLoggedIn = true;
            if (loginError) loginError.style.display = 'none';
            adminPinInput.value = '';
            showDashboard();
        } else {
            if (loginError) {
                loginError.textContent = 'Invalid Passcode. Default PIN is admin123';
                loginError.style.display = 'block';
            }
        }
    }

    function showLogin() {
        if (adminLoginScreen) adminLoginScreen.style.display = 'block';
        if (adminDashboardScreen) adminDashboardScreen.style.display = 'none';
    }

    function showDashboard() {
        if (adminLoginScreen) adminLoginScreen.style.display = 'none';
        if (adminDashboardScreen) adminDashboardScreen.style.display = 'block';
        renderAdminStats();
        populateCategorySelect();
        renderAdminPhotosTable();
        renderCategoryManagerList();
    }

    // Tab Navigation inside Admin Panel
    adminTabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            adminTabBtns.forEach(b => b.classList.remove('active'));
            adminTabContents.forEach(c => c.style.display = 'none');
            
            btn.classList.add('active');
            const target = document.getElementById(btn.dataset.tab);
            if (target) target.style.display = 'block';
        });
    });

    // Populate Category Dropdown in Upload Form
    async function populateCategorySelect() {
        const select = document.getElementById('uploadCategorySelect');
        if (!select) return;

        const categories = await db.getCategories();
        select.innerHTML = '<option value="">-- Select Category --</option>' + 
            categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
    }

    // Render Admin Stats
    async function renderAdminStats() {
        const photos = await db.getPhotos('all');
        const categories = await db.getCategories();
        const totalViews = photos.reduce((acc, p) => acc + (p.views_count || 0), 0);

        const photoStat = document.getElementById('statTotalPhotos');
        const catStat = document.getElementById('statTotalCategories');
        const viewsStat = document.getElementById('statTotalViews');

        if (photoStat) photoStat.textContent = photos.length;
        if (catStat) catStat.textContent = categories.length;
        if (viewsStat) viewsStat.textContent = totalViews.toLocaleString();
    }

    // Photo Upload Form Handler (Convert image to DataURL or Use URL)
    const photoUploadForm = document.getElementById('photoUploadForm');
    const photoFileInput = document.getElementById('photoFileInput');

    if (photoUploadForm) {
        photoUploadForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const title = document.getElementById('uploadTitle').value.trim();
            const categoryId = document.getElementById('uploadCategorySelect').value;
            const newCategoryName = document.getElementById('uploadNewCategoryInput').value.trim();
            const description = document.getElementById('uploadDescription').value.trim();
            const photoUrl = document.getElementById('uploadUrlInput').value.trim();
            const camera = document.getElementById('uploadCamera').value.trim();
            const lens = document.getElementById('uploadLens').value.trim();
            const iso = document.getElementById('uploadIso').value.trim();
            const shutter = document.getElementById('uploadShutter').value.trim();
            const aperture = document.getElementById('uploadAperture').value.trim();
            const location = document.getElementById('uploadLocation').value.trim();
            const isFeatured = document.getElementById('uploadFeaturedCheck').checked;
            const alertMsg = document.getElementById('uploadAlert');

            let imagePath = photoUrl;

            // Handle File Picker Conversion to Base64/DataURL if selected
            if (photoFileInput && photoFileInput.files.length > 0) {
                const file = photoFileInput.files[0];
                imagePath = await new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onload = (ev) => resolve(ev.target.result);
                    reader.readAsDataURL(file);
                });
            }

            if (!title) {
                alert('Please enter a photo title.');
                return;
            }

            let finalCategoryId = categoryId;

            // On-the-fly new category creation
            if (newCategoryName !== '') {
                const newCat = await db.addCategory(newCategoryName);
                finalCategoryId = newCat.id;
            }

            if (!finalCategoryId) {
                alert('Please select or type a category name.');
                return;
            }

            if (!imagePath) {
                alert('Please upload an image file or enter an image URL.');
                return;
            }

            // Save Photo via Supabase Wrapper Service
            await db.addPhoto({
                title: title,
                description: description,
                category_id: finalCategoryId,
                image_path: imagePath,
                camera: camera,
                lens: lens,
                iso: iso,
                shutter_speed: shutter,
                aperture: aperture,
                location: location,
                is_featured: isFeatured
            });

            if (alertMsg) {
                alertMsg.style.display = 'block';
                setTimeout(() => alertMsg.style.display = 'none', 3000);
            }

            photoUploadForm.reset();
            populateCategorySelect();
            renderAdminPhotosTable();
            renderAdminStats();
            if (window.loadPortfolio) window.loadPortfolio();
        });
    }

    // Render Admin Photos Table
    async function renderAdminPhotosTable() {
        const tableBody = document.getElementById('adminPhotosTableBody');
        if (!tableBody) return;

        const photos = await db.getPhotos('all');

        if (photos.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">No photos uploaded yet.</td></tr>`;
            return;
        }

        tableBody.innerHTML = photos.map(p => `
            <tr style="border-bottom: 1px solid var(--border-color);">
                <td style="padding: 0.8rem 1rem;">
                    <img src="${p.image_path}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;" alt="">
                </td>
                <td style="padding: 0.8rem 1rem; font-weight: 600;">${escapeHtml(p.title)}</td>
                <td style="padding: 0.8rem 1rem;"><span class="category-tag" style="position: static; font-size: 0.7rem;">${p.category_name || 'Gallery'}</span></td>
                <td style="padding: 0.8rem 1rem; color: var(--text-muted); font-size: 0.85rem;">${p.camera || 'N/A'}</td>
                <td style="padding: 0.8rem 1rem;">
                    <button class="btn-icon toggle-featured-btn" data-id="${p.id}" style="width: 32px; height: 32px; color: ${p.is_featured ? '#f59e0b' : 'var(--text-muted)'};" title="Toggle Featured">
                        <i class="fa-${p.is_featured ? 'solid' : 'regular'} fa-star"></i>
                    </button>
                </td>
                <td style="padding: 0.8rem 1rem;">
                    <button class="btn-icon delete-photo-btn" data-id="${p.id}" style="width: 32px; height: 32px; color: #ef4444;" title="Delete Photo">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');

        // Action Handlers
        tableBody.querySelectorAll('.toggle-featured-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                await db.toggleFeatured(btn.dataset.id);
                renderAdminPhotosTable();
                if (window.loadPortfolio) window.loadPortfolio();
            });
        });

        tableBody.querySelectorAll('.delete-photo-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                if (confirm('Are you sure you want to delete this photograph?')) {
                    await db.deletePhoto(btn.dataset.id);
                    renderAdminPhotosTable();
                    renderAdminStats();
                    if (window.loadPortfolio) window.loadPortfolio();
                }
            });
        });
    }

    // Category Manager List
    async function renderCategoryManagerList() {
        const catContainer = document.getElementById('categoryManagerList');
        if (!catContainer) return;

        const categories = await db.getCategories();
        catContainer.innerHTML = categories.map(c => `
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border-color); border-radius: var(--radius-sm); margin-bottom: 0.6rem;">
                <div>
                    <strong>${escapeHtml(c.name)}</strong>
                    <span style="font-size: 0.8rem; color: var(--text-muted); margin-left: 0.8rem;">(${c.photo_count || 0} photos)</span>
                </div>
                <button class="btn-icon delete-cat-btn" data-id="${c.id}" style="width: 28px; height: 28px; color: #ef4444;" title="Delete Category">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `).join('');

        catContainer.querySelectorAll('.delete-cat-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                if (confirm('Are you sure you want to delete this category?')) {
                    await db.deleteCategory(btn.dataset.id);
                    renderCategoryManagerList();
                    populateCategorySelect();
                    renderAdminStats();
                    if (window.loadPortfolio) window.loadPortfolio();
                }
            });
        });
    }

    // Add New Category Form Handler
    const addCatForm = document.getElementById('addCategoryForm');
    if (addCatForm) {
        addCatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = document.getElementById('newCategoryNameInput').value.trim();
            if (name) {
                await db.addCategory(name);
                document.getElementById('newCategoryNameInput').value = '';
                renderCategoryManagerList();
                populateCategorySelect();
                renderAdminStats();
                if (window.loadPortfolio) window.loadPortfolio();
            }
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
});
