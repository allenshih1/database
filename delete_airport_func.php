<?
session_save_path("./session/");
session_start();

if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $id = $_POST['id'];
  require_once('db.php');
  $sql = "DELETE FROM Airport WHERE id = ? ";
  $delete_airport = $db->prepare($sql);
  $delete_airport->execute(array($id));
  header('Location: airport_management.php');
}
?>
