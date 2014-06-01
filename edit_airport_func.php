<?php require_once('check_exist.php'); ?>
<?
session_save_path("./session/");
session_start();

if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $id = $_POST['id'];
  $name = $_POST['name'];
  $country = $_POST['country'];
  $longitude = $_POST['longitude'];
  $latitude = $_POST['latitude'];
  $timezone = $_POST['timezone'];
  require_once('db.php');
  $sql = "SELECT * FROM Airport WHERE id = ? ";
  $sth = $db->prepare($sql);
  $sth->execute(array($id));
  if($airport = $sth->fetchObject())
  {
    $sql = "UPDATE Airport SET name = ?,"
      ." country = ?,"
      ." longitude = ?,"
      ." latitude = ?,"
      ." timezone = ?"
      ." WHERE id = ?";
    $sth = $db->prepare($sql);
    $sth->execute(array($name, $country, $longitude, $latitude, $timezone, $id));
    header('Location: airport_management.php');
  }
  else
  {
    echo "hardly error";
  }
}
?>
