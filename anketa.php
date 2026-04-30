<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анкета | Лабораторная работа №4</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f5;
            color: #2c2c2c;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Анимированный градиентный фон */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: linear-gradient(125deg, #e0e0e0, #f5f5f5, #d9d9d9, #ececec);
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Blob-фигуры */
        .blob {
            position: fixed;
            width: 300px;
            height: 300px;
            background: rgba(255,255,240,0.4);
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            pointer-events: none;
            animation: floatBlob 20s infinite alternate ease-in-out;
        }
        .blob:nth-child(1) { top: 10%; left: -5%; animation-duration: 25s; background: rgba(200,200,210,0.5); }
        .blob:nth-child(2) { bottom: 10%; right: -10%; width: 400px; height: 400px; animation-duration: 30s; background: rgba(220,220,230,0.4); }
        .blob:nth-child(3) { top: 40%; left: 30%; width: 250px; height: 250px; animation-duration: 18s; background: rgba(180,180,200,0.5); }
        @keyframes floatBlob {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 40px) scale(1.2); }
        }

        /* Шапка */
        .site-header {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 15px 30px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .header-content {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .logo h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }
        .nav-links a {
            color: #555;
            text-decoration: none;
            margin-left: 25px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .nav-links a:hover {
            color: #b87333;
        }

        /* Контейнер формы */
        .form-container {
            max-width: 700px;
            margin: 40px auto;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(2px);
            border-radius: 32px;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            color: #2c2c2c;
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #444;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 16px;
            font-size: 1rem;
            font-family: inherit;
            transition: 0.2s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #b87333;
            outline: none;
            box-shadow: 0 0 0 3px rgba(184,115,51,0.1);
        }
        .radio-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .radio-group label {
            display: inline-flex;
            align-items: center;
            font-weight: normal;
            gap: 8px;
        }
        .checkbox label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        button {
            width: 100%;
            background: #b87333;
            color: white;
            border: none;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 40px;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 10px;
        }
        button:hover {
            background: #9e5e28;
            transform: translateY(-2px);
        }

        .error-messages {
            background: #ffe6e6;
            border-left: 4px solid #e67e22;
            padding: 15px;
            border-radius: 16px;
            margin-bottom: 20px;
            color: #a04000;
        }
        .error-messages ul {
            margin-left: 20px;
        }
        .success-message {
            background: #e0f2e9;
            border-left: 4px solid #2ecc71;
            padding: 15px;
            border-radius: 16px;
            margin-bottom: 20px;
            text-align: center;
            color: #1e6f3f;
        }
        .field-error {
            color: #e67e22;
            font-size: 0.85rem;
            margin-top: 4px;
            display: block;
        }
        input.error, select.error, textarea.error {
            border-color: #e67e22 !important;
        }

        /* Подвал */
        .site-footer {
            background: rgba(30,30,30,0.9);
            backdrop-filter: blur(8px);
            color: #ccc;
            text-align: center;
            padding: 20px;
            font-size: 0.85rem;
            margin-top: 60px;
        }

        @media (max-width: 700px) {
            .form-container {
                margin: 20px;
                padding: 25px;
            }
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            .nav-links a {
                margin: 0 12px;
            }
        }
    </style>
</head>
<body>
<div class="animated-bg"></div>
<div class="blob"></div>
<div class="blob"></div>
<div class="blob"></div>

<header class="site-header">
    <div class="header-content">
        <div class="logo">
            <h1>Анкета</h1>
        </div>
        <div class="nav-links">
            <a href="index.php">Главная</a>
            <a href="v.php">Просмотр анкет</a>
        </div>
    </div>
</header>

<div class="form-container">
    <h2>Заполните анкету</h2>

    <?php if (!empty($messages)): ?>
        <div class="error-messages">
            <ul>
                <?php foreach ($messages as $msg): ?>
                    <li><?= htmlspecialchars($msg) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($_COOKIE['save']) && !empty($messages) && strpos($messages[0], 'успешно') !== false): ?>
        <div class="success-message">Данные успешно сохранены!</div>
    <?php endif; ?>

    <form id="anketa-form" method="post" action="index.php">
        <div class="form-group">
            <label for="full_name">ФИО *</label>
            <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($values['full_name'] ?? '') ?>" <?= !empty($errors['full_name']) ? 'class="error"' : '' ?>>
            <?php if (!empty($errors['full_name'])): ?>
                <span class="field-error">Некорректное ФИО</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="phone">Телефон *</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($values['phone'] ?? '') ?>" <?= !empty($errors['phone']) ? 'class="error"' : '' ?>>
            <?php if (!empty($errors['phone'])): ?>
                <span class="field-error">Некорректный телефон</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">E-mail *</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($values['email'] ?? '') ?>" <?= !empty($errors['email']) ? 'class="error"' : '' ?>>
            <?php if (!empty($errors['email'])): ?>
                <span class="field-error">Некорректный email</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="birth_date">Дата рождения *</label>
            <input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($values['birth_date'] ?? '') ?>" <?= !empty($errors['birth_date']) ? 'class="error"' : '' ?>>
            <?php if (!empty($errors['birth_date'])): ?>
                <span class="field-error">Некорректная дата</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Пол *</label>
            <div class="radio-group">
                <label><input type="radio" name="gender" value="male" <?= ($values['gender'] ?? '') === 'male' ? 'checked' : '' ?> <?= !empty($errors['gender']) ? 'class="error"' : '' ?>> Мужской</label>
                <label><input type="radio" name="gender" value="female" <?= ($values['gender'] ?? '') === 'female' ? 'checked' : '' ?> <?= !empty($errors['gender']) ? 'class="error"' : '' ?>> Женский</label>
            </div>
            <?php if (!empty($errors['gender'])): ?>
                <span class="field-error">Выберите пол</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="languages">Любимые языки программирования * (можно несколько)</label>
            <select id="languages" name="languages[]" multiple size="6" <?= !empty($errors['languages']) ? 'class="error"' : '' ?>>
                <?php foreach ($languages_from_db as $lang): ?>
                    <option value="<?= htmlspecialchars($lang) ?>" <?= in_array($lang, $values['languages'] ?? []) ? 'selected' : '' ?>><?= htmlspecialchars($lang) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['languages'])): ?>
                <span class="field-error">Выберите хотя бы один язык</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="biography">Биография</label>
            <textarea id="biography" name="biography" rows="5" <?= !empty($errors['biography']) ? 'class="error"' : '' ?>><?= htmlspecialchars($values['biography'] ?? '') ?></textarea>
            <?php if (!empty($errors['biography'])): ?>
                <span class="field-error">Не более 10000 символов</span>
            <?php endif; ?>
        </div>

        <div class="form-group checkbox">
            <label>
                <input type="checkbox" name="contract_accepted" value="1" <?= !empty($values['contract_accepted']) ? 'checked' : '' ?> <?= !empty($errors['contract_accepted']) ? 'class="error"' : '' ?>>
                Я ознакомлен(а) с контрактом *
            </label>
            <?php if (!empty($errors['contract_accepted'])): ?>
                <span class="field-error">Необходимо подтвердить согласие</span>
            <?php endif; ?>
        </div>

        <button type="submit" id="submitBtn">Отправить</button>
    </form>
