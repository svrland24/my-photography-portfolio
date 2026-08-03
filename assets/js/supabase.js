/* ========================================================
   Supabase Data Store & API Wrapper
   Manages Photos, Categories & Local Persistence
   ======================================================== */

class SupabaseDataService {
    constructor() {
        this.storageKeyPhotos = 'aperture_photos_db';
        this.storageKeyCategories = 'aperture_categories_db';
        this.initStorage();
    }

    initStorage() {
        if (!localStorage.getItem(this.storageKeyCategories)) {
            localStorage.setItem(this.storageKeyCategories, JSON.stringify(CONFIG.FALLBACK_CATEGORIES));
        }
        if (!localStorage.getItem(this.storageKeyPhotos)) {
            localStorage.setItem(this.storageKeyPhotos, JSON.stringify(CONFIG.FALLBACK_PHOTOS));
        }
    }

    // --- CATEGORIES ---
    async getCategories() {
        try {
            // Attempt Supabase REST fetch
            const res = await fetch(`${CONFIG.SUPABASE_URL}/rest/v1/categories?select=*`, {
                headers: {
                    'apikey': CONFIG.SUPABASE_KEY,
                    'Authorization': `Bearer ${CONFIG.SUPABASE_KEY}`
                }
            });
            if (res.ok) {
                const data = await res.json();
                if (data && data.length > 0) return data;
            }
        } catch (e) {
            console.log('Supabase offline, using local store:', e);
        }

        // Local Storage Fallback
        const categories = JSON.parse(localStorage.getItem(this.storageKeyCategories)) || CONFIG.FALLBACK_CATEGORIES;
        const photos = JSON.parse(localStorage.getItem(this.storageKeyPhotos)) || CONFIG.FALLBACK_PHOTOS;

        return categories.map(cat => ({
            ...cat,
            photo_count: photos.filter(p => p.category_id == cat.id || p.category_slug == cat.slug).length
        }));
    }

    async addCategory(name) {
        const slug = name.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        const newCat = {
            id: Date.now(),
            name: name,
            slug: slug,
            created_at: new Date().toISOString()
        };

        // Try Supabase Insert
        try {
            await fetch(`${CONFIG.SUPABASE_URL}/rest/v1/categories`, {
                method: 'POST',
                headers: {
                    'apikey': CONFIG.SUPABASE_KEY,
                    'Authorization': `Bearer ${CONFIG.SUPABASE_KEY}`,
                    'Content-Type': 'application/json',
                    'Prefer': 'return=representation'
                },
                body: JSON.stringify({ name: name, slug: slug })
            });
        } catch (e) {}

        // Local Storage Insert
        const categories = JSON.parse(localStorage.getItem(this.storageKeyCategories)) || [];
        categories.push(newCat);
        localStorage.setItem(this.storageKeyCategories, JSON.stringify(categories));
        return newCat;
    }

    async deleteCategory(catId) {
        // Try Supabase Delete
        try {
            await fetch(`${CONFIG.SUPABASE_URL}/rest/v1/categories?id=eq.${catId}`, {
                method: 'DELETE',
                headers: {
                    'apikey': CONFIG.SUPABASE_KEY,
                    'Authorization': `Bearer ${CONFIG.SUPABASE_KEY}`
                }
            });
        } catch (e) {}

        let categories = JSON.parse(localStorage.getItem(this.storageKeyCategories)) || [];
        categories = categories.filter(c => c.id != catId);
        localStorage.setItem(this.storageKeyCategories, JSON.stringify(categories));
    }

    // --- PHOTOS ---
    async getPhotos(categorySlug = 'all', searchQuery = '') {
        let photos = [];
        try {
            const res = await fetch(`${CONFIG.SUPABASE_URL}/rest/v1/photos?select=*&order=id.desc`, {
                headers: {
                    'apikey': CONFIG.SUPABASE_KEY,
                    'Authorization': `Bearer ${CONFIG.SUPABASE_KEY}`
                }
            });
            if (res.ok) {
                const data = await res.json();
                if (data && data.length > 0) photos = data;
            }
        } catch (e) {}

        if (photos.length === 0) {
            photos = JSON.parse(localStorage.getItem(this.storageKeyPhotos)) || CONFIG.FALLBACK_PHOTOS;
        }

        // Apply Category Filter
        if (categorySlug && categorySlug !== 'all') {
            photos = photos.filter(p => p.category_slug === categorySlug || p.category_id == categorySlug);
        }

        // Apply Search Filter
        if (searchQuery && searchQuery.trim() !== '') {
            const q = searchQuery.toLowerCase().trim();
            photos = photos.filter(p => 
                (p.title && p.title.toLowerCase().includes(q)) ||
                (p.description && p.description.toLowerCase().includes(q)) ||
                (p.camera && p.camera.toLowerCase().includes(q)) ||
                (p.lens && p.lens.toLowerCase().includes(q)) ||
                (p.location && p.location.toLowerCase().includes(q)) ||
                (p.category_name && p.category_name.toLowerCase().includes(q))
            );
        }

        return photos;
    }

