/* ========================================================
   Supabase & App Configuration
   ======================================================== */

const CONFIG = {
    // Supabase Cloud Project URL
    SUPABASE_URL: "https://xikfgjbhfrsctzpzartc.supabase.co",
    
    // Supabase Anon / Publishable Public Key
    SUPABASE_KEY: "sb_publishable_anon_key_placeholder", // Will be filled or uses REST API

    // Default Admin PIN / Password
    ADMIN_PIN: "admin123",

    // Initial Fallback Categories (if offline)
    FALLBACK_CATEGORIES: [
        { id: 1, name: "Nature", slug: "nature", photo_count: 2 },
        { id: 2, name: "Portrait", slug: "portrait", photo_count: 1 },
        { id: 3, name: "Street", slug: "street", photo_count: 1 },
        { id: 4, name: "Landscape", slug: "landscape", photo_count: 1 },
        { id: 5, name: "Architecture", slug: "architecture", photo_count: 1 },
        { id: 6, name: "Wildlife", slug: "wildlife", photo_count: 1 }
    ],

    // Initial Fallback Photographs (if offline)
    FALLBACK_PHOTOS: [
        {
            id: 1,
            title: "Golden Hour Mountain Peak",
            description: "A breathtaking view of sunrise over misty mountain ridges.",
            category_id: 4,
            category_name: "Landscape",
            category_slug: "landscape",
            image_path: "https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200&auto=format&fit=crop",
            camera: "Sony A7 IV",
            lens: "FE 16-35mm f/2.8 GM",
            iso: "100",
            shutter_speed: "1/500s",
            aperture: "f/8.0",
            location: "Bandarban, Bangladesh",
            is_featured: true,
            views_count: 142
        },
        {
            id: 2,
            title: "Serene Forest Path",
            description: "Morning light filtering through dense green canopy leaves.",
            category_id: 1,
            category_name: "Nature",
            category_slug: "nature",
            image_path: "https://images.unsplash.com/photo-1448375240586-882707db888b?q=80&w=1200&auto=format&fit=crop",
            camera: "Canon EOS R6",
            lens: "RF 50mm f/1.2L",
            iso: "200",
            shutter_speed: "1/160s",
            aperture: "f/2.0",
            location: "Sreemangal, Sylhet",
            is_featured: false,
            views_count: 98
        },
        {
            id: 3,
            title: "Urban Night Life",
            description: "Vibrant light trails of city traffic under neon lights.",
            category_id: 3,
            category_name: "Street",
            category_slug: "street",
            image_path: "https://images.unsplash.com/photo-1519501025264-65ba15a82390?q=80&w=1200&auto=format&fit=crop",
            camera: "Fujifilm X-T4",
            lens: "XF 23mm f/1.4",
            iso: "800",
            shutter_speed: "2s",
            aperture: "f/5.6",
            location: "Dhaka City, Bangladesh",
            is_featured: true,
            views_count: 215
        },
        {
            id: 4,
            title: "Expressive Soul Portrait",
            description: "A deep mood portrait capturing natural shadows and emotion.",
            category_id: 2,
            category_name: "Portrait",
            category_slug: "portrait",
            image_path: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=1200&auto=format&fit=crop",
            camera: "Sony A7R V",
            lens: "FE 85mm f/1.4 GM",
            iso: "100",
            shutter_speed: "1/320s",
            aperture: "f/1.8",
            location: "Studio Art, Chittagong",
            is_featured: true,
            views_count: 310
        },
        {
            id: 5,
            title: "Modern Glass Facade",
            description: "Geometric symmetry in modern skyscraper architecture.",
            category_id: 5,
            category_name: "Architecture",
            category_slug: "architecture",
            image_path: "https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1200&auto=format&fit=crop",
            camera: "Nikon Z7 II",
            lens: "NIKKOR Z 14-30mm f/4",
            iso: "100",
            shutter_speed: "1/200s",
            aperture: "f/7.1",
            location: "Gulshan, Dhaka",
            is_featured: false,
            views_count: 75
        },
        {
            id: 6,
            title: "Wild Kingfisher Focus",
            description: "A colorful kingfisher waiting patiently on a wooden twig.",
            category_id: 6,
            category_name: "Wildlife",
            category_slug: "wildlife",
            image_path: "https://images.unsplash.com/photo-1552728089-57bdde30beb3?q=80&w=1200&auto=format&fit=crop",
            camera: "Canon EOS R5",
            lens: "RF 100-500mm f/4.5-7.1",
            iso: "1600",
            shutter_speed: "1/2000s",
            aperture: "f/5.6",
            location: "Sundarbans, Khulna",
            is_featured: false,
            views_count: 189
        }
    ]
};
