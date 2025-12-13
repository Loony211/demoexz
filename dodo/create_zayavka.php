<?php
$pageTitle = "Создание заявки на продукт";
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
    $product_name = mysqli_real_escape_string($db, $_POST['product_name']);
    $expiry_date = mysqli_real_escape_string($db, $_POST['expiry_date']);
    $weight = (float)$_POST['weight'];
    $action_type_id = (int)$_POST['action_type_id'];
    $notes = mysqli_real_escape_string($db, $_POST['notes'] ?? '');
    
    // Валидация
    if (!empty($product_name) && !empty($expiry_date) && $weight > 0 && $action_type_id > 0) {
        $current_date = date('Y-m-d');
        
        $query = "INSERT INTO `product` (`product_name`, `expiry_date`, `weight`, `action_type_id`, `user_id`, `created_date`, `status_id`, `notes`) 
                  VALUES ('$product_name', '$expiry_date', '$weight', '$action_type_id', '{$user['id_user']}', '$current_date', '2', '$notes')";
        
        if (mysqli_query($db, $query)) {
            $success = "Заявка на продукт успешно создана!";
            // Очистка полей после успешного создания
            $_POST = [];
        } else {
            $error = "Ошибка при создании заявки: " . mysqli_error($db);
        }
    } else {
        $error = "Все обязательные поля должны быть заполнены корректно!";
    }
}

// Получим типы действий
$action_types = [];
$action_type_query = mysqli_query($db, "SELECT * FROM action_type");
if ($action_type_query) {
    while ($row = mysqli_fetch_assoc($action_type_query)) {
        $action_types[$row['id_action_type']] = $row;
    }
}

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
        <label for="product_name">Наименование продукта:</label>
        <input type="text" id="product_name" name="product_name" required 
               value="<?php echo isset($_POST['product_name']) ? htmlspecialchars($_POST['product_name']) : ''; ?>"
               placeholder="Например: Пицца Маргарита, сыр моцарелла">
    </div>
    
    <div>
        <label for="expiry_date">Срок годности:</label>
        <input type="date" id="expiry_date" name="expiry_date" required 
               value="<?php echo isset($_POST['expiry_date']) ? htmlspecialchars($_POST['expiry_date']) : ''; ?>"
               min="<?php echo date('Y-m-d'); ?>">
    </div>
    
    <div>
        <label for="weight">Вес (кг):</label>
        <input type="number" id="weight" name="weight" step="0.01" min="0.01" required 
               value="<?php echo isset($_POST['weight']) ? htmlspecialchars($_POST['weight']) : ''; ?>"
               placeholder="0.50">
    </div>
    
    <div>
        <label for="action_type_id">Действие с продуктом:</label>
        <select id="action_type_id" name="action_type_id" required>
            <option value="">-- Выберите действие --</option>
            <?php foreach ($action_types as $id => $type): ?>
                <option value="<?php echo $id; ?>" 
                    <?php echo (isset($_POST['action_type_id']) && $_POST['action_type_id'] == $id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($type['name_action']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div>
        <label for="notes">Примечания (необязательно):</label>
        <textarea id="notes" name="notes" rows="3" 
                  placeholder="Дополнительная информация о продукте"><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
    </div>
    
    <button type="submit">Создать заявку</button>
</form>
<p><a href="zayavka.php">Вернуться к списку заявок</a></p>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>