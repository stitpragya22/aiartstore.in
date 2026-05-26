<?php
$details = [];
if (isset($product) && !empty($product['details_json'])) {
    $d = json_decode($product['details_json'], true);
    if (is_array($d)) $details = $d;
}
?>
<?= view('admin/layouts/header') ?>

<style>
.type-badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; cursor: pointer; border: 2px solid var(--border-color); background: transparent; color: var(--text-secondary); transition: all 0.2s; }
.type-badge:hover { border-color: var(--accent-primary); color: var(--text-primary); }
.type-badge.active { background: var(--accent-primary); border-color: var(--accent-primary); color: #fff; }
.type-section { display: none; }
.type-section.show { display: block; }
</style>

<div class="card-admin">
    <form action="<?= isset($product) ? site_url('/admin/products/edit/' . $product['id']) : site_url('/admin/products/create') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-4">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Product Type</label>
                    <div class="d-flex gap-2" id="typeSelector">
                        <?php
                        $types = ['art' => 'Art Print', 'ebook' => 'E-Book', 'audio' => 'Audio', 'bundle' => 'Bundle'];
                        $currentType = old('product_type', $product['product_type'] ?? 'art');
                        foreach ($types as $val => $label):
                        ?>
                        <label class="type-badge <?= $currentType === $val ? 'active' : '' ?>" data-type="<?= $val ?>">
                            <input type="radio" name="product_type" value="<?= $val ?>" <?= $currentType === $val ? 'checked' : '' ?> style="display:none">
                            <?= $label ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" name="title" class="form-control" value="<?= old('title', $product['title'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Subtitle / Tagline</label>
                    <input type="text" name="subtitle" class="form-control" value="<?= old('subtitle', $product['subtitle'] ?? '') ?>" placeholder="Short marketing tagline">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description (short)</label>
                    <textarea name="description" class="form-control" rows="3"><?= old('description', $product['description'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Content (full landing page body)</label>
                    <textarea name="content" class="form-control" rows="10" placeholder="Rich HTML content for the landing page"><?= old('content', $product['content'] ?? '') ?></textarea>
                </div>

                <div class="type-section show" data-for="art">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Dimensions</label>
                            <input type="text" name="dimensions" class="form-control" value="<?= old('dimensions', $product['dimensions'] ?? '') ?>" placeholder="e.g. 4096x4096">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">File Size</label>
                            <input type="text" name="file_size" class="form-control" value="<?= old('file_size', $product['file_size'] ?? '') ?>" placeholder="e.g. 20 MB">
                        </div>
                    </div>
                </div>

                <div class="type-section" data-for="ebook">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Author</label>
                            <input type="text" name="details_json[author]" class="form-control" value="<?= old('details_json.author', $details['author'] ?? '') ?>" placeholder="Author name">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Pages</label>
                            <input type="number" name="details_json[pages]" class="form-control" value="<?= old('details_json.pages', $details['pages'] ?? '') ?>" placeholder="120">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Language</label>
                            <input type="text" name="details_json[language]" class="form-control" value="<?= old('details_json.language', $details['language'] ?? '') ?>" placeholder="English">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ISBN</label>
                        <input type="text" name="details_json[isbn]" class="form-control" value="<?= old('details_json.isbn', $details['isbn'] ?? '') ?>" placeholder="Optional">
                    </div>
                </div>

                <div class="type-section" data-for="audio">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Duration</label>
                            <input type="text" name="details_json[duration]" class="form-control" value="<?= old('details_json.duration', $details['duration'] ?? '') ?>" placeholder="e.g. 2h 30m">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Narrator / Artist</label>
                            <input type="text" name="details_json[narrator]" class="form-control" value="<?= old('details_json.narrator', $details['narrator'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Bitrate</label>
                            <input type="text" name="details_json[bitrate]" class="form-control" value="<?= old('details_json.bitrate', $details['bitrate'] ?? '') ?>" placeholder="e.g. 320 kbps">
                        </div>
                    </div>
                </div>

                <div class="type-section" data-for="bundle">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Items in Bundle</label>
                        <textarea name="details_json[bundle_items]" class="form-control" rows="4" placeholder="One item per line, e.g.&#10;1. Cosmic Dreams (Art Print)&#10;2. Zen Garden (Art Print)&#10;3. Bonus: Desktop Wallpaper"><?= old('details_json.bundle_items', $details['bundle_items'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Highlights (one per line)</label>
                    <textarea name="highlights" class="form-control" rows="4" placeholder="e.g.&#10;High-resolution 4K file&#10;Instant digital download&#10;Commercial use license"><?= old('highlights', $product['highlights'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Features (JSON key:value pairs)</label>
                    <textarea name="features" class="form-control" rows="4" placeholder='e.g.&#10;{"Resolution":"4096x4096","Format":"JPEG","Size":"20 MB"}'> <?= old('features', $product['features'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tags (comma separated)</label>
                    <input type="text" name="tags" class="form-control" value="<?= old('tags', $product['tags'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (old('category_id', $product['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label fw-semibold">Price (₹)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="<?= old('price', $product['price'] ?? '') ?>" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-semibold">Compare (₹)</label>
                        <input type="number" step="0.01" name="compare_price" class="form-control" value="<?= old('compare_price', $product['compare_price'] ?? '') ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Preview Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <?php if (isset($product) && $product['image']): ?>
                        <div class="mt-2"><img src="<?= base_url('uploads/products/' . $product['image']) ?>" alt="" style="width:100%;max-height:150px;object-fit:cover;border-radius:8px;"></div>
                    <?php endif; ?>
                    <small class="text-muted">Watermark applied automatically</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">High-Res File (download)</label>
                    <input type="file" name="file" class="form-control">
                    <?php if (isset($product) && $product['file']): ?>
                        <small class="text-muted d-block">Current: <?= esc($product['file']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Preview Files (audio samples, pdf previews)</label>
                    <input type="file" name="preview_files" class="form-control" multiple>
                </div>
                <div class="d-flex gap-3 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="featured" <?= (old('is_featured', $product['is_featured'] ?? 0) ? 'checked' : '') ?>>
                        <label class="form-check-label" for="featured">Featured</label>
                    </div>
                    <select name="status" class="form-select">
                        <option value="active" <?= (old('status', $product['status'] ?? 'active') == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= (old('status', $product['status'] ?? '') == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100"><?= isset($product) ? 'Update' : 'Create' ?> Product</button>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    function showTypeFields(type) {
        $('.type-section').removeClass('show');
        $('.type-section[data-for="' + type + '"]').addClass('show');
        $('.type-badge').removeClass('active');
        $('.type-badge[data-type="' + type + '"]').addClass('active');
    }

    $('#typeSelector').on('click', '.type-badge', function() {
        var type = $(this).data('type');
        $(this).find('input[type="radio"]').prop('checked', true);
        showTypeFields(type);
    });

    showTypeFields($('input[name="product_type"]:checked').val());
});
</script>

<?= view('admin/layouts/footer') ?>
