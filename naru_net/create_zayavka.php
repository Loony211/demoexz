<?php
<<<<<<< HEAD
$pageTitle = "Создание заявки";
require_once "db/db.php";

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user']; // Информация о текущем авторизованном пользователе
$error = "";
$success = "";

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и экранируем строковые данные
    $car_number = isset($_POST['car_number']) ? mysqli_real_escape_string($db, $_POST['car_number']) : '';
    $violation_description = isset($_POST['violation_description']) ? mysqli_real_escape_string($db, $_POST['violation_description']) : '';
    $violation_location = isset($_POST['violation_location']) ? mysqli_real_escape_string($db, $_POST['violation_location']) : '';
    $data = isset($_POST['data']) ? mysqli_real_escape_string($db, $_POST['data']) : '';
    $time = isset($_POST['time']) ? mysqli_real_escape_string($db, $_POST['time']) : '';
    
    // Получаем числовые данные, проверяя их наличие и корректность
    // service_type_id и pay_type_id удалены из этой части
    
    // Автоматически подставляем текущего пользователя
    $user_id = $user['id_user']; // Берем ID из сессии

    // Валидация обязательных полей
    // Убираем проверки для service_type_id, pay_type_id, address
    if (!empty($car_number) && !empty($violation_description) && !empty($violation_location) && !empty($data) && !empty($time) && $user_id > 0) {
        
        // Вставляем новую заявку
        // ВНИМАНИЕ: Используйте подготовленные выражения для безопасности!
        
        // Пример с mysqli_query (менее безопасный)
        $query = "INSERT INTO `service` (`car_number`, `violation_description`, `violation_location`, `user_id`, `data`, `time`, `status_id`) 
                  VALUES ('$car_number', '$violation_description', '$violation_location', '{$user['id_user']}', '$data', '$time', '1')"; // status_id = 1 (новый)
        
        // ----- НАСТОЯТЕЛЬНО РЕКОМЕНДУЕТСЯ использовать подготовленные выражения -----
        // Пример с подготовленными выражениями (более безопасный):
        /*
        $insert_query_prepared = "INSERT INTO `service` (`car_number`, `violation_description`, `violation_location`, `user_id`, `data`, `time`, `status_id`) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $insert_query_prepared);
        $default_status_id = 1;
        // Типы: s - string, i - integer. Сопоставьте с порядком полей в VALUES
        mysqli_stmt_bind_param($stmt, "ssssisi", $car_number, $violation_description, $violation_location, $user_id, $data, $time, $default_status_id);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Заявка успешно создана!";
        } else {
            $error = "Ошибка при создании заявки: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
        */
        // ---

        // Для текущего примера, используем mysqli_query (но помните про безопасность)
        if (mysqli_query($db, $query)) {
            $success = "Заявка успешно создана!";
        } else {
            $error = "Ошибка при создании заявки: " . mysqli_error($db);
        }
    } else {
        $error = "Все поля обязательны для заполнения!";
    }
}

// Получим типы услуг (если они все же нужны для какой-то другой цели, но не для формы)
// Если вид услуги больше не нужен, этот блок можно удалить.
/*
$service_types = [];
$service_type_query = mysqli_query($db, "SELECT * FROM service_type");
if ($service_type_query) {
    while ($row = mysqli_fetch_assoc($service_type_query)) {
        $service_types[$row['id_service_type']] = $row;
    }
}
*/

// Получим типы оплаты (если они все же нужны для какой-то другой цели, но не для формы)
// Если тип оплаты больше не нужен, этот блок можно удалить.
/*
$pay_types = [];
$pay_type_query = mysqli_query($db, "SELECT * FROM pay_type");
if ($pay_type_query) {
    while ($row = mysqli_fetch_assoc($pay_type_query)) {
        $pay_types[$row['id_pay_type']] = $row;
    }
}
*/

// Формируем контент страницы
ob_start();
?>

<?php if ($error): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<form method="POST" action="">
    
    <!-- Автоматически подставляемые данные заявителя -->
    <div class="user-info">
        <h3>Данные заявителя:</h3>
        <p><strong>ФИО:</strong> <?php echo htmlspecialchars($user['surname'] ?? '') . ' ' . htmlspecialchars($user['name'] ?? '') . ' ' . htmlspecialchars($user['otchestvo'] ?? ''); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? 'Не указан'); ?></p>
        <p><strong>Телефон:</strong> <?php echo htmlspecialchars($user['phone'] ?? 'Не указан'); ?></p>
        <input type="hidden" name="user_id" value="<?php echo $user['id_user']; ?>"> <!-- Скрытое поле для user_id -->
    </div>
    <hr>

    <div>
        <label for="car_number">Автомобиль (номер):</label>
        <input type="text" id="car_number" name="car_number" required style="width: 300px;" 
               placeholder="Например, А123БВ777" value="<?php echo isset($_POST['car_number']) ? htmlspecialchars($_POST['car_number']) : ''; ?>">
    </div>

    <div>
        <label for="violation_description">Нарушение:</label>
        <textarea id="violation_description" name="violation_description" required style="width: 300px; height: 100px;" 
                  placeholder="Подробное описание нарушения"><?php echo isset($_POST['violation_description']) ? htmlspecialchars($_POST['violation_description']) : ''; ?></textarea>
    </div>

    <div>
        <label for="violation_location">Место (где произошло):</label>
        <input type="text" id="violation_location" name="violation_location" required style="width: 300px;" 
               placeholder="Улица, дом, район..." value="<?php echo isset($_POST['violation_location']) ? htmlspecialchars($_POST['violation_location']) : ''; ?>">
    </div>
    
    <!-- Адрес заявки (куда приехать) убран из формы -->
    
    <div>
        <label for="data">Дата:</label>
        <input type="date" id="data" name="data" required value="<?php echo isset($_POST['data']) ? htmlspecialchars($_POST['data']) : ''; ?>">
    </div>
    
    <div>
        <label for="time">Время:</label>
        <input type="time" id="time" name="time" required value="<?php echo isset($_POST['time']) ? htmlspecialchars($_POST['time']) : ''; ?>">
    </div>
    
    <!-- Поля для выбора услуги и оплаты убраны из формы -->
    
    <button type="submit">Создать заявку</button>
</form>

<p><a href="zayavka.php">Вернуться к списку заявок</a></p>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>
=======
$pageTitle = 'Создать заявку';
require_once "struktura.php";
?>
    <main>    
    </main>
>>>>>>> 6b7f57f35a2cad7bff364e68e715a2b13b175b73
