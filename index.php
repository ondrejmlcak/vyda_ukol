<?php
// Jména členů skupiny: Jméno Příjmení, Jméno Příjmení (DOPLŇTE)

function get_post_value($key, $default = '') {
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

function escape_html($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

$themes = [
    'web' => 'Webová aplikace',
    'mobile' => 'Mobilní aplikace',
    'data' => 'Práce s daty',
    'other' => 'Jiné'
];

$errors = [];
$is_submitted = $_SERVER['REQUEST_METHOD'] === 'POST';

$team_name = get_post_value('team_name');
$member1 = get_post_value('member1');
$member2 = get_post_value('member2');
$member3 = get_post_value('member3');
$project_name = get_post_value('project_name');
$theme = get_post_value('theme');
$description = get_post_value('description');

if ($is_submitted) {
    if (strlen($team_name) === 0) {
        $errors[] = 'Vyplňte název týmu.';
    } elseif (strlen($team_name) > 40) {
        $errors[] = 'Název týmu může mít nejvýše 40 znaků.';
    }

    if (strlen($member1) === 0) $errors[] = 'Vyplňte jméno člena 1.';
    if (strlen($member2) === 0) $errors[] = 'Vyplňte jméno člena 2.';
    if (strlen($member3) === 0) $errors[] = 'Vyplňte jméno člena 3.';

    if (strlen($project_name) === 0) $errors[] = 'Vyplňte název projektu.';

    if (!in_array($theme, array_keys($themes))) {
        $errors[] = 'Vyberte téma projektu ze seznamu.';
    }

    if (strlen($description) < 20) {
        $errors[] = 'Popis musí mít alespoň 20 znaků.';
    }
}

$is_success = $is_submitted && count($errors) === 0;
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihláška týmu na minikonferenci</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body {
            background: linear-gradient(-45deg, #4facfe, #00f2fe, #43e97b, #38f9d7);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            font-family: 'Poppins', sans-serif;
            color: #2d3436;
            min-height: 100vh;
            padding-top: 2rem;
            padding-bottom: 3rem;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.5);
            max-width: 850px;
            margin: 0 auto;
        }

        .page-title {
            font-weight: 800;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            margin-bottom: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e1e5eb;
            padding: 0.8rem 1.2rem;
            font-size: 1rem;
            background-color: #f8fafc;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: auto;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: #4facfe;
            box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.15);
        }

        label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(118, 75, 162, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(118, 75, 162, 0.4);
            color: white;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .vip-ticket {
            position: relative;
            background: #fff;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            margin-top: 2rem;
        }

        .vip-ticket::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            width: 70px;
            height: 12px;
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
            box-shadow: inset 0 3px 6px rgba(0,0,0,0.2);
            z-index: 10;
        }

        .ticket-header {
            background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);
            color: white;
            padding: 3.5rem 2rem 2rem;
            text-align: center;
            border-bottom: 3px dashed rgba(255,255,255,0.4);
        }

        .ticket-header h3 {
            margin: 0;
            font-weight: 800;
            font-size: 2rem;
            letter-spacing: 1px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .ticket-body {
            background: #ffffff;
            padding: 2.5rem;
            position: relative;
        }

        .info-block {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            border-left: 5px solid #4facfe;
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #a0aec0;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 0.3rem;
        }

        .info-value {
            font-size: 1.15rem;
            color: #2d3748;
            font-weight: 600;
        }

        .theme-badge {
            background: #4facfe;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(79, 172, 254, 0.2);
        }

        .alert {
            border-radius: 12px;
            border: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="glass-panel">
        <h1 class="page-title">Přihláška na minikonferenci</h1>

        <?php if ($is_success): ?>

            <?php
            $members_array = [$member1, $member2, $member3];
            $members_string = implode(', ', $members_array);

            $words = explode(' ', $description);
            $word_count = 0;
            foreach ($words as $word) {
                if (strlen(trim($word)) > 0) {
                    $word_count++;
                }
            }
            $char_count = strlen($description);
            $submit_time = date('d.m.Y H:i');
            ?>

            <div class="alert alert-success shadow-sm d-flex align-items-center p-4">
                <div>
                    <h5 class="mb-1 font-weight-bold">Úspěch!</h5>
                    <p class="mb-0">Vaše přihláška byla úspěšně zpracována a místo na konferenci je zajištěno.</p>
                </div>
            </div>

            <div class="card vip-ticket mb-5">
                <div class="ticket-header">
                    <h3><?= escape_html($team_name) ?></h3>
                    <div class="mt-3">
                        <span class="theme-badge"><?= escape_html($themes[$theme]) ?></span>
                    </div>
                </div>

                <div class="card-body ticket-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-block">
                                <div class="info-label">Název projektu</div>
                                <div class="info-value text-primary"><?= escape_html($project_name) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-block" style="border-left-color: #43e97b;">
                                <div class="info-label">Členové týmu</div>
                                <div class="info-value" style="font-size: 1rem;"><?= escape_html($members_string) ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="info-label mt-2">Popis projektu</div>
                    <div class="bg-light p-3 rounded" style="border: 1px solid #e2e8f0; color: #4a5568; font-size: 0.95rem;">
                        <?= nl2br(escape_html($description)) ?>
                    </div>

                    <?php if (str_contains($description, 'PHP') || str_contains($description, 'php')): ?>
                        <div class="alert mt-4 mb-0" style="background-color: #ebf4ff; border-left: 4px solid #3182ce; color: #2b6cb0;">
                            💡 <strong>V popisu je PHP.</strong> Na konferenci bude stát za zmínku, kterou část PHP použijete.
                        </div>
                    <?php endif; ?>

                    <hr class="mt-4 mb-3">
                    <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 0.85rem; font-weight: 500;">
                        <span>Délka: <?= $char_count ?> znaků | <?= $word_count ?> slov</span>
                        <span><?= $submit_time ?></span>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="index.php" class="btn btn-dark px-5 py-3 shadow-sm" style="border-radius: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Zadat novou přihlášku</a>
            </div>

        <?php else: ?>

            <div>
                <?php if ($is_submitted && count($errors) > 0): ?>
                    <div class="alert alert-danger shadow-sm p-4 mb-4" style="border-left: 6px solid #e53e3e;">
                        <h5 class="font-weight-bold text-danger mb-3">Přihlášku teď nejde přijmout:</h5>
                        <ul class="mb-0" style="font-weight: 500;">
                            <?php foreach ($errors as $error): ?>
                                <li class="mb-1"><?= escape_html($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="">
                    <div class="form-group mb-4">
                        <label for="team_name">Název týmu <span class="text-danger">*</span></label>
                        <input type="text" id="team_name" name="team_name" class="form-control form-control-lg" placeholder="Zadejte název vašeho super týmu" value="<?= escape_html($team_name) ?>" required>
                        <small class="form-text text-muted mt-2">Maximum je 40 znaků.</small>
                    </div>

                    <div class="form-row mb-4">
                        <div class="col-12 mb-2"><label>Členové týmu <span class="text-danger">*</span></label></div>
                        <div class="form-group col-md-4 mb-0">
                            <input type="text" id="member1" name="member1" class="form-control" placeholder="1. člen (Jméno)" value="<?= escape_html($member1) ?>" required>
                        </div>
                        <div class="form-group col-md-4 mb-0 mt-3 mt-md-0">
                            <input type="text" id="member2" name="member2" class="form-control" placeholder="2. člen (Jméno)" value="<?= escape_html($member2) ?>" required>
                        </div>
                        <div class="form-group col-md-4 mb-0 mt-3 mt-md-0">
                            <input type="text" id="member3" name="member3" class="form-control" placeholder="3. člen (Jméno)" value="<?= escape_html($member3) ?>" required>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="project_name">Název projektu <span class="text-danger">*</span></label>
                        <input type="text" id="project_name" name="project_name" class="form-control form-control-lg" placeholder="Jak se váš projekt jmenuje?" value="<?= escape_html($project_name) ?>" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="theme">Téma projektu <span class="text-danger">*</span></label>
                        <select id="theme" name="theme" class="form-control form-control-lg" style="cursor: pointer;">
                            <option value="">— vyberte kategorii —</option>
                            <?php foreach ($themes as $key => $label): ?>
                                <option value="<?= escape_html($key) ?>" <?= $theme === $key ? 'selected' : '' ?>>
                                    <?= escape_html($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-5">
                        <label for="description">Popis projektu <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" class="form-control" rows="5" placeholder="Detailně popište, co váš projekt dělá, jaké technologie používá... (min. 20 znaků)" required><?= escape_html($description) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-block btn-submit mt-2">Odeslat přihlášku na konferenci</button>
                </form>
            </div>

        <?php endif; ?>
    </div>
</div>
</body>
</html>