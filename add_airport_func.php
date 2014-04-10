<? require_once('check_exist.php'); ?>
<?
session_save_path("./session/");
session_start();

if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $name = $_POST['name'];
  $longitude = $_POST['longitude'];
  $latitude = $_POST['latitude'];

  if($name === "" || $longitude === "" || $latitude === "")
  {
    echo "insert error";
  }
  else
  {
    require_once('db.php');
    $sql = "SELECT name FROM Airport WHERE name = ? ";
    $sth = $db->prepare($sql);
    $sth->execute(array($name));
    if($airport = $sth->fetchObject())
    {
      echo "repeated airport name";
    }
    else
    {
      $sql = "INSERT INTO Airport (name, longitude, latitude)"
        ."VALUES (?, ?, ?)";
      $sth = $db->prepare($sql);
      $sth->execute(array($name, $longitude, $latitude));

      header('Location: airport_management.php');
    }
  }
}
?>
