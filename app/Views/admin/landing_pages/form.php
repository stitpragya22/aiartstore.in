<?= view('admin/layouts/header') ?>

<style>
.section-card { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem; }
.section-card h6 { color: var(--accent-secondary); font-weight: 600; margin-bottom: 1rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; }
.section-card h6 i { transition: transform 0.2s; }
.section-card.collapsed .section-body { display: none; }
.section-card.collapsed h6 i { transform: rotate(-90deg); }
.form-label small { color: var(--text-muted); font-weight: 400; }
.image-preview { max-width: 120px; max-height: 80px; border-radius: 8px; border: 1px solid var(--border-color); margin-top: 4px; }
</style>

<div class="card-admin">
    <form action="<?= isset($page) ? site_url('/admin/landing-pages/edit/' . $page['id']) : site_url('/admin/landing-pages/create') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row g-4">
            <div class="col-md-8">

                <!-- === SEO & BASICS === -->
                <div class="section-card">
                    <h6 onclick="this.closest('.section-card').classList.toggle('collapsed')">SEO & Basics <i class="bi bi-chevron-down"></i></h6>
                    <div class="section-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <small>(Internal label)</small></label>
                            <input type="text" name="title" class="form-control" value="<?= old('title', $page['title'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="2"><?= old('meta_description', $page['meta_description'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keywords <small>(comma-separated)</small></label>
                            <input type="text" name="keywords" class="form-control" value="<?= old('keywords', $page['keywords'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Language</label>
                            <select name="language" class="form-select">
                                <option value="en" <?= old('language', $page['language'] ?? 'en') == 'en' ? 'selected' : '' ?>>English</option>
                                <option value="hi" <?= old('language', $page['language'] ?? '') == 'hi' ? 'selected' : '' ?>>Hindi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" <?= old('status', $page['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= old('status', $page['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- === HERO SECTION === -->
                <div class="section-card">
                    <h6 onclick="this.closest('.section-card').classList.toggle('collapsed')">Hero Section <i class="bi bi-chevron-down"></i></h6>
                    <div class="section-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Headline</label>
                            <input type="text" name="headline" class="form-control" value="<?= old('headline', $page['headline'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Subheadline</label>
                            <input type="text" name="subheadline" class="form-control" value="<?= old('subheadline', $page['subheadline'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Hero Background Image</label>
                            <input type="file" name="hero_image_backgroun" class="form-control" accept="image/jpeg,image/png,image/webp">
                            <?php if (!empty($page['hero_image_backgroun'])): ?>
                                <img src="<?= base_url('uploads/landing_pages/' . $page['hero_image_backgroun']) ?>" class="image-preview">
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">YouTube Video Link <small>(hero background video)</small></label>
                            <input type="url" name="video_link_youtube" class="form-control" value="<?= old('video_link_youtube', $page['video_link_youtube'] ?? '') ?>" placeholder="https://youtube.com/watch?v=...">
                        </div>
                    </div>
                </div>

                <!-- === PRICING & OFFER === -->
                <div class="section-card">
                    <h6 onclick="this.closest('.section-card').classList.toggle('collapsed')">Pricing & Offer <i class="bi bi-chevron-down"></i></h6>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Old Price <small>(strikethrough)</small></label>
                                <input type="text" name="old_price_of_seminar" class="form-control" value="<?= old('old_price_of_seminar', $page['old_price_of_seminar'] ?? '') ?>" placeholder="₹999">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">New Price / Offer</label>
                                <input type="text" name="new_price_of_seminar" class="form-control" value="<?= old('new_price_of_seminar', $page['new_price_of_seminar'] ?? '') ?>" placeholder="₹49">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Actual Price <small>(numeric)</small></label>
                                <input type="number" step="0.01" name="price" class="form-control" value="<?= old('price', $page['price'] ?? 0) ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Redirection Link <small>(where CTA goes)</small></label>
                            <input type="url" name="redirection_link" class="form-control" value="<?= old('redirection_link', $page['redirection_link'] ?? '') ?>" placeholder="https://aiartstore.in/shop/product-slug">
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Last Date <small>(offer expiry)</small></label>
                                <input type="date" name="lastdate" class="form-control" value="<?= old('lastdate', $page['lastdate'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Date <small>(event date)</small></label>
                                <input type="date" name="date" class="form-control" value="<?= old('date', $page['date'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Time</label>
                                <input type="text" name="time" class="form-control" value="<?= old('time', $page['time'] ?? '') ?>" placeholder="7:00 PM">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reserve Seat Message</label>
                            <input type="text" name="reserv_seat_messsage" class="form-control" value="<?= old('reserv_seat_messsage', $page['reserv_seat_messsage'] ?? '') ?>" placeholder="Only 10 seats left!">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Timer <small>(countdown in minutes)</small></label>
                            <input type="number" name="timer_time_in_minutes" class="form-control" value="<?= old('timer_time_in_minutes', $page['timer_time_in_minutes'] ?? '') ?>" placeholder="e.g. 30">
                        </div>
                    </div>
                </div>

                <!-- === FEATURE IMAGES === -->
                <div class="section-card">
                    <h6 onclick="this.closest('.section-card').classList.toggle('collapsed')">Feature Images <small>(6 max)</small> <i class="bi bi-chevron-down"></i></h6>
                    <div class="section-body">
                        <div class="row">
                            <?php for ($i = 1; $i <= 6; $i++): ?>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Feature Image <?= $i ?></label>
                                <input type="file" name="feature_image_<?= $i ?>" class="form-control" accept="image/jpeg,image/png,image/webp">
                                <?php if (!empty($page['feature_image_' . $i])): ?>
                                    <img src="<?= base_url('uploads/landing_pages/' . $page['feature_image_' . $i]) ?>" class="image-preview">
                                <?php endif; ?>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <!-- === INTRO SECTION === -->
                <div class="section-card">
                    <h6 onclick="this.closest('.section-card').classList.toggle('collapsed')">Intro Section <i class="bi bi-chevron-down"></i></h6>
                    <div class="section-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Intro Image</label>
                            <input type="file" name="intro_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                            <?php if (!empty($page['intro_image'])): ?>
                                <img src="<?= base_url('uploads/landing_pages/' . $page['intro_image']) ?>" class="image-preview">
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Intro Title</label>
                            <input type="text" name="intro_title" class="form-control" value="<?= old('intro_title', $page['intro_title'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Intro Content</label>
                            <textarea name="intro_content" class="form-control" rows="4"><?= old('intro_content', $page['intro_content'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Intro Video Link</label>
                            <input type="url" name="intro_video_link" class="form-control" value="<?= old('intro_video_link', $page['intro_video_link'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Join Button Text</label>
                            <input type="text" name="_intro_join_button_text" class="form-control" value="<?= old('_intro_join_button_text', $page['_intro_join_button_text'] ?? '') ?>" placeholder="Join Now">
                        </div>
                    </div>
                </div>

                <!-- === WORKSHOP / OFFERINGS === -->
                <div class="section-card">
                    <h6 onclick="this.closest('.section-card').classList.toggle('collapsed')">Offerings / Workshops <small>(up to 6)</small> <i class="bi bi-chevron-down"></i></h6>
                    <div class="section-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Section Title</label>
                            <input type="text" name="workshop_section_title" class="form-control" value="<?= old('workshop_section_title', $page['workshop_section_title'] ?? '') ?>" placeholder="What You'll Get">
                        </div>
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                        <div class="border rounded p-3 mb-3" style="border-color: var(--border-color) !important;">
                            <label class="form-label fw-semibold">Offering <?= $i ?></label>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <input type="file" name="workshop_image_<?= $i ?>" class="form-control" accept="image/jpeg,image/png,image/webp">
                                    <?php if (!empty($page['workshop_image_' . $i])): ?>
                                        <img src="<?= base_url('uploads/landing_pages/' . $page['workshop_image_' . $i]) ?>" class="image-preview">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="text" name="workshop_title_<?= $i ?>" class="form-control mb-2" value="<?= old('workshop_title_' . $i, $page['workshop_title_' . $i] ?? '') ?>" placeholder="Title">
                                    <textarea name="workshop_details_<?= $i ?>" class="form-control" rows="2" placeholder="Details"><?= old('workshop_details_' . $i, $page['workshop_details_' . $i] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- === TESTIMONIALS === -->
                <div class="section-card">
                    <h6 onclick="this.closest('.section-card').classList.toggle('collapsed')">Testimonials <i class="bi bi-chevron-down"></i></h6>
                    <div class="section-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Section Title</label>
                            <input type="text" name="testimonial_section_title" class="form-control" value="<?= old('testimonial_section_title', $page['testimonial_section_title'] ?? '') ?>">
                        </div>
                        <?php for ($i = 1; $i <= 3; $i++): ?>
                        <div class="border rounded p-3 mb-3" style="border-color: var(--border-color) !important;">
                            <label class="form-label fw-semibold">Testimonial <?= $i ?></label>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label"><small>Image</small></label>
                                    <input type="file" name="testimonial_image_<?= $i ?>" class="form-control" accept="image/jpeg,image/png,image/webp">
                                    <?php if (!empty($page['testimonial_image_' . $i])): ?>
                                        <img src="<?= base_url('uploads/landing_pages/' . $page['testimonial_image_' . $i]) ?>" class="image-preview">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label"><small>Video Link</small></label>
                                    <input type="url" name="testimonial_video_link_<?= $i ?>" class="form-control" value="<?= old('testimonial_video_link_' . $i, $page['testimonial_video_link_' . $i] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- === FOOTER === -->
                <div class="section-card">
                    <h6 onclick="this.closest('.section-card').classList.toggle('collapsed')">Footer Section <i class="bi bi-chevron-down"></i></h6>
                    <div class="section-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Footer Title</label>
                            <input type="text" name="footer_section_title" class="form-control" value="<?= old('footer_section_title', $page['footer_section_title'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Footer Subtitle</label>
                            <input type="text" name="footer_section_subtitle" class="form-control" value="<?= old('footer_section_subtitle', $page['footer_section_subtitle'] ?? '') ?>">
                        </div>
                        <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input type="text" name="footer_link_title_<?= $i ?>" class="form-control" value="<?= old('footer_link_title_' . $i, $page['footer_link_title_' . $i] ?? '') ?>" placeholder="Link title <?= $i ?>">
                            </div>
                            <div class="col-md-8">
                                <input type="url" name="footer_link_<?= $i ?>" class="form-control" value="<?= old('footer_link_' . $i, $page['footer_link_' . $i] ?? '') ?>" placeholder="URL <?= $i ?>">
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- === CUSTOM JS === -->
                <div class="section-card">
                    <h6 onclick="this.closest('.section-card').classList.toggle('collapsed')">Custom JS <i class="bi bi-chevron-down"></i></h6>
                    <div class="section-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Custom JavaScript <small>(e.g. tracking pixels, FB pixel, conversion code)</small></label>
                            <textarea name="custom_js" class="form-control" rows="6" placeholder="&lt;script&gt;...&lt;/script&gt;"><?= old('custom_js', $page['custom_js'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="section-card" style="position: sticky; top: 2rem;">
                    <h6>Actions</h6>
                    <button type="submit" class="btn btn-primary-custom w-100 mb-2"><?= isset($page) ? 'Update' : 'Create' ?> Landing Page</button>
                    <a href="<?= site_url('/admin/landing-pages') ?>" class="btn btn-outline-custom w-100">Cancel</a>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
document.querySelectorAll('.section-card h6').forEach(function(h) {
    if (!h.closest('.section-card').classList.contains('collapsed')) {
        h.closest('.section-card').classList.add('collapsed');
    }
});
</script>

<?= view('admin/layouts/footer') ?>
