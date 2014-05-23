<? require_once('check_exist.php'); ?>
<?
session_save_path("./session/");
session_start();

if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $id = $_POST['id'];
  require_once('db.php');
  $sql = "DELETE FROM Country WHERE id = ? ";
  $delete_airport = $db->prepare($sql);
  $delete_airport->execute(array($id));
  header('Location: country_management.php');
}
?>
