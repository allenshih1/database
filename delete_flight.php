<?php
session_save_path("./session/");
session_start();
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  require_once("db.php");
  $sql = "DELETE FROM Flight WHERE id = ?";
  $delete_flight = $db->prepare($sql);
  $delete_flight->execute(array($_POST['delete']));
  header('Location:admin_flight.php');
}
?>
