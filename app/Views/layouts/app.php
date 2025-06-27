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
    <main class="ml-60 pt-24 px-8 min-h-screen">
        <?= $this->renderSection('content') ?>
    </main>
    <?= view('layouts/footer') ?>
</body>
</html>
