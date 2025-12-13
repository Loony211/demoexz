<?php
$pageTitle = 'Список заявок на продукты';
require_once "db/db.php";

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id_user'];

// Получаем заявки пользователя
$query = "
    SELECT p.id_product, p.product_name, p.expiry_date, p.weight, p.created_date, 
           p.notes, at.name_action, s.name_status
    FROM product p
    JOIN action_type at ON p.action_type_id = at.id_action_type
    JOIN status s ON p.status_id = s.id_status
    WHERE p.user_id = ?
    ORDER BY p.created_date DESC, p.expiry_date ASC
";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$zayavki = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

ob_start();
?>

<h2>Мои заявки на продукты</h2>

<?php if (empty($zayavki)): ?>
    <div class="no-zayavki">
        <p>У вас пока нет заявок на продукты.</p>
        <a href="create_zayavka.php" class="create-link">Создать первую заявку</a>
    </div>
<?php else: ?>
    <div class="cards-container">
        <?php foreach ($zayavki as $z): ?>
            <div class="card">
                <div class="card-header">
                    Заявка #<?= htmlspecialchars($z['id_product']) ?>
                </div>
                <div class="card-field">
                    <strong>Продукт:</strong> <?= htmlspecialchars($z['product_name']) ?>
                </div>
                <div class="card-field">
                    <strong>Срок годности:</strong> <?= htmlspecialchars($z['expiry_date']) ?>
                </div>
                <div class="card-field">
                    <strong>Вес:</strong> <?= htmlspecialchars($z['weight']) ?> кг
                </div>
                <div class="card-field">
                    <strong>Действие:</strong> <?= htmlspecialchars($z['name_action']) ?>
                </div>
                <div class="card-field">
                    <strong>Дата создания:</strong> <?= htmlspecialchars($z['created_date']) ?>
                </div>
                <div class="card-field">
                    <strong>Статус:</strong> 
                    <span style="
                        padding: 3px 6px; 
                        border-radius: 3px;
                        background-color: <?= 
                            $z['name_status'] == 'Обработано' ? '#d4edda' : 
                            ($z['name_status'] == 'В работе' ? '#fff3cd' : 
                            '#f8d7da') 
                        ?>;
                        color: <?= 
                            $z['name_status'] == 'Обработано' ? '#155724' : 
                            ($z['name_status'] == 'В работе' ? '#856404' : 
                            '#721c24') 
                        ?>;
                    ">
                        <?= htmlspecialchars($z['name_status']) ?>
                    </span>
                </div>
                <?php if (!empty($z['notes'])): ?>
                <div class="card-field">
                    <strong>Примечания:</strong> <?= htmlspecialchars($z['notes']) ?>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <a href="create_zayavka.php" class="create-link">Создать новую заявку</a>
<?php endif; ?>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>