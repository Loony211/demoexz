<?php
$pageTitle = 'Список заявок';
<<<<<<< HEAD
require_once "db/db.php";

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id_user'];

// Получаем заявки пользователя
$query = "
    SELECT s.id_service, s.address, s.data, s.time, st.name_service_type, pt.name_pay, stat.name_status
    FROM service s
    JOIN service_type st ON s.service_type_id = st.id_service_type
    JOIN pay_type pt ON s.pay_type_id = pt.id_pay_type
    JOIN status stat ON s.status_id = stat.id_status
    WHERE s.user_id = ?
    ORDER BY s.data DESC, s.time DESC
";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$zayavki = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Формируем контент страницы
ob_start();
?>

<?php if (empty($zayavki)): ?>
    <div class="no-zayavki">
        <p>У вас пока нет заявок.</p>
        <a href="create_zayavka.php" class="create-link">Создать первую заявку</a>
    </div>
<?php else: ?> 

    
   <div class="cards-container">
        <?php foreach ($zayavki as $z): ?>
            <div class="card">
                <div class="card-header">
                    Заявка #<?= htmlspecialchars($z['id_service']) ?>
                </div>
                <div class="card-field">
                    <strong>Адрес:</strong> <?= htmlspecialchars($z['address']) ?>
                </div>
                <div class="card-field">
                    <strong>Услуга:</strong> <?= htmlspecialchars($z['name_service_type']) ?>
                </div>
                <div class="card-field">
                    <strong>Дата:</strong> <?= htmlspecialchars($z['data']) ?>
                </div>
                <div class="card-field">
                    <strong>Время:</strong> <?= htmlspecialchars($z['time']) ?>
                </div>
                <div class="card-field">
                    <strong>Оплата:</strong> <?= htmlspecialchars($z['name_pay']) ?>
                </div>
                <div class="card-field">
                    <strong>Статус:</strong> <?= htmlspecialchars($z['name_status']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <a href="create_zayavka.php" class="create-link">Создать новую заявку</a>
<?php endif; ?>

<?php
$pageContent = ob_get_clean();
require_once "struktura.php";
?>
=======
require_once "struktura.php";
?>
    <main>    
    </main>
>>>>>>> 6b7f57f35a2cad7bff364e68e715a2b13b175b73
