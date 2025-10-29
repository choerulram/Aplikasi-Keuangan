<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Aplikasi Keuangan' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?= view('layouts/header') ?>
    <?= view('layouts/sidebar') ?>
    <!-- Use md:ml-60 so mobile doesn't keep left margin when sidebar is off-canvas -->
    <main class="md:ml-60 pt-24 px-4 md:px-8 min-h-screen">
        <?= $this->renderSection('content') ?>
    </main>
    <?= view('layouts/footer') ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</html>
