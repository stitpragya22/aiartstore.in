<?= view('layouts/header') ?>

<style>
.chat-container { max-height: 500px; overflow-y: auto; padding: 1rem; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; }
.chat-msg { margin-bottom: 1rem; display: flex; }
.chat-msg.customer { justify-content: flex-end; }
.chat-msg.admin { justify-content: flex-start; }
.chat-bubble { max-width: 75%; padding: 12px 16px; border-radius: 16px; font-size: 0.95rem; line-height: 1.5; }
.chat-msg.customer .chat-bubble { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border-bottom-right-radius: 4px; }
.chat-msg.admin .chat-bubble { background: var(--border-color); color: var(--text-primary); border-bottom-left-radius: 4px; }
.chat-bubble small { display: block; margin-top: 4px; opacity: 0.7; font-size: 0.75rem; }
.chat-bubble .chat-file { display: inline-block; margin-top: 6px; padding: 6px 12px; background: rgba(0,0,0,0.15); border-radius: 8px; color: inherit; text-decoration: none; font-size: 0.85rem; }
.chat-msg.admin .chat-bubble .chat-file { background: rgba(0,0,0,0.08); }
.status-timeline { display: flex; align-items: center; gap: 0; margin-bottom: 2rem; }
.step { flex: 1; text-align: center; position: relative; }
.step .dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px; font-size: 0.85rem; font-weight: 600; }
.step.active .dot { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; }
.step.done .dot { background: #198754; color: #fff; }
.step.inactive .dot { background: var(--border-color); color: var(--text-muted); }
.step .label { font-size: 0.75rem; color: var(--text-muted); }
.step.active .label { color: var(--text-primary); font-weight: 600; }
.step + .step::before { content: ''; position: absolute; top: 16px; left: -50%; width: 100%; height: 2px; background: var(--border-color); z-index: -1; }
.step.done + .step::before { background: #198754; }
</style>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="<?= site_url('/custom-request/my') ?>" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i>Back to requests</a>
                        <h3 class="fw-bold mt-2">Request #<?= $request['id'] ?></h3>
                    </div>
                    <span class="badge bg-info"><?= esc($request['request_type']) ?></span>
                </div>

                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="status-timeline">
                            <?php
                            $steps = ['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'];
                            $statuses = array_keys($steps);
                            $currentIdx = array_search($request['status'], $statuses);
                            if ($currentIdx === false) $currentIdx = -1;
                            foreach ($statuses as $i => $s):
                                $cls = $i < $currentIdx ? 'done' : ($i === $currentIdx ? 'active' : 'inactive');
                            ?>
                            <div class="step <?= $cls ?>">
                                <div class="dot"><?= $i < $currentIdx ? '<i class="bi bi-check"></i>' : ($i + 1) ?></div>
                                <div class="label"><?= $steps[$s] ?></div>
                            </div>
                            <?php endforeach ?>
                        </div>

                        <div class="mt-3">
                            <strong>Plan:</strong> <span class="badge bg-secondary">
    <?php if ($request['plan'] === 'free'): ?>Free
    <?php elseif ($request['plan'] === '99'): ?>₹99
    <?php elseif ($request['plan'] === '249'): ?>₹249
    <?php elseif ($request['plan'] === '499'): ?>₹499
    <?php endif ?>
</span>
                            <strong class="ms-3">Submitted:</strong> <?= date('d M Y, h:i A', strtotime($request['created_at'])) ?>
                            <?php if ($request['sent_at']): ?>
                                <strong class="ms-3">Delivered:</strong> <?= date('d M Y, h:i A', strtotime($request['sent_at'])) ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-chat-dots me-2"></i>Conversation
                        </h5>

                        <div class="chat-container" id="chatContainer">
                            <?php if (!empty($messages)): ?>
                                <?php foreach ($messages as $m): ?>
                                <div class="chat-msg <?= $m['sender'] ?>">
                                    <div class="chat-bubble">
                                        <?= nl2br(esc($m['message'])) ?>
                                        <?php if ($m['file']): ?>
                                            <a href="<?= base_url($m['file']) ?>" class="chat-file" download>
                                                <i class="bi bi-paperclip me-1"></i>Download Attachment
                                            </a>
                                        <?php endif ?>
                                        <small><?= date('d M Y, h:i A', strtotime($m['created_at'])) ?></small>
                                    </div>
                                </div>
                                <?php endforeach ?>
                            <?php else: ?>
                                <p class="text-muted text-center py-4">No messages yet. Start the conversation below.</p>
                            <?php endif ?>
                        </div>

                        <form method="POST" enctype="multipart/form-data" class="mt-3">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <textarea name="message" class="form-control" rows="3" placeholder="Type your message here..."></textarea>
                            </div>
                            <div class="d-flex gap-2 align-items-start">
                                <input type="file" name="file" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif,application/pdf,application/zip,audio/mpeg,audio/wav">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-send me-1"></i>Send</button>
                            </div>
                            <small class="text-muted mt-1 d-block">Allowed: images, PDFs, ZIPs, audio files</small>
                        </form>
                    </div>
                </div>

                <?php if ($request['result_file']): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold"><i class="bi bi-download me-2"></i>Delivered File</h5>
                        <a href="<?= base_url($request['result_file']) ?>" class="btn btn-success mt-2" download>
                            <i class="bi bi-cloud-download me-1"></i>Download Your Artwork
                        </a>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var chat = document.getElementById('chatContainer');
    if (chat) chat.scrollTop = chat.scrollHeight;
});
</script>

<?= view('layouts/footer') ?>
