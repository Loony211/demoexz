<?php
$pageTitle = 'Авторизация';
<<<<<<< HEAD
require_once "db/db.php";

$loginError = '';
$login = '';

// Проверка авторизации и редирект
=======
require_once "struktura.php";
$loginError = '';

// Check if the user is already logged in and redirect them if necessary
>>>>>>> 6b7f57f35a2cad7bff364e68e715a2b13b175b73
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

<<<<<<< HEAD
// Обработка формы авторизации
=======
>>>>>>> 6b7f57f35a2cad7bff364e68e715a2b13b175b73
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST["login"] ?? "";
    $password = $_POST["password"] ?? "";

<<<<<<< HEAD
    // Очистка входных данных
    $login = strip_tags($login);
    $password = strip_tags($password);

    $user = find($login, $password);

    if ($user) {
        // Успешная авторизация
        $_SESSION['user'] = $user;

        // Редирект в зависимости от типа пользователя
=======
    // Sanitize input before passing to find function (though find() also sanitizes)
    $login = strip_tags($login);
    $password = strip_tags($password);

    $user = find($login, $password); // This now returns user data or false

    if ($user) {
        // Successful login
        $_SESSION['user'] = $user; // Store the entire user data array in session

        // Redirect based on user type
>>>>>>> 6b7f57f35a2cad7bff364e68e715a2b13b175b73
        if ($user['user_type_id'] == 2) {
            header("Location: admin.php");
            exit();
        } else {
            header("Location: zayavka.php");
            exit();
        }
    } else {
<<<<<<< HEAD
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
=======
        // Failed login
        $loginError = "Неверный логин или пароль.";
    }
}
?>

    <main>    
       <!--
        <form>
        <label>Логин
            <input type="text" name="login"> 
        </label> 
        <label>Пароль
            <input type="text" name="password"> 
        </label> 
        <button>Вход</button>
        </form> 
        <p class="Error">
            <?php    /*        
            $password=strip_tags($_GET["password"] ?? "");
            $login=strip_tags($_GET["login"] ?? "");            
            if ($login && $password){                
                echo find($login,$password);
                if (find($login, $password)) {
                    echo "Успешная авторизация: " . $login . ", " . $password;
                } else {
                    echo "Ошибка авторизации: " . $login . ", " . $password . " - error";
                }
            }*/
            ?>
        </p>-->
        <form method="post" action="index.php">
        <label>Логин
        <input type="text" name="login" required value="<?php echo htmlspecialchars($login ?? ''); ?>" autocomplete="username">
       
        </label>
        <label>Пароль
        <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button type="submit">Вход</button>
    </form>

    <?php if (!empty($loginError)): ?>
        <p class="Error"><?php echo htmlspecialchars($loginError); ?></p>
    <?php endif; ?>
    </main>





>>>>>>> 6b7f57f35a2cad7bff364e68e715a2b13b175b73
