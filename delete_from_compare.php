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
    $sql = "DELETE FROM Compare WHERE user_id = ? and flight_id = ?";
    $delete_compare = $db->prepare($sql);
    $delete_compare->execute(array($uid, $fid));
    header('Location:comparison.php');
  }
  else
  {
    echo "not in the sheet";
  }
}
?>
