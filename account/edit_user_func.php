<?
session_save_path("./session/");
session_start();

if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $id = $_POST['id'];
  require_once('db.php');
  $sql = "SELECT * FROM User WHERE id = ? ";
  $sth = $db->prepare($sql);
  $sth->execute(array($id));
  if($user = $sth->fetchObject())
  {
    $sql = "UPDATE User SET is_admin = ? WHERE id = ?";
    $sth = $db->prepare($sql);
    $sth->execute(array(!$user->is_admin,$id));
    header('Location: account_management.php');
  }
  else
  {
    echo "hardly error";
  }
}
?>
