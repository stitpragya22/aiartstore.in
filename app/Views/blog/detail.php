<?= view('layouts/header') ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "<?= esc($post['title']) ?>",
    "description": "<?= esc(mb_substr(strip_tags($post['excerpt'] ?: $post['content']), 0, 200)) ?>",
    "author": {
        "@type": "Organization",
        "name": "AI Art Store"
    },
    "publisher": {
        "@type": "Organization",
        "name": "AI Art Store"
    },
    "datePublished": "<?= $post['published_at'] ? date('c', strtotime($post['published_at'])) : '' ?>",
    "dateModified": "<?= $post['updated_at'] ? date('c', strtotime($post['updated_at'])) : date('c', strtotime($post['published_at'])) ?>",
    "image": "<?= $post['featured_image'] ? base_url($post['featured_image']) : '' ?>"
}
</script>

<article class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <?php if ($post['category_name']): ?>
                <a href="<?= site_url('blog?category=' . esc($post['category_slug'])) ?>" class="badge bg-primary text-decoration-none mb-2"><?= esc($post['category_name']) ?></a>
            <?php endif ?>

            <h1 class="display-5 fw-bold mb-3"><?= esc($post['title']) ?>
                <?php if (auth()->loggedIn() && auth()->user()->can('admin.access')): ?>
                    <a href="<?= site_url('/admin/blog/posts/edit/' . $post['id']) ?>" class="btn btn-sm btn-outline-secondary ms-2" target="_blank" title="Edit this post">
                        <i class="bi bi-pencil"></i>
                    </a>
                <?php endif ?>
            </h1>

            <div class="text-muted mb-4">
                <span><i class="bi bi-person me-1"></i>AI Art Store Team</span>
                <?php if ($post['published_at']): ?>
                    <span class="ms-3"><i class="bi bi-calendar3 me-1"></i>Published: <?= date('F d, Y', strtotime($post['published_at'])) ?></span>
                <?php endif ?>
                <?php if ($post['updated_at'] && $post['updated_at'] != $post['published_at']): ?>
                    <span class="ms-3"><i class="bi bi-arrow-clockwise me-1"></i>Updated: <?= date('F d, Y', strtotime($post['updated_at'])) ?></span>
                <?php endif ?>
                <?php if ($post['tags']): ?>
                    <span class="ms-3">
                        <?php foreach (explode(',', $post['tags']) as $tag): ?>
                            <span class="badge bg-light text-dark me-1">#<?= esc(trim($tag)) ?></span>
                        <?php endforeach ?>
                    </span>
                <?php endif ?>
            </div>

            <?php if ($post['featured_image']): ?>
                <div class="mb-4">
                    <img src="<?= base_url($post['featured_image']) ?>" class="img-fluid rounded shadow" alt="<?= esc($post['title']) ?>">
                </div>
            <?php endif ?>

            <?php if ($post['excerpt']): ?>
                <div class="lead mb-4 p-3 bg-light rounded" style="color:#1e293b;">
                    <?= esc($post['excerpt']) ?>
                </div>
            <?php endif ?>

            <div class="blog-content">
                <?php
                $cleanContent = strip_tags($post['content'], '<h2><h3><h4><p><br><strong><em><b><i><u><a><ul><ol><li><blockquote><pre><code><img><table><thead><tbody><tr><th><td><hr><span><div><figure><figcaption><section>');
                $cleanContent = preg_replace('/\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\')/i', '', $cleanContent);
                $cleanContent = preg_replace('/href\s*=\s*(?:"javascript:[^"]*"|\'javascript:[^\']*\')/i', 'href="#"', $cleanContent);
                echo $cleanContent;
                ?>
            </div>

            <hr class="my-5">

            <?php if (!empty($recent)): ?>
                <h4 class="mb-4">Recent Posts</h4>
                <div class="row g-3">
                    <?php foreach ($recent as $rp): ?>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php if ($rp['featured_image']): ?>
                                    <a href="<?= site_url('blog/' . esc($rp['slug'])) ?>">
                                        <img src="<?= base_url($rp['featured_image']) ?>" class="card-img-top" alt="<?= esc($rp['title']) ?>" style="height:140px;object-fit:cover">
                                    </a>
                                <?php endif ?>
                                <div class="card-body">
                                    <h6><a href="<?= site_url('blog/' . esc($rp['slug'])) ?>" class="text-decoration-none text-dark"><?= esc($rp['title']) ?></a></h6>
                                    <small class="text-muted"><?= date('M d, Y', strtotime($rp['published_at'])) ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</article>

<style>
.blog-content { font-size: 1.05rem; line-height: 1.9; }
.blog-content h2 { font-size: 1.6rem; margin-top: 2rem; margin-bottom: 1rem; font-weight: 700; }
.blog-content h3 { font-size: 1.3rem; margin-top: 1.5rem; margin-bottom: 0.75rem; font-weight: 600; }
.blog-content p { margin-bottom: 1.25rem; }
.blog-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 1.5rem 0; }
.blog-content blockquote { border-left: 4px solid var(--primary-color, #0d6efd); padding-left: 1rem; margin: 1.5rem 0; color: #6c757d; font-style: italic; }
.blog-content ul, .blog-content ol { margin-bottom: 1.25rem; padding-left: 1.5rem; }
.blog-content a { color: var(--primary-color, #0d6efd); text-decoration: underline; }
.blog-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; }
.blog-content table th, .blog-content table td { border: 1px solid #dee2e6; padding: 0.5rem; }
.blog-content table th { background: #f8f9fa; font-weight: 600; }
.blog-content pre { background: #1e1e2e; color: #cdd6f4; padding: 1rem; border-radius: 8px; overflow-x: auto; }
.blog-content code { font-size: 0.9em; }
</style>

<?= view('layouts/footer') ?>
