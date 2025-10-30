<?php
require_once "db/db.php"; 
$navLinks = [];
$showAuthLinks = true;

// Check if user is logged in via session
if (isset($_SESSION['user'])) {
    $showAuthLinks = false; // Hide auth links if user is logged in
    $user = $_SESSION['user']; // User data is already in the session

    // Check user type from the session data
    $userTypeId = $user['user_type_id'] ?? null;
    
    if ($userTypeId == 2) { // Administrator
        // Admin specific links
        $navLinks = [
            ['href' => 'admin.php', 'text' => 'Панель администратора'],
        ];
    } else { // Regular User
        $navLinks = [
            ['href' => 'zayavka.php', 'text' => 'Список заявок'],
            ['href' => 'create_zayavka.php', 'text' => 'Создать заявку'],
        ];
    }
    // Add logout button for all logged-in users
    $navLinks[] = ['href' => 'logout.php', 'text' => 'Выход'];
} else {
    // Links visible before authentication
    $navLinks = [
        ['href' => 'index.php', 'text' => 'Авторизация'],
        ['href' => 'registration.php', 'text' => 'Регистрация'],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>нарушениям нет  <?php echo $pageTitle; ?></title>
    <link rel='icon' href='images/logo.jpeg'>
    <link rel='stylesheet' href='css/style.css'>
</head>
<body>
    <header>
        <img src='images/logo.jpeg' alt='логотип'>
        <h1>нарушениям нет</h1>
    </header>
<!--
    <nav>
        <a href="index.php">Авторизация</a>
        <a href="registration.php">Регистрация</a>
        <a href="create_zayavka.php">Создать заявку</a>
        <a href="zayavka.php">Список заявок</a>
        <a href="admin.php">Панель администратора</a>
    </nav>
-->

    <nav>
        <?php foreach ($navLinks as $link): ?>
            <a href="<?php echo htmlspecialchars($link['href']); ?>"><?php echo htmlspecialchars($link['text']); ?></a>
        <?php endforeach; ?>
    </nav>

    <main>
        <h1><?php echo $pageTitle;?></h1>
        <div class="content">
            <?php echo $pageContent ?? '';?>
        </div>
        <footer>
            <h3>2025</h3>
        </footer>
    </main>


    <script src="js/script.js"></script>
</body>
</html>