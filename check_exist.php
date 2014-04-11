<?php
session_save_path('./session/');
session_start();
require_once('db.php');
if(isset($_SESSION['isAuth']))
{
  $sql = "SELECT * FROM User WHERE id = ?";
  $check = $db->prepare($sql);
  $check->execute(array($_SESSION['uid']));
  if(!($check->fetchObject()))
  {
    unset($_SESSION['isAuth']);
    header('Location:login.php');
  }
}
?>
