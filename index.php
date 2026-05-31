<?php
header('Content-Type: text/html; charset=UTF-8');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $db_host = 'localhost';
        $db_user = 'u82325';
        $db_pass = '2941524';
        $db_name = 'u82325';
        try {
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            die("Внутренняя ошибка сервера. Попробуйте позже.");
        }
    }
    return $pdo;
}

$allowed_languages = ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python',
    'Java', 'Haskell', 'Clojure', 'Prolog', 'Scala', 'Go'];
$allowed_genders = ['male', 'female'];

// GET – отображение формы
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $messages = [];
    $errors = [];
    $values = [];

    $fields = ['full_name', 'phone', 'email', 'birth_date', 'gender', 'biography', 'contract_accepted', 'languages'];

    foreach ($fields as $field) {
        if (isset($_COOKIE[$field . '_error'])) {
            $errors[$field] = true;
            setcookie($field . '_error', '', 1);
        } else {
            $errors[$field] = false;
        }
    }

    if ($errors['full_name']) $messages[] = '<div class="error-message">ФИО должно содержать только буквы и пробелы (макс. 150 символов).</div>';
    if ($errors['phone']) $messages[] = '<div class="error-message">Телефон: 6–12 цифр, разрешены +, -, (, ), пробел.</div>';
    if ($errors['email']) $messages[] = '<div class="error-message">Введите корректный email.</div>';
    if ($errors['birth_date']) $messages[] = '<div class="error-message">Дата рождения: формат ГГГГ-ММ-ДД, не позже сегодня.</div>';
    if ($errors['gender']) $messages[] = '<div class="error-message">Выберите пол.</div>';
    if ($errors['biography']) $messages[] = '<div class="error-message">Биография не более 10000 символов.</div>';
    if ($errors['contract_accepted']) $messages[] = '<div class="error-message">Подтвердите согласие.</div>';
    if ($errors['languages']) $messages[] = '<div class="error-message">Выберите хотя бы один язык.</div>';

    foreach ($fields as $field) {
        if ($field === 'languages') {
            $values['languages'] = !empty($_COOKIE['languages_value']) ? explode(',', $_COOKIE['languages_value']) : [];
        } elseif ($field === 'contract_accepted') {
            $values['contract_accepted'] = !empty($_COOKIE['contract_accepted_value']);
        } else {
            $values[$field] = $_COOKIE[$field . '_value'] ?? '';
        }
    }

    if (!empty($_COOKIE['save'])) {
        setcookie('save', '', 1);
        $messages[] = '<div class="success-message">✅ Данные успешно сохранены!</div>';
    }

    $pdo = getDB();
    $languages_from_db = $pdo->query("SELECT name FROM language ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
    if (empty($languages_from_db)) $languages_from_db = $allowed_languages;

    include 'anketa.php';
    exit();
}

// POST – обработка отправки формы
else {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $biography = trim($_POST['biography'] ?? '');
    $contract_accepted = isset($_POST['contract_accepted']) ? 1 : 0;
    $languages = $_POST['languages'] ?? [];

    $errors = false;

    if (empty($full_name) || !preg_match('/^[а-яА-Яa-zA-Z\s]+$/u', $full_name) || strlen($full_name) > 150) {
        setcookie('full_name_error', '1', 0);
        $errors = true;
    }
    setcookie('full_name_value', $full_name, time() + 31536000);

    if (empty($phone) || !preg_match('/^[\d\s\-\+\(\)]{6,12}$/', $phone)) {
        setcookie('phone_error', '1', 0);
        $errors = true;
    }
    setcookie('phone_value', $phone, time() + 31536000);

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setcookie('email_error', '1', 0);
        $errors = true;
    }
    setcookie('email_value', $email, time() + 31536000);

    if (empty($birth_date)) {
        setcookie('birth_date_error', '1', 0);
        $errors = true;
    } else {
        $date = DateTime::createFromFormat('Y-m-d', $birth_date);
        if (!$date || $date->format('Y-m-d') !== $birth_date || $date > new DateTime('today')) {
            setcookie('birth_date_error', '1', 0);
            $errors = true;
        }
    }
    setcookie('birth_date_value', $birth_date, time() + 31536000);

    if (empty($gender) || !in_array($gender, $allowed_genders)) {
        setcookie('gender_error', '1', 0);
        $errors = true;
    }
    setcookie('gender_value', $gender, time() + 31536000);

    if (strlen($biography) > 10000) {
        setcookie('biography_error', '1', 0);
        $errors = true;
    }
    setcookie('biography_value', $biography, time() + 31536000);

    if (!$contract_accepted) {
        setcookie('contract_accepted_error', '1', 0);
        $errors = true;
    }
    setcookie('contract_accepted_value', $contract_accepted ? '1' : '0', time() + 31536000);

    if (empty($languages)) {
        setcookie('languages_error', '1', 0);
        $errors = true;
    } else {
        foreach ($languages as $lang) {
            if (!in_array($lang, $allowed_languages)) {
                setcookie('languages_error', '1', 0);
                $errors = true;
                break;
            }
        }
    }
    setcookie('languages_value', implode(',', $languages), time() + 31536000);

    if ($errors) {
        header('Location: index.php');
        exit();
    }

    try {
        $pdo = getDB();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO application (full_name, phone, email, birth_date, gender, biography, contract_accepted) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $phone, $email, $birth_date, $gender, $biography, $contract_accepted]);
        $app_id = $pdo->lastInsertId();

        $lang_map = [];
        $stmt = $pdo->query("SELECT id, name FROM language");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) $lang_map[$row['name']] = $row['id'];
        $stmt = $pdo->prepare("INSERT INTO application_language (application_id, language_id) VALUES (?, ?)");
        foreach ($languages as $lang_name) {
            if (isset($lang_map[$lang_name])) $stmt->execute([$app_id, $lang_map[$lang_name]]);
        }

        $pdo->commit();

        $fields = ['full_name', 'phone', 'email', 'birth_date', 'gender', 'biography', 'contract_accepted', 'languages'];
        foreach ($fields as $field) setcookie($field . '_error', '', 1);

        setcookie('save', '1', time() + 10);
        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        setcookie('db_error', '1', time() + 10);
        header('Location: index.php');
        exit();
    }
}
?>