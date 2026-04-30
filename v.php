<?php
// Настройки подключения к БД
$db_user = 'u82325';
$db_pass = '2941524';   
$db_name = 'u82325';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("
        SELECT a.*, GROUP_CONCAT(l.name SEPARATOR ', ') AS languages
        FROM application a
        LEFT JOIN application_language al ON a.id = al.application_id
        LEFT JOIN language l ON al.language_id = l.id
        GROUP BY a.id
        ORDER BY a.id DESC
    ");
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("View error: " . $e->getMessage());
    die("Внутренняя ошибка. Попробуйте позже.");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сохранённые анкеты</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            padding: 20px;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            background: white;
            border-radius: 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            overflow: auto;
            padding: 30px;
        }
        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 16px;
            overflow-x: auto;
            display: block;
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 12px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #f0f0f0;
            color: #333;
        }
        tr:hover td {
            background: #fafafa;
        }
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        .back-link a {
            background: #b87333;
            color: white;
            padding: 10px 25px;
            border-radius: 40px;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
        }
        .back-link a:hover {
            background: #9e5e28;
        }
        @media (max-width: 768px) {
            .container { padding: 15px; }
            th, td { padding: 8px; font-size: 0.85rem; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Сохранённые анкеты</h1>
    <p>Всего записей: <?= count($applications) ?></p>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>Email</th>
                    <th>Дата рождения</th>
                    <th>Пол</th>
                    <th>Языки</th>
                    <th>Биография</th>
                    <th>Согласие</th>
                    <th>Дата создания</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                <tr>
                    <td><?= htmlspecialchars($app['id']) ?></td>
                    <td><?= htmlspecialchars($app['full_name']) ?></td>
                    <td><?= htmlspecialchars($app['phone']) ?></td>
                    <td><?= htmlspecialchars($app['email']) ?></td>
                    <td><?= htmlspecialchars($app['birth_date']) ?></td>
                    <td><?= $app['gender'] === 'male' ? 'Мужской' : 'Женский' ?></td>
                    <td><?= htmlspecialchars($app['languages'] ?? '—') ?></td>
                    <td><?= nl2br(htmlspecialchars($app['biography'])) ?></td>
                    <td><?= $app['contract_accepted'] ? '✅ Да' : '❌ Нет' ?></td>
                    <td><?= htmlspecialchars($app['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="back-link">
        <a href="index.php">← Вернуться к форме</a>
        
    </div>
</div>
</body>
</html>