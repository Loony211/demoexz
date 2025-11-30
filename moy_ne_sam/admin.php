<?php
$pageTitle = "Панель администратора";
require_once "db/db.php";

// Проверка авторизации и прав администратора
if (!isset($_SESSION['user']) || $_SESSION['user']['user_type_id'] != 2) {
    header("Location: index.php");
    exit();
}

$message = "";

// Обработка изменения статуса заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_status'])) {
    $service_id = (int)$_POST['service_id'];
    $new_status = (int)$_POST['status_id'];
    
    $update_query = "UPDATE service SET status_id = '$new_status' WHERE id_service = '$service_id'";
    if (mysqli_query($db, $update_query)) {
        $message = "Статус заявки успешно изменен!";
    } else {
        $message = "Ошибка при изменении статуса: " . mysqli_error($db);
    }
}

// Получаем все заявки
$services_query = "SELECT s.*, u.surname, u.name, u.otchestvo, st.name_service_type, p.name_pay, ss.name_status 
                   FROM service s 
                   LEFT JOIN user u ON s.user_id = u.id_user 
                   LEFT JOIN service_type st ON s.service_type_id = st.id_service_type 
                   LEFT JOIN pay_type p ON s.pay_type_id = p.id_pay_type 
                   LEFT JOIN status ss ON s.status_id = ss.id_status 
                   ORDER BY s.data DESC, s.time DESC";
$services_result = mysqli_query($db, $services_query);

// Получаем все статусы
$statuses_query = mysqli_query($db, "SELECT * FROM status");
$statuses = [];
while ($row = mysqli_fetch_assoc($statuses_query)) {
    $statuses[$row['id_status']] = $row;
}

// Формируем контент страницы
ob_start();
?>

<?php if ($message): ?>
    <div class="<?php echo strpos($message, 'успешно') !== false ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<h2>Все заявки</h2>

<?php if ($services_result && mysqli_num_rows($services_result) > 0): ?>
    <div class="cards-container">
        <?php while ($service = mysqli_fetch_assoc($services_result)): ?>
            <div class="card">
                <div class="card-header">
                    Заявка #<?= $service['id_service'] ?>
                    <span style="
                        padding: 4px 8px; 
                        border-radius: 4px; 
                        font-size: 12px; 
                        font-weight: normal;
                        background-color: <?= 
                            $service['status_id'] == 1 ? '#e3f2fd' : 
                            ($service['status_id'] == 2 ? '#fff3e0' : 
                            ($service['status_id'] == 3 ? '#e8f5e8' : '#ffebee')) 
                        ?>;
                        color: <?= 
                            $service['status_id'] == 1 ? '#1976d2' : 
                            ($service['status_id'] == 2 ? '#f57c00' : 
                            ($service['status_id'] == 3 ? '#388e3c' : '#d32f2f')) 
                        ?>;
                    ">
                        <?= htmlspecialchars($service['name_status']) ?>
                    </span>
                </div>
                <div class="card-field">
                    <strong>Клиент:</strong> <?= htmlspecialchars($service['surname'] . ' ' . $service['name'] . ' ' . $service['otchestvo']) ?>
                </div>
                <div class="card-field">
                    <strong>Адрес:</strong> <?= htmlspecialchars($service['address']) ?>
                </div>
                <div class="card-field">
                    <strong>Услуга:</strong> <?= htmlspecialchars($service['name_service_type']) ?>
                </div>
                <div class="card-field">
                    <strong>Дата:</strong> <?= htmlspecialchars($service['data']) ?>
                </div>
                <div class="card-field">
                    <strong>Время:</strong> <?= htmlspecialchars($service['time']) ?>
                </div>
                <div class="card-field">
                    <strong>Тип оплаты:</strong> <?= htmlspecialchars($service['name_pay']) ?>
                </div>
                <div class="card-field">
                    <strong>Контакты:</strong> <?= htmlspecialchars($service['reason_cancel'] ?? 'Не указаны') ?>
                </div>
                <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #eee;">
                    <form method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                        <input type="hidden" name="service_id" value="<?= $service['id_service'] ?>">
                        <select name="status_id" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">Выберите статус</option>
                            <?php foreach ($statuses as $id => $status): ?>
                                <option value="<?= $id ?>" <?= $id == $service['status_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($status['name_status']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="change_status" style="
                            padding: 8px 16px; 
                            background-color: #007bff; 
                            color: white; 
                            border: none; 
                            border-radius: 4px; 
                            cursor: pointer;
                        ">
                            Изменить статус
                        </button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>Заявок нет.</p>
<?php endif; ?>

<p class="text-center mt-20"><a href="zayavka.php" class="create-link">Вернуться к списку заявок</a></p>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>