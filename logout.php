<?
  session_save_path("./session/");
  session_start();
  unset($_SESSION['isAuth']);
  unset($_SESSION['error']);
  header('Location: login.php');
?>
