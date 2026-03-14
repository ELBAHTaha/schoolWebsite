<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'JEFAL Prive'); ?></title>
    <style>
        :root {
            --bg: #f7f7f8;
            --card: #ffffff;
            --text: #1f2937;
            --brand: #0f766e;
            --brand-dark: #115e59;
            --muted: #6b7280;
            --border: #e5e7eb;
        }
        body { margin: 0; font-family: "Segoe UI", Tahoma, sans-serif; background: var(--bg); color: var(--text); }
        .container { width: min(1200px, 92%); margin: 0 auto; }
        .btn { background: var(--brand); color: #fff; border: 0; padding: .6rem 1rem; border-radius: .5rem; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: var(--brand-dark); }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: .8rem; padding: 1rem; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html>
<?php /**PATH C:\Users\dell\Desktop\schoolWebsite-main\backend\resources\views/layouts/app.blade.php ENDPATH**/ ?>