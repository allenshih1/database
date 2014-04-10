<?php require_once('check_exist.php'); ?>
<?
  session_save_path("./session/");
  session_start();
  unset($_SESSION['search']);
  $source = $_SESSION['source'];
  header("Location: $source");
?>
