<?php
// Чтобы на разных страницах оставалсь информация о данных пользователя будем использовать сессии (Другой вариант cookie)
session_start();

$db=mysqli_connect("localhost","root","","naru_net1");
if (!$db){
  $error = mysqli_error($db);  // Capture the MySQL error
  echo '<script>console.log("MySQL Connection Error: ' . htmlspecialchars($error) . '");</script>';
  die ("Ошибка подключения к базе данных.  Проверьте консоль разработчика для деталей.");  // More informative message
}/*
function find ($login,$password){
    global $db;
    $result = mysqli_query($db, "SELECT * FROM user WHERE username = '$login'  AND password = MD5('$password');");
    return mysqli_num_rows ($result);
      //return $result;
      //return mysqli_fetch_assoc($result);
      //while ($row=mysqli_fetch_assoc($result)){
        //print_r ($row);
     //}
      
}*/

function find($login, $password) {
  global $db;
  //$login = mysqli_real_escape_string($db, $login);
  //$password = mysqli_real_escape_string($db, $password);

  $result = mysqli_query($db, "SELECT * FROM user WHERE username = '$login' AND password = MD5('$password')");

  if ($result) {
      if (mysqli_num_rows($result) > 0) {
          return mysqli_fetch_assoc($result); // Return the entire user array
      } else {
          return false; // No user found
      }
  } else {
    echo '<script>console.log("MySQL Query Error: ' . htmlspecialchars(mysqli_error($db)) . '");</script>';
    return false;
  }
}
?>