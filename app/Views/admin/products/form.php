<?= view('admin/layouts/header') ?>

<div class="card-admin">
    <form action="<?= isset($product) ? site_url('/admin/products/edit/' . $product['id']) : site_url('/admin/products/create') ?>" method="POST" enctype="multipart/form-data">
        <div class="row g-4">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" name="title" class="form-control" value="<?= old('title', $product['title'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="5"><?= old('description', $product['description'] ?? '') ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Price (₹)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="<?= old('price', $product['price'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Compare Price (₹)</label>
                        <input type="number" step="0.01" name="compare_price" class="form-control" value="<?= old('compare_price', $product['compare_price'] ?? '') ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tags (comma separated)</label>
                    <input type="text" name="tags" class="form-control" value="<?= old('tags', $product['tags'] ?? '') ?>" placeholder="e.g., abstract, landscape, fantasy">
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
                <div class="mb-3">
                    <label class="form-label fw-semibold">Preview Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <?php if (isset($product) && $product['image']): ?>
                        <div class="mt-2"><img src="<?= base_url('uploads/products/' . $product['image']) ?>" alt="" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;"></div>
                    <?php endif; ?>
                    <small class="text-muted">Watermark will be applied automatically</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">High-Res File (for download)</label>
                    <input type="file" name="file" class="form-control">
                    <?php if (isset($product) && $product['file']): ?>
                        <small class="text-muted">Current: <?= esc($product['file']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label fw-semibold">File Size</label>
                        <input type="text" name="file_size" class="form-control" value="<?= old('file_size', $product['file_size'] ?? '') ?>" placeholder="e.g., 20 MB">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-semibold">Dimensions</label>
                        <input type="text" name="dimensions" class="form-control" value="<?= old('dimensions', $product['dimensions'] ?? '') ?>" placeholder="e.g., 4096x4096">
                    </div>
                </div>
                <div class="d-flex gap-3 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="featured" <?= (old('is_featured', $product['is_featured'] ?? 0) ? 'checked' : '') ?>>
                        <label class="form-check-label" for="featured">Featured</label>
                    </div>
                    <div class="form-check">
                        <select name="status" class="form-select">
                            <option value="active" <?= (old('status', $product['status'] ?? 'active') == 'active') ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= (old('status', $product['status'] ?? '') == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100"><?= isset($product) ? 'Update' : 'Create' ?> Product</button>
            </div>
        </div>
    </form>
</div>

<?= view('admin/layouts/footer') ?>
