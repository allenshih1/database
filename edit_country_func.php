<?php require_once('check_exist.php'); ?>
<?
session_save_path("./session/");
session_start();

if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $id = $_POST['id'];
  $abbr = $_POST['abbr'];
  $name = $_POST['name'];
  require_once('db.php');
  $sql = "SELECT * FROM Country WHERE id = ? ";
  $sth = $db->prepare($sql);
  $sth->execute(array($id));
  if($airport = $sth->fetchObject())
  {
    $sql = "UPDATE Country SET abbr = ?, name = ?"
      ." WHERE id = ?";
    $sth = $db->prepare($sql);
    $sth->execute(array($abbr, $name, $id));
    header('Location: country_management.php');
  }
  else
  {
    echo "hardly error";
  }
}
?>
