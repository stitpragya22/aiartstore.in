    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() { $('.alert-custom').fadeOut(500); }, 4000);
        });
        function showToast(msg, type, url) {
            var bg = type === 'error' ? 'linear-gradient(135deg, #ef4444, #dc2626)' :
                      type === 'success' ? 'linear-gradient(135deg, #22c55e, #16a34a)' :
                      'linear-gradient(135deg, #8b5cf6, #6366f1)';
            var opts = { text: msg, duration: 4000, gravity: 'top', position: 'right',
                stopOnFocus: true, style: { background: bg, borderRadius: '12px',
                boxShadow: '0 8px 32px rgba(0,0,0,0.3)', fontSize: '0.9rem' } };
            if (url) {
                opts.destination = url;
                opts.newWindow = true;
                opts.text = msg + ' \u2197';
            }
            Toastify(opts).showToast();
        }
    </script>
</body>
</html>
