<?php // admin/includes/footer.php ?>
</div><!-- /.page-body -->
</div><!-- /#content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle sidebar mobile
    const sidebar   = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => sidebar.classList.toggle('open'));
    }

    // Auto-dismiss alerts con data-auto-dismiss
    document.querySelectorAll('.alert[data-auto-dismiss]').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 3500);
    });

    // Confirmación antes de eliminar (data-confirm)
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', e => {
            if (!confirm(btn.dataset.confirm || '¿Estás seguro? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });
</script>
</body>
</html>
