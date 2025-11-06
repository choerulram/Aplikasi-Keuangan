<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('Errors.pageNotFound') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f3f4f6;
            font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #374151;
            margin: 0;
        }
        .container-404 {
            max-width: 420px;
            margin: 0 auto;
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .circle {
            width: 72px;
            height: 72px;
            background: #e0e7ff;
            color: #6366f1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(99,102,241,0.08);
        }
        .circle svg {
            width: 40px;
            height: 40px;
        }
        .title-404 {
            font-size: 2rem;
            font-weight: 700;
            color: #6366f1;
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
        }
        .subtitle-404 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1.5rem;
        }
        .desc-404 {
            font-size: 1rem;
            color: #6b7280;
            margin-bottom: 2rem;
            text-align: center;
        }
        .btn-main {
            display: inline-block;
            background: #6366f1;
            color: #fff;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(99,102,241,0.08);
            transition: background 0.2s;
        }
        .btn-main:hover {
            background: #4f46e5;
        }
        @media (min-width: 768px) {
            .container-404 {
                padding: 3rem 0;
                max-width: 520px;
            }
            .circle {
                width: 96px;
                height: 96px;
            }
            .circle svg {
                width: 56px;
                height: 56px;
            }
            .title-404 {
                font-size: 2.5rem;
            }
            .subtitle-404 {
                font-size: 1.25rem;
            }
            .desc-404 {
                font-size: 1.125rem;
            }
            .btn-main {
                font-size: 1.125rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-404">
        <div class="circle">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="title-404">404</div>
        <div class="subtitle-404">Halaman tidak ditemukan</div>
        <div class="desc-404">
            <?php if (ENVIRONMENT !== 'production') : ?>
                <?= nl2br(esc($message)) ?>
            <?php else : ?>
                <?= lang('Errors.sorryCannotFind') ?>
            <?php endif; ?>
        </div>
        <a href="/" class="btn-main">Kembali ke Beranda</a>
    </div>
</body>
</html>
