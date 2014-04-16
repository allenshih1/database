<?
  session_save_path("./session/");
  session_start();
  unset($_SESSION['isAuth']);
  unset($_SESSION['error']);
  unset($_SESSION['search']);
  header('Location: login.php');
?>
