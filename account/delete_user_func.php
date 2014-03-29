<?
session_save_path("./session/");
session_start();

if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $id = $_POST['id'];
  require_once('db.php');
  $sql = "DELETE FROM User WHERE id = ? ";
  $delete_user = $db->prepare($sql);
  $delete_user->execute(array($id));
  header('Location: account_management.php');
}
?>
