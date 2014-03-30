<?
session_save_path("./session/");
session_start();

if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $id = $_POST['id'];
  $name = $_POST['name'];
  $longitude = $_POST['longitude'];
  $latitude = $_POST['latitude'];
  require_once('db.php');
  $sql = "SELECT * FROM Airport WHERE id = ? ";
  $sth = $db->prepare($sql);
  $sth->execute(array($id));
  if($airport = $sth->fetchObject())
  {
    $sql = "UPDATE Airport SET name = ?,"
      ." longitude = ?,"
      ." latitude = ?"
      ." WHERE id = ?";
    $sth = $db->prepare($sql);
    $sth->execute(array($name, $longitude, $latitude, $id));
    header('Location: airport_management.php');
  }
  else
  {
    echo "hardly error";
  }
}
?>
