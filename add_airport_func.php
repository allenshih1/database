<? require_once('check_exist.php'); ?>
<?
session_save_path("./session/");
session_start();

if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $abbr = $_POST['abbr'];
  $name = $_POST['name'];
  $country = $_POST['country'];
  $longitude = $_POST['longitude'];
  $latitude = $_POST['latitude'];
  $timezone = $_POST['timezone'];

  if($id === "" || $name === "" || $longitude === "" || $latitude === "")
  {
    echo "insert error";
  }
  else
  {
    require_once('db.php');
    $sql = "SELECT name FROM Airport WHERE abbr = ? ";
    $sth = $db->prepare($sql);
    $sth->execute(array($abbr));
    if($airport = $sth->fetchObject())
    {
      echo "repeated abbr";
    }
    else
    {
      $sql = "INSERT INTO Airport (abbr, name, country, longitude, latitude, timezone)"
        ."VALUES (?, ?, ?, ?, ?, ?)";
      $sth = $db->prepare($sql);
      $sth->execute(array($abbr, $name, $country, $longitude, $latitude, $timezone));

      header('Location: airport_management.php');
    }
  }
}
?>
