<?
  session_save_path("./session/");
  session_start();
  unset($_SESSION['search']);
  header('Location: flight.php');
?>
