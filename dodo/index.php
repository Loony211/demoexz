<?php
$pageTitle = 'Авторизация';
require_once "db/db.php";

$loginError = '';
$login = '';

// Проверка авторизации и редирект
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if ($user['user_type_id'] == 2) {
        header("Location: admin.php");
        exit();
    } else {
        header("Location: zayavka.php");
        exit();
    }
}

// Обработка формы авторизации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST["login"] ?? "";
    $password = $_POST["password"] ?? "";

    // Очистка входных данных
    $login = strip_tags($login);
    $password = strip_tags($password);

    $user = find($login, $password);

    if ($user) {
        // Успешная авторизация
        $_SESSION['user'] = $user;

        // Редирект в зависимости от типа пользователя
        if ($user['user_type_id'] == 2) {
            header("Location: admin.php");
            exit();
        } else {
            header("Location: zayavka.php");
            exit();
        }
    } else {
        // Ошибка авторизации
        $loginError = "Неверный логин или пароль.";
    }
}

// Формируем контент страницы
ob_start();
?>

<form method="post" action="index.php">
    <div>
        <label for="login">Логин</label>
        <input type="text" name="login" id="login" required value="<?php echo htmlspecialchars($login); ?>" autocomplete="username">
    </div>
    
    <div>
        <label for="password">Пароль</label>
        <input type="password" name="password" id="password" required autocomplete="current-password">
    </div>
    
    <button type="submit">Вход</button>
</form>

<?php if (!empty($loginError)): ?>
    <p class="error"><?php echo htmlspecialchars($loginError); ?></p>
<?php endif; ?>

<p>Нет аккаунта? <a href="registration.php">Зарегистрируйтесь здесь</a></p>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>