<?php
<<<<<<< HEAD
$pageTitle = "Создание заявки";
require_once "db/db.php";

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$error = "";
$success = "";

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $contact_data = mysqli_real_escape_string($db, $_POST['contact_data']);
    $data = mysqli_real_escape_string($db, $_POST['data']);
    $time = mysqli_real_escape_string($db, $_POST['time']);
    $service_type_id = (int)$_POST['service_type_id'];
    $pay_type_id = (int)$_POST['pay_type_id'];
    
    // Валидация обязательных полей
    if (!empty($address) && !empty($contact_data) && !empty($data) && !empty($time) && $service_type_id > 0 && $pay_type_id > 0) {
        
        // Вставляем новую заявку
        $query = "INSERT INTO `service` (`address`, `user_id`, `service_type_id`, `data`, `time`, `pay_type_id`, `status_id`, `reason_cancel`) 
                  VALUES ('$address', '{$user['id_user']}', '$service_type_id', '$data', '$time', '$pay_type_id', '1', '$contact_data')";
        
        if (mysqli_query($db, $query)) {
            $success = "Заявка успешно создана!";
        } else {
            $error = "Ошибка при создании заявки: " . mysqli_error($db);
        }
    } else {
        $error = "Все поля обязательны для заполнения!";
    }
}

// Получим типы услуг
$service_types = [];
$service_type_query = mysqli_query($db, "SELECT * FROM service_type");
if ($service_type_query) {
    while ($row = mysqli_fetch_assoc($service_type_query)) {
        $service_types[$row['id_service_type']] = $row;
    }
}

// Получим типы оплаты
$pay_types = [];
$pay_type_query = mysqli_query($db, "SELECT * FROM pay_type");
if ($pay_type_query) {
    while ($row = mysqli_fetch_assoc($pay_type_query)) {
        $pay_types[$row['id_pay_type']] = $row;
    }
}

// Формируем контент страницы
ob_start();
?>

<?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success"><?php echo $success; ?></div>
<?php endif; ?>

<form method="POST" action="">
    <div>
        <label for="address">Адрес:</label>
        <input type="text" id="address" name="address" required style="width: 300px;" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
    </div>
    
    <div>
        <label for="contact_data">Контактные данные:</label>
        <input type="text" id="contact_data" name="contact_data" required style="width: 300px;" 
               placeholder="Телефон или email" value="<?php echo isset($_POST['contact_data']) ? htmlspecialchars($_POST['contact_data']) : ''; ?>">
    </div>
    
    <div>
        <label for="data">Желаемая дата:</label>
        <input type="date" id="data" name="data" required value="<?php echo isset($_POST['data']) ? htmlspecialchars($_POST['data']) : ''; ?>">
    </div>
    
    <div>
        <label for="time">Желаемое время:</label>
        <input type="time" id="time" name="time" required value="<?php echo isset($_POST['time']) ? htmlspecialchars($_POST['time']) : ''; ?>">
    </div>
    
    <div>
        <label for="service_type_id">Вид услуги:</label>
        <select id="service_type_id" name="service_type_id" required>
            <option value="">-- Выберите услугу --</option>
            <?php foreach ($service_types as $id => $type): ?>
                <option value="<?php echo $id; ?>" <?php echo (isset($_POST['service_type_id']) && $_POST['service_type_id'] == $id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($type['name_service_type']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div>
        <label for="pay_type_id">Тип оплаты:</label>
        <select id="pay_type_id" name="pay_type_id" required>
            <option value="">-- Выберите тип оплаты --</option>
            <?php foreach ($pay_types as $id => $type): ?>
                <option value="<?php echo $id; ?>" <?php echo (isset($_POST['pay_type_id']) && $_POST['pay_type_id'] == $id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($type['name_pay']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
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
