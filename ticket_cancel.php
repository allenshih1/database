<?
  session_save_path("./session/");
  session_start();
  unset($_SESSION['departure']);
  unset($_SESSION['destination']);
  unset($_SESSION['max_transfer']);
  $source = $_SESSION['source'];
  header("Location: $source");
?>
