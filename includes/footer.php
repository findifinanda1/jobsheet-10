</main>
<footer>
    <p>&copy; 2026 SIMPUS-Mini &mdash; Jobsheet 7</p>
    
    <div style="margin-top: 0.8rem; display: flex; justify-content: center; gap: 0.5rem;">
        <a href="<?php echo $base; ?>debug_session.php" style="padding: 0.35rem 0.75rem; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #555; background: #fff; font-size: 0.85rem;">🔍 Debug Session</a>
        <a href="<?php echo $base; ?>reset_session.php" onclick="return confirm('Yakin ingin mereset seluruh data sesi?');" style="padding: 0.35rem 0.75rem; border: 1px solid #d9534f; border-radius: 4px; text-decoration: none; color: #d9534f; background: #fff; font-size: 0.85rem;">🔄 Reset Data</a>
    </div>
</footer>

<script src="<?php echo $base; ?>assets/js/app.js"></script>
<?php if (!empty($extra_scripts)): foreach ($extra_scripts as $src): ?>
<script src="<?php echo $src; ?>"></script>
<?php endforeach; endif; ?>
</body>
</html>