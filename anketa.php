<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анкета | Лабораторная работа №4</title>
    <link rel="stylesheet" href="style.css">
    <!-- библиотека canvas-confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1"></script>
</head>
<body>
<div class="gradient-bg"></div>
<div class="blob blob1"></div>
<div class="blob blob2"></div>
<div class="blob blob3"></div>

<div class="container">
    <div class="site-header">
        <h1>Анкета</h1>
        <div class="nav-links">
            <a href="index.php">Главная</a>
            
            <a href="v.php">Просмотр анкет</a>
        </div>
    </div>

    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $msg): ?>
            <?= $msg ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="post" action="index.php" id="mainForm">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <div class="form-group">
            <label>ФИО</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($values['full_name'] ?? '') ?>"
                   <?= !empty($errors['full_name']) ? 'class="error"' : '' ?>>
            <?php if (!empty($errors['full_name'])): ?>
                <span class="field-error">Некорректное ФИО</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Телефон</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($values['phone'] ?? '') ?>"
                   <?= !empty($errors['phone']) ? 'class="error"' : '' ?>>
            <?php if (!empty($errors['phone'])): ?>
                <span class="field-error">Некорректный телефон</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" value="<?= htmlspecialchars($values['email'] ?? '') ?>"
                   <?= !empty($errors['email']) ? 'class="error"' : '' ?>>
            <?php if (!empty($errors['email'])): ?>
                <span class="field-error">Некорректный email</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Дата рождения</label>
            <input type="date" name="birth_date" value="<?= htmlspecialchars($values['birth_date'] ?? '') ?>"
                   <?= !empty($errors['birth_date']) ? 'class="error"' : '' ?>>
            <?php if (!empty($errors['birth_date'])): ?>
                <span class="field-error">Некорректная дата</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Пол</label>
            <div class="radio-group">
                <label><input type="radio" name="gender" value="male" <?= ($values['gender'] ?? '') === 'male' ? 'checked' : '' ?>> Мужской</label>
                <label><input type="radio" name="gender" value="female" <?= ($values['gender'] ?? '') === 'female' ? 'checked' : '' ?>> Женский</label>
            </div>
            <?php if (!empty($errors['gender'])): ?>
                <span class="field-error">Выберите пол</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Любимые языки программирования</label>
            <select name="languages[]" multiple size="6">
                <?php foreach ($languages_from_db as $lang): ?>
                    <option value="<?= htmlspecialchars($lang) ?>" <?= in_array($lang, $values['languages'] ?? []) ? 'selected' : '' ?>><?= htmlspecialchars($lang) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['languages'])): ?>
                <span class="field-error">Выберите хотя бы один язык</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Биография</label>
            <textarea name="biography" rows="5"><?= htmlspecialchars($values['biography'] ?? '') ?></textarea>
            <?php if (!empty($errors['biography'])): ?>
                <span class="field-error">Биография слишком длинная</span>
            <?php endif; ?>
        </div>

        <div class="form-group checkbox">
            <label>
                <input type="checkbox" name="contract_accepted" value="1" <?= !empty($values['contract_accepted']) ? 'checked' : '' ?>>
                Я ознакомлен(а) с контрактом
            </label>
            <?php if (!empty($errors['contract_accepted'])): ?>
                <span class="field-error">Необходимо подтвердить согласие</span>
            <?php endif; ?>
        </div>

        <button type="submit">Сохранить</button>
    </form>

    <div class="site-footer">
        <p>&copy; 2026 Учебный проект | Все права защищены</p>
    </div>
</div>

<script>
    const form = document.getElementById('mainForm');
    form.addEventListener('submit', function(e) {
        // Проверим, есть ли сообщение об успехе (вставляется только после сохранения)
        // Мы не можем здесь узнать, были ли ошибки. Но можно подождать ответа сервера.
        // Чтобы конфетти срабатывало только при успехе, лучше проверять наличие класса .success-message в ответе.
        // Однако проще: будем запускать анимацию, если форма отправляется без явных ошибок.
        // Но для 100% точности – после перезагрузки страницы сработает скрипт, если есть .success-message.
    });

    // Если на странице есть .success-message – значит, отправка прошла успешно → конфетти
    window.addEventListener('load', function() {
        if (document.querySelector('.success-message')) {
            canvasConfetti({
                particleCount: 200,
                spread: 70,
                origin: { y: 0.6 },
                startVelocity: 20,
                colors: ['#2c3e66', '#5a7c9e', '#ffffff']
            });
            // добавим ещё один взрыв
            setTimeout(() => {
                canvasConfetti({
                    particleCount: 100,
                    spread: 100,
                    origin: { y: 0.5, x: 0.3 },
                });
            }, 150);
            setTimeout(() => {
                canvasConfetti({
                    particleCount: 100,
                    spread: 100,
                    origin: { y: 0.5, x: 0.7 },
                });
            }, 300);
        }
    });
</script>
</body>
</html>