    async addPhoto(photoData) {
        const categories = await this.getCategories();
        const cat = categories.find(c => c.id == photoData.category_id);
        const catName = cat ? cat.name : 'General';
        const catSlug = cat ? cat.slug : 'general';

        const newPhoto = {
            id: Date.now(),
            title: photoData.title,
            description: photoData.description || '',
            category_id: photoData.category_id,
            category_name: catName,
            category_slug: catSlug,
            image_path: photoData.image_path,
            camera: photoData.camera || 'Canon EOS R5',
            lens: photoData.lens || 'RF 24-70mm f/2.8L',
            iso: photoData.iso || '100',
            shutter_speed: photoData.shutter_speed || '1/250s',
            aperture: photoData.aperture || 'f/2.8',
            location: photoData.location || 'Sylhet, Bangladesh',
            is_featured: !!photoData.is_featured,
            views_count: 0,
            created_at: new Date().toISOString()
        };

        // Try Supabase Insert
        try {
            await fetch(`${CONFIG.SUPABASE_URL}/rest/v1/photos`, {
                method: 'POST',
                headers: {
                    'apikey': CONFIG.SUPABASE_KEY,
                    'Authorization': `Bearer ${CONFIG.SUPABASE_KEY}`,
                    'Content-Type': 'application/json',
                    'Prefer': 'return=representation'
                },
                body: JSON.stringify({
                    title: newPhoto.title,
                    description: newPhoto.description,
                    category_id: newPhoto.category_id,
                    image_path: newPhoto.image_path,
                    camera: newPhoto.camera,
                    lens: newPhoto.lens,
                    iso: newPhoto.iso,
                    shutter_speed: newPhoto.shutter_speed,
                    aperture: newPhoto.aperture,
                    location: newPhoto.location,
                    is_featured: newPhoto.is_featured
                })
            });
        } catch (e) {}

        // Local Storage Insert
        const photos = JSON.parse(localStorage.getItem(this.storageKeyPhotos)) || [];
        photos.unshift(newPhoto);
        localStorage.setItem(this.storageKeyPhotos, JSON.stringify(photos));
        return newPhoto;
    }

    async deletePhoto(photoId) {
        // Try Supabase Delete
        try {
            await fetch(`${CONFIG.SUPABASE_URL}/rest/v1/photos?id=eq.${photoId}`, {
                method: 'DELETE',
                headers: {
                    'apikey': CONFIG.SUPABASE_KEY,
                    'Authorization': `Bearer ${CONFIG.SUPABASE_KEY}`
                }
            });
        } catch (e) {}

        let photos = JSON.parse(localStorage.getItem(this.storageKeyPhotos)) || [];
        photos = photos.filter(p => p.id != photoId);
        localStorage.setItem(this.storageKeyPhotos, JSON.stringify(photos));
    }

    async toggleFeatured(photoId) {
        let photos = JSON.parse(localStorage.getItem(this.storageKeyPhotos)) || [];
        const photo = photos.find(p => p.id == photoId);
        if (photo) {
            photo.is_featured = !photo.is_featured;
            localStorage.setItem(this.storageKeyPhotos, JSON.stringify(photos));

            try {
                await fetch(`${CONFIG.SUPABASE_URL}/rest/v1/photos?id=eq.${photoId}`, {
                    method: 'PATCH',
                    headers: {
                        'apikey': CONFIG.SUPABASE_KEY,
                        'Authorization': `Bearer ${CONFIG.SUPABASE_KEY}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ is_featured: photo.is_featured })
                });
            } catch (e) {}
        }
    }
}

const db = new SupabaseDataService();