</div>

<footer class="site-footer">
    <p> ЛАБОРАТОРНАЯ РАБОТА №4 </p>
</footer>

<script>
    // Конфетти при успешной отправке
    const form = document.getElementById('anketa-form');
    const urlParams = new URLSearchParams(window.location.search);
    // Если есть признак успешного сохранения в куках, можно показать конфетти
    // Проще: после отправки форма редиректит на index.php с кукой save.
    // Мы проверим наличие сообщения об успехе в DOM.
    window.addEventListener('DOMContentLoaded', () => {
        const successDiv = document.querySelector('.success-message');
        if (successDiv) {
            // Запускаем конфетти
            canvasConfetti({
                particleCount: 150,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#b87333', '#f5a623', '#e67e22', '#ffffff']
            });
            // Дополнительная волна
            setTimeout(() => {
                canvasConfetti({
                    particleCount: 100,
                    spread: 100,
                    origin: { y: 0.7 },
                    startVelocity: 15,
                });
            }, 200);
        }
    });

    // Дополнительная анимация при отправке (если нужно)
    form.addEventListener('submit', function(e) {
        // Можно добавить анимацию на кнопку, но не блокируем отправку
        const btn = document.getElementById('submitBtn');
        btn.style.transform = 'scale(0.98)';
        setTimeout(() => { btn.style.transform = ''; }, 200);
    });
</script>
</body>
</html>