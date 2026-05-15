<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ArtStoreSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('categories')->insertBatch([
            ['name' => 'Abstract', 'slug' => 'abstract', 'description' => 'Abstract AI-generated art with unique patterns and compositions', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Fantasy', 'slug' => 'fantasy', 'description' => 'Magical and fantasy-themed AI artwork', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Landscape', 'slug' => 'landscape', 'description' => 'Breathtaking AI-generated landscapes and scenery', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Portrait', 'slug' => 'portrait', 'description' => 'AI-generated portraits and character designs', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Cyberpunk', 'slug' => 'cyberpunk', 'description' => 'Futuristic cyberpunk and sci-fi AI art', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Minimalist', 'slug' => 'minimalist', 'description' => 'Clean and minimalist AI art compositions', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Surreal', 'slug' => 'surreal', 'description' => 'Surreal and dreamlike AI-generated artwork', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'Nature', 'slug' => 'nature', 'description' => 'Nature-inspired AI art including flowers, animals, and more', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);
        echo "  Categories seeded.\n";

        $cats = $this->db->table('categories')->select('id, name')->get()->getResultArray();
        $catMap = [];
        foreach ($cats as $c) {
            $catMap[$c['name']] = $c['id'];
        }

        $this->db->table('products')->insertBatch([
            ['category_id' => $catMap['Abstract'] ?? 1, 'title' => 'Cosmic Dreams', 'slug' => 'cosmic-dreams', 'description' => 'A mesmerizing abstract composition of cosmic colors and ethereal patterns.', 'price' => 29.99, 'compare_price' => 49.99, 'is_featured' => 1, 'tags' => 'abstract, cosmic, colorful, ethereal, space', 'dimensions' => '4096x4096', 'file_size' => '15 MB', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['category_id' => $catMap['Fantasy'] ?? 2, 'title' => 'Dragon\'s Lair', 'slug' => 'dragons-lair', 'description' => 'An epic fantasy scene depicting a majestic dragon resting in its ancient lair.', 'price' => 34.99, 'compare_price' => null, 'is_featured' => 1, 'tags' => 'fantasy, dragon, medieval, magical, epic', 'dimensions' => '4096x3072', 'file_size' => '18 MB', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['category_id' => $catMap['Landscape'] ?? 3, 'title' => 'Misty Mountains', 'slug' => 'misty-mountains', 'description' => 'A breathtaking landscape of mist-covered mountains at sunrise.', 'price' => 24.99, 'compare_price' => 39.99, 'is_featured' => 1, 'tags' => 'landscape, mountains, mist, sunrise, nature', 'dimensions' => '5120x2880', 'file_size' => '12 MB', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['category_id' => $catMap['Cyberpunk'] ?? 5, 'title' => 'Neon City Nights', 'slug' => 'neon-city-nights', 'description' => 'A vibrant cyberpunk cityscape illuminated by neon lights and holographic ads.', 'price' => 32.99, 'compare_price' => null, 'is_featured' => 1, 'tags' => 'cyberpunk, neon, city, futuristic, sci-fi, night', 'dimensions' => '4096x4096', 'file_size' => '16 MB', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['category_id' => $catMap['Portrait'] ?? 4, 'title' => 'Ethereal Beauty', 'slug' => 'ethereal-beauty', 'description' => 'An AI-generated portrait with luminous skin and celestial elements.', 'price' => 27.99, 'compare_price' => 44.99, 'is_featured' => 0, 'tags' => 'portrait, ethereal, beauty, celestial, fantasy', 'dimensions' => '3072x4096', 'file_size' => '14 MB', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['category_id' => $catMap['Surreal'] ?? 7, 'title' => 'The Floating Islands', 'slug' => 'the-floating-islands', 'description' => 'Surreal masterpiece with floating islands, waterfalls, and impossible geometries.', 'price' => 39.99, 'compare_price' => null, 'is_featured' => 1, 'tags' => 'surreal, islands, floating, dreamlike, fantasy', 'dimensions' => '4096x4096', 'file_size' => '20 MB', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['category_id' => $catMap['Minimalist'] ?? 6, 'title' => 'Zen Garden', 'slug' => 'zen-garden', 'description' => 'A minimalist Japanese zen garden with clean lines and balanced composition.', 'price' => 19.99, 'compare_price' => 29.99, 'is_featured' => 0, 'tags' => 'minimalist, zen, garden, japanese, peaceful', 'dimensions' => '4096x3072', 'file_size' => '10 MB', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['category_id' => $catMap['Nature'] ?? 8, 'title' => 'Enchanted Forest', 'slug' => 'enchanted-forest', 'description' => 'Magical forest with bioluminescent plants and glowing mushrooms.', 'price' => 29.99, 'compare_price' => null, 'is_featured' => 1, 'tags' => 'nature, forest, enchanted, magical, bioluminescent', 'dimensions' => '4096x4096', 'file_size' => '17 MB', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['category_id' => $catMap['Abstract'] ?? 1, 'title' => 'Quantum Fractals', 'slug' => 'quantum-fractals', 'description' => 'Intricate fractal patterns at the intersection of mathematics and art.', 'price' => 22.99, 'compare_price' => 35.99, 'is_featured' => 0, 'tags' => 'abstract, fractals, quantum, geometric, intricate', 'dimensions' => '4096x4096', 'file_size' => '13 MB', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['category_id' => $catMap['Fantasy'] ?? 2, 'title' => 'The Crystal Mage', 'slug' => 'the-crystal-mage', 'description' => 'A powerful mage channeling crystalline magic through ancient runes.', 'price' => 34.99, 'compare_price' => null, 'is_featured' => 0, 'tags' => 'fantasy, mage, crystal, magic, runes, wizard', 'dimensions' => '3072x4096', 'file_size' => '19 MB', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);
        echo "  Products seeded.\n";
    }
}
