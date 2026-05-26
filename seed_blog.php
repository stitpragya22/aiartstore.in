<?php
require __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
$app = new CodeIgniter\CodeIgniter(new Config\App());
$app->initialize();

$db = \Config\Database::connect();

$db->table('blog_categories')->insert([
    'name' => 'AI Art Guides',
    'slug' => 'ai-art-guides',
    'description' => 'Comprehensive guides and tutorials about AI art generation, tools, and techniques.',
    'meta_title' => 'AI Art Guides - Tutorials & Tips for AI-Generated Art',
    'meta_description' => 'Learn how to create stunning AI-generated art with our comprehensive guides. From beginner tips to advanced techniques, master AI art creation.',
    'status' => 'active',
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
]);
$catId = $db->insertID();
echo "Category created, ID: $catId\n";

$authorId = 1;

$posts = [
    [
        'title' => 'How to Create Stunning AI Art: A Complete Beginner\'s Guide',
        'slug' => 'how-to-create-stunning-ai-art-beginners-guide',
        'excerpt' => 'New to AI art? This comprehensive guide walks you through everything from choosing the right tools to creating museum-worthy digital artworks.',
        'content' => '<h2>What is AI Art?</h2>
<p>AI art refers to artwork created with the assistance of artificial intelligence algorithms. These algorithms can generate images from text descriptions, transform existing images, or create entirely new visual concepts based on training data.</p>
<h2>Getting Started with AI Art</h2>
<p>Creating AI art has never been more accessible. Here\'s your step-by-step roadmap:</p>
<h3>1. Choose Your AI Art Platform</h3>
<p>Several platforms offer AI art generation capabilities, from user-friendly web apps to advanced professional tools.</p>
<h3>2. Master Prompt Engineering</h3>
<p>The key to great AI art lies in crafting effective prompts. A well-written prompt describes subject, style, medium, lighting, color palette, and composition.</p>
<h3>3. Iterate and Refine</h3>
<p>AI art generation is an iterative process. Start with broad prompts, then refine based on results.</p>
<h2>Tips for Better AI Art Results</h2>
<ul>
<li>Use descriptive language that paints a vivid picture</li>
<li>Reference specific art styles and artists</li>
<li>Experiment with different parameters</li>
<li>Post-process AI outputs in editing software</li>
</ul>
<h2>Why AI Art is the Future of Digital Creativity</h2>
<p>AI art democratizes creativity, allowing anyone to bring visual ideas to life regardless of traditional artistic skill.</p>',
        'tags' => 'AI art, beginner guide, digital art, prompt engineering, AI tools',
        'focus_keyword' => 'AI art beginner guide',
        'seo_score' => 92,
        'meta_title' => 'How to Create Stunning AI Art: A Complete Beginner\'s Guide (2026)',
        'meta_description' => 'New to AI art? Step-by-step beginner\'s guide covering prompt engineering, tool selection, and pro tips for stunning AI-generated artwork.',
        'status' => 'published',
        'published_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
    ],
    [
        'title' => 'Top 10 AI Art Styles Transforming Digital Creativity in 2026',
        'slug' => 'top-10-ai-art-styles-digital-creativity-2026',
        'excerpt' => 'Discover the most popular AI art styles dominating digital creativity this year, from surreal dreamscapes to hyper-realistic portraits.',
        'content' => '<h2>The Evolution of AI Art Styles</h2>
<p>AI art has evolved far beyond simple image generation. Today\'s AI models can replicate and reimagine virtually any artistic style.</p>
<h2>1. Surreal Dreamscapes</h2>
<p>Surrealism has found a natural home in AI art. Artists use AI to create impossible landscapes and dreamlike scenes.</p>
<h2>2. Hyper-Realistic Portraits</h2>
<p>AI-generated portraits have reached remarkable levels of realism with stunning accuracy in human expression and detail.</p>
<h2>3. Cyberpunk & Sci-Fi</h2>
<p>The cyberpunk aesthetic is one of the most popular AI art styles, perfect for game design and concept art.</p>
<h2>4. Fantasy Landscapes</h2>
<p>From magical forests to floating islands, AI creates breathtaking fantasy environments.</p>
<h2>5. Minimalist Geometric</h2>
<p>Clean lines and bold colors make minimalist geometric AI art perfect for modern interior design.</p>
<h2>6-10. More Styles</h2>
<p>From impressionist to abstract expressionism, steampunk to wildlife art, AI can recreate virtually any visual style with remarkable fidelity.</p>
<blockquote>AI art isn\'t replacing human creativity—it\'s expanding what\'s possible.</blockquote>',
        'tags' => 'AI art styles, digital creativity, surreal AI, cyberpunk art, fantasy AI',
        'focus_keyword' => 'AI art styles',
        'seo_score' => 95,
        'meta_title' => 'Top 10 AI Art Styles Transforming Digital Creativity in 2026',
        'meta_description' => 'Explore the 10 most popular AI art styles of 2026, from surreal dreamscapes to hyper-realistic portraits. Find inspiration for your next AI project.',
        'status' => 'published',
        'published_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
    ],
    [
        'title' => 'How Businesses Can Leverage AI Art for Branding and Marketing',
        'slug' => 'how-businesses-use-ai-art-branding-marketing',
        'excerpt' => 'Learn how forward-thinking businesses use AI-generated art for cost-effective branding, social media content, and marketing campaigns.',
        'content' => '<h2>Why Businesses Are Turning to AI Art</h2>
<p>Visual content is more important than ever. AI art offers businesses a cost-effective way to create unique, high-quality visuals.</p>
<h2>Cost-Effective Visual Content</h2>
<p>Traditional content creation is expensive. AI art generation reduces costs by up to 90% while giving unlimited creative possibilities.</p>
<h2>Brand Identity & Consistency</h2>
<p>AI tools can be trained to understand your brand\'s visual identity, ensuring consistent imagery across all marketing channels.</p>
<h2>Social Media Content</h2>
<p>Social media rewards fresh visual content. AI art enables a steady stream of unique images without exhausting creative resources.</p>
<h2>Product Visualization</h2>
<p>AI art generates product mockups and lifestyle imagery before a product exists, invaluable for pre-launch marketing.</p>
<h2>Real-World Success Stories</h2>
<p>Companies across industries use AI art to reduce content costs, improve engagement, and accelerate marketing timelines.</p>',
        'tags' => 'AI art business, branding, marketing, AI for business, digital content',
        'focus_keyword' => 'AI art for business branding',
        'seo_score' => 90,
        'meta_title' => 'How Businesses Leverage AI Art for Branding & Marketing | AI Art Store',
        'meta_description' => 'Discover how businesses use AI-generated art for cost-effective branding, social media, and marketing. Save 90% on visual content creation.',
        'status' => 'published',
        'published_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
    ],
    [
        'title' => 'AI Art vs Traditional Digital Art: Pros, Cons, and Best Use Cases',
        'slug' => 'ai-art-vs-traditional-digital-art-comparison',
        'excerpt' => 'A balanced comparison of AI-generated art and traditional digital art creation, exploring the strengths and limitations of each approach.',
        'content' => '<h2>The Great Debate: AI Art vs Traditional Digital Art</h2>
<p>As AI art tools become more powerful, the art community debates the merits of AI-generated versus traditionally created digital art.</p>
<h2>What is Traditional Digital Art?</h2>
<p>Traditional digital art involves human artists creating artwork using digital tools. Every creative decision comes from the artist.</p>
<h2>What is AI Art?</h2>
<p>AI art uses machine learning to generate images from text prompts. Human guidance through prompt engineering remains crucial.</p>
<h2>Comparison Table</h2>
<table>
<tr><th>Aspect</th><th>AI Art</th><th>Traditional</th></tr>
<tr><td>Speed</td><td>Seconds</td><td>Hours to days</td></tr>
<tr><td>Cost</td><td>Very low</td><td>Higher</td></tr>
<tr><td>Skill Required</td><td>Prompt engineering</td><td>Drawing + software</td></tr>
<tr><td>Creative Control</td><td>Moderate</td><td>Complete</td></tr>
</table>
<h2>The Best of Both Worlds</h2>
<p>Many professional artists use hybrid workflows: AI for ideation, then refine with traditional techniques. This combines speed with personal touch.</p>
<blockquote>The future isn\'t AI OR traditional—it\'s both, working together.</blockquote>',
        'tags' => 'AI art vs traditional, digital art comparison, AI art pros cons, hybrid workflow',
        'focus_keyword' => 'AI art vs traditional digital art',
        'seo_score' => 93,
        'meta_title' => 'AI Art vs Traditional Digital Art: Pros, Cons & Best Use Cases (2026)',
        'meta_description' => 'Balanced comparison of AI art vs traditional digital art. Learn pros and cons of each approach and discover hybrid workflows for best results.',
        'status' => 'published',
        'published_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
    ],
];

foreach ($posts as $p) {
    $p['category_id'] = $catId;
    $p['author_id'] = $authorId;
    $p['created_at'] = date('Y-m-d H:i:s');
    $p['updated_at'] = date('Y-m-d H:i:s');
    $db->table('blog_posts')->insert($p);
    echo "Inserted: {$p['title']}\n";
}

echo "\nDone! $catId posts inserted.\n";
