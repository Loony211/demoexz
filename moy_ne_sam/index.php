<?php
$pageTitle = 'Авторизация';
require_once "struktura.php";

?>

    <main>    
       
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
            <?php            
            $password=strip_tags($_GET["password"] ?? "");
            $login=strip_tags($_GET["login"] ?? "");            
            if ($login && $password){                
                echo find($login,$password);
                if (find($login, $password)) {
                    echo "Успешная авторизация: " . $login . ", " . $password;
                } else {
                    echo "Ошибка авторизации: " . $login . ", " . $password . " - error";
                }
            }
            ?>
        </p>
    </main>





