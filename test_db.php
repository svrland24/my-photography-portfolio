<?php
require_once __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test | Aperture Vision</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background: var(--bg-dark);">

    <div style="width: 100%; max-width: 580px; padding: 2.5rem; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <i class="fa-solid fa-database fa-3x" style="background: var(--accent-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
            <h2 style="margin-top: 0.8rem; font-family: var(--font-heading);">Database Connection Test</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Mode: <strong style="color: var(--accent-glow);"><?php echo strtoupper($db_mode); ?></strong></p>
        </div>

        <?php if (!isset($db_error) && isset($pdo) && $pdo !== null): ?>
            <div style="padding: 1.2rem; background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; border-radius: 12px; color: #34d399; margin-bottom: 1.5rem; text-align: center;">
                <i class="fa-solid fa-circle-check fa-2x"></i>
                <h3 style="margin-top: 0.5rem;">Connection Successful!</h3>
                <p style="font-size: 0.9rem; margin-top: 0.2rem; color: #a7f3d0;">Your website is connected to <strong><?php echo $db_mode === 'supabase' ? 'Supabase PostgreSQL' : 'Local XAMPP MySQL'; ?></strong>.</p>
            </div>

            <div style="background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 8px; font-size: 0.85rem; color: var(--text-muted);">
                <p><strong>Host:</strong> <code><?php echo htmlspecialchars($db_mode === 'supabase' ? $supabase_host : $mysql_host); ?></code></p>
                <p><strong>Database:</strong> <code><?php echo htmlspecialchars($db_mode === 'supabase' ? $supabase_db : $mysql_name); ?></code></p>
            </div>

            <a href="/index.php" class="btn-admin" style="width: 100%; justify-content: center; margin-top: 1.5rem;">
                <i class="fa-solid fa-globe"></i> Open Photography Portfolio Website &rarr;
            </a>

        <?php else: ?>
            <div style="padding: 1.2rem; background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 12px; color: #f87171; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-circle-exclamation fa-2x" style="display: block; text-align: center; margin-bottom: 0.5rem;"></i>
                <h3 style="text-align: center; margin-bottom: 0.4rem;">Connection Pending / Error</h3>
                <p style="font-size: 0.88rem; line-height: 1.5; color: #fca5a5;"><?php echo htmlspecialchars($db_error); ?></p>
            </div>

            <div style="background: rgba(0,0,0,0.3); padding: 1.2rem; border-radius: 8px; font-size: 0.85rem; color: var(--text-main); line-height: 1.6;">
                <p style="font-weight: 700; color: var(--accent-glow); margin-bottom: 0.5rem;"><i class="fa-solid fa-lightbulb"></i> How to resolve:</p>
                <ol style="margin-left: 1.2rem; color: var(--text-muted);">
                    <li>In Supabase, open the <strong>Connect</strong> modal.</li>
                    <li>Click the 3rd tab: <strong>Direct</strong> (Connection string).</li>
                    <li>Copy the <strong>Host</strong> name (e.g. <code>aws-0-xx.pooler.supabase.com</code> or <code>db.xxx.supabase.co</code>).</li>
                    <li>Paste it into <code>includes/config.php</code> and save.</li>
                </ol>
            </div>
        <?php endif; ?>

        <div style="margin-top: 1.5rem; text-align: center;">
            <a href="/test_db.php" style="font-size: 0.85rem; color: var(--text-muted); text-decoration: underline;">
                <i class="fa-solid fa-rotate-right"></i> Re-test Connection
            </a>
        </div>

    </div>

</body>
</html>
