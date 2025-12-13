<?php
$pageTitle = "Панель администратора - Пиццерия Додо";
require_once "db/db.php";

// Проверка авторизации и прав администратора
if (!isset($_SESSION['user']) || $_SESSION['user']['user_type_id'] != 2) {
    header("Location: index.php");
    exit();
}

$message = "";

// Обработка изменения статуса заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_status'])) {
    $product_id = (int)$_POST['product_id'];
    $new_status = (int)$_POST['status_id'];
    
    $update_query = "UPDATE product SET status_id = '$new_status' WHERE id_product = '$product_id'";
    if (mysqli_query($db, $update_query)) {
        $message = "Статус заявки успешно изменен!";
    } else {
        $message = "Ошибка при изменении статуса: " . mysqli_error($db);
    }
}

// Получаем все заявки на продукты
$products_query = "SELECT p.*, u.surname, u.name, u.otchestvo, 
                          at.name_action, s.name_status 
                   FROM product p 
                   LEFT JOIN user u ON p.user_id = u.id_user 
                   LEFT JOIN action_type at ON p.action_type_id = at.id_action_type 
                   LEFT JOIN status s ON p.status_id = s.id_status 
                   ORDER BY p.created_date DESC, p.expiry_date ASC";
$products_result = mysqli_query($db, $products_query);

// Получаем все статусы
$statuses_query = mysqli_query($db, "SELECT * FROM status");
$statuses = [];
while ($row = mysqli_fetch_assoc($statuses_query)) {
    $statuses[$row['id_status']] = $row;
}

ob_start();
?>

<?php if ($message): ?>
    <div class="<?php echo strpos($message, 'успешно') !== false ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<h2>Управление заявками на продукты</h2>

<?php if ($products_result && mysqli_num_rows($products_result) > 0): ?>
    <div class="cards-container">
        <?php while ($product = mysqli_fetch_assoc($products_result)): ?>
            <div class="card">
                <div class="card-header">
                    Заявка #<?= $product['id_product'] ?>
                    <span style="
                        padding: 4px 8px; 
                        border-radius: 4px; 
                        font-size: 12px; 
                        font-weight: normal;
                        background-color: <?= 
                            $product['status_id'] == 1 ? '#d4edda' : 
                            ($product['status_id'] == 2 ? '#fff3cd' : 
                            '#f8d7da') 
                        ?>;
                        color: <?= 
                            $product['status_id'] == 1 ? '#155724' : 
                            ($product['status_id'] == 2 ? '#856404' : 
                            '#721c24') 
                        ?>;
                    ">
                        <?= htmlspecialchars($product['name_status']) ?>
                    </span>
                </div>
                <div class="card-field">
                    <strong>Сотрудник:</strong> <?= htmlspecialchars($product['surname'] . ' ' . $product['name'] . ' ' . $product['otchestvo']) ?>
                </div>
                <div class="card-field">
                    <strong>Продукт:</strong> <?= htmlspecialchars($product['product_name']) ?>
                </div>
                <div class="card-field">
                    <strong>Срок годности:</strong> 
                    <span style="color: <?= strtotime($product['expiry_date']) < strtotime('+3 days') ? '#dc3545' : '#28a745'; ?>">
                        <?= htmlspecialchars($product['expiry_date']) ?>
                    </span>
                </div>
                <div class="card-field">
                    <strong>Вес:</strong> <?= htmlspecialchars($product['weight']) ?> кг
                </div>
                <div class="card-field">
                    <strong>Действие:</strong> <?= htmlspecialchars($product['name_action']) ?>
                </div>
                <div class="card-field">
                    <strong>Дата создания:</strong> <?= htmlspecialchars($product['created_date']) ?>
                </div>
                <?php if (!empty($product['notes'])): ?>
                <div class="card-field">
                    <strong>Примечания:</strong> <?= htmlspecialchars($product['notes']) ?>
                </div>
                <?php endif; ?>
                <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #eee;">
                    <form method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                        <input type="hidden" name="product_id" value="<?= $product['id_product'] ?>">
                        <select name="status_id" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">Выберите статус</option>
                            <?php foreach ($statuses as $id => $status): ?>
                                <option value="<?= $id ?>" <?= $id == $product['status_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($status['name_status']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="change_status" style="
                            padding: 8px 16px; 
                            background-color: #28a745; 
                            color: white; 
                            border: none; 
                            border-radius: 4px; 
                            cursor: pointer;
                        ">
                            Обновить статус
                        </button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>Заявок на продукты нет.</p>
<?php endif; ?>

<div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
    <h3>Статистика:</h3>
    <?php
    // Статистика
    $stats_query = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status_id = 1 THEN 1 ELSE 0 END) as processed,
        SUM(CASE WHEN status_id = 2 THEN 1 ELSE 0 END) as in_work,
        SUM(CASE WHEN status_id = 3 THEN 1 ELSE 0 END) as cancelled,
        SUM(CASE WHEN action_type_id = 1 THEN 1 ELSE 0 END) as thawing,
        SUM(CASE WHEN action_type_id = 2 THEN 1 ELSE 0 END) as writing_off
    FROM product";
    
    $stats_result = mysqli_query($db, $stats_query);
    $stats = mysqli_fetch_assoc($stats_result);
    ?>
    <p>Всего заявок: <strong><?= $stats['total'] ?></strong></p>
    <p>Обработано: <strong><?= $stats['processed'] ?></strong></p>
    <p>В работе: <strong><?= $stats['in_work'] ?></strong></p>
    <p>На разморозку: <strong><?= $stats['thawing'] ?></strong></p>
    <p>На списание: <strong><?= $stats['writing_off'] ?></strong></p>
</div>

<p class="text-center mt-20"><a href="zayavka.php" class="create-link">Вернуться к списку заявок</a></p>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>