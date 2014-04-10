<? require_once('check_exist.php'); ?>
<?php
session_save_path("./session/");
session_start();
if(isset($_SESSION['isAuth']))
{
  require_once("db.php");
  $uid = $_SESSION['uid'];
  $fid = $_POST['flight_id'];
  $sql = "SELECT * FROM Compare WHERE user_id = ? and flight_id = ?";
  $search_compare = $db->prepare($sql);
  $search_compare->execute(array($uid, $fid));
  if($search_compare->fetchObject())
  {
    echo "already inserted";
  }
  else
  {
    $sql = "INSERT INTO Compare (user_id, flight_id)"
      ."VALUES (?, ?)";
    $add_compare = $db->prepare($sql);
    $add_compare->execute(array($uid, $fid));
    if($_SESSION['isAdmin'])
      header('Location:admin_flight.php');
    else
      header('Location:flight.php');
  }
}
?>
