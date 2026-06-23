<?= view('admin/layouts/header') ?>

<style>
.chat-box { max-height: 400px; overflow-y: auto; padding: 1rem; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; }
.chat-msg { margin-bottom: 1rem; display: flex; }
.chat-msg.customer { justify-content: flex-end; }
.chat-msg.admin { justify-content: flex-start; }
.chat-bubble { max-width: 80%; padding: 10px 14px; border-radius: 14px; font-size: 0.9rem; line-height: 1.5; }
.chat-msg.customer .chat-bubble { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border-bottom-right-radius: 4px; }
.chat-msg.admin .chat-bubble { background: var(--border-color); color: var(--text-primary); border-bottom-left-radius: 4px; }
.chat-bubble small { display: block; margin-top: 4px; opacity: 0.7; font-size: 0.7rem; }
.chat-bubble .chat-file { display: inline-block; margin-top: 4px; padding: 4px 10px; background: rgba(0,0,0,0.15); border-radius: 6px; color: inherit; text-decoration: none; font-size: 0.8rem; }
.chat-msg.admin .chat-bubble .chat-file { background: rgba(0,0,0,0.08); }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Custom Request #<?= $request['id'] ?></h4>
    <a href="<?= site_url('/admin/custom-requests') ?>" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Customer Details</h5>
                <table class="table table-borderless mb-0">
                    <tr><td style="width:140px"><strong>Name</strong></td><td><?= esc($request['name']) ?></td></tr>
                    <tr><td><strong>Email</strong></td><td><a href="mailto:<?= esc($request['email']) ?>"><?= esc($request['email']) ?></a></td></tr>
                    <tr><td><strong>Type</strong></td><td><span class="badge bg-info"><?= esc($request['request_type']) ?></span></td></tr>
                    <tr><td><strong>Plan</strong></td><td>
    <?php if ($request['plan'] === 'free'): ?><span class="badge bg-secondary">Free</span>
    <?php elseif ($request['plan'] === '99'): ?><span class="badge bg-info">Basic (₹99)</span>
    <?php elseif ($request['plan'] === '249'): ?><span class="badge bg-primary">Pro (₹249)</span>
    <?php elseif ($request['plan'] === '499'): ?><span class="badge bg-warning text-dark">Premium (₹499)</span>
    <?php endif ?>
</td></tr>
                    <tr><td><strong>Status</strong></td><td>
                        <?php if ($request['status'] === 'pending'): ?><span class="badge bg-secondary">Pending</span>
                        <?php elseif ($request['status'] === 'in_progress'): ?><span class="badge bg-primary">In Progress</span>
                        <?php elseif ($request['status'] === 'completed'): ?><span class="badge bg-success">Completed</span>
                        <?php elseif ($request['status'] === 'rejected'): ?><span class="badge bg-danger">Rejected</span>
                        <?php endif ?>
                    </td></tr>
                    <tr><td><strong>Submitted</strong></td><td><?= date('d M Y, h:i A', strtotime($request['created_at'])) ?></td></tr>
                    <?php if ($request['sent_at']): ?>
                    <tr><td><strong>Result Sent</strong></td><td><?= date('d M Y, h:i A', strtotime($request['sent_at'])) ?></td></tr>
                    <?php endif ?>
                </table>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Request Description</h5>
                <p><?= nl2br(esc($request['description'])) ?></p>
            </div>
        </div>

        <?php if ($request['reference_image']): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Reference Image</h5>
                <img src="<?= base_url($request['reference_image']) ?>" class="img-fluid rounded" style="max-height:300px;">
            </div>
        </div>
        <?php endif ?>

        <?php if ($request['result_file']): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Result File</h5>
                <a href="<?= base_url($request['result_file']) ?>" class="btn btn-sm btn-success" download>Download Result</a>
                <?php if (preg_match('/\.(mp3|wav|ogg)$/i', $request['result_file'])): ?>
                    <audio controls class="mt-2 w-100"><source src="<?= base_url($request['result_file']) ?>"></audio>
                <?php elseif (preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $request['result_file'])): ?>
                    <img src="<?= base_url($request['result_file']) ?>" class="img-fluid rounded mt-2" style="max-height:300px;">
                <?php endif ?>
            </div>
        </div>
        <?php endif ?>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-chat-dots me-1"></i>Conversation</h5>
                <div class="chat-box" id="chatBox">
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $m): ?>
                        <div class="chat-msg <?= $m['sender'] ?>">
                            <div class="chat-bubble">
                                <?= nl2br(esc($m['message'])) ?>
                                <?php if ($m['file']): ?>
                                    <a href="<?= base_url($m['file']) ?>" class="chat-file" download>
                                        <i class="bi bi-paperclip me-1"></i>Attachment
                                    </a>
                                <?php endif ?>
                                <small><?= date('d M Y, h:i A', strtotime($m['created_at'])) ?></small>
                            </div>
                        </div>
                        <?php endforeach ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No messages yet.</p>
                    <?php endif ?>
                </div>
                <form method="POST" enctype="multipart/form-data" class="mt-3">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="message">
                    <div class="mb-2">
                        <textarea name="message" class="form-control" rows="2" placeholder="Reply as admin..."></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <input type="file" name="file" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp,image/gif,application/pdf,application/zip,audio/mpeg,audio/wav">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-send me-1"></i>Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Manage Request</h5>
                <form method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="update">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" <?= $request['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="in_progress" <?= $request['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="completed" <?= $request['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="rejected" <?= $request['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Admin Notes</label>
                        <textarea name="admin_notes" class="form-control" rows="4"><?= esc($request['admin_notes'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Result File</label>
                        <input type="file" name="result_file" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif,audio/mpeg,audio/wav,audio/ogg,audio/mp4,application/zip">
                        <small class="text-muted">Upload the final AI art image or audio file to send to the customer.</small>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="send_email" value="1" id="sendEmail" checked>
                        <label class="form-check-label" for="sendEmail">Send result to customer via email</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Request</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var chat = document.getElementById('chatBox');
    if (chat) chat.scrollTop = chat.scrollHeight;
});
</script>

<?= view('admin/layouts/footer') ?>
