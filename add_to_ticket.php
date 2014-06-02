<? require_once('check_exist.php'); ?>
<?php
session_save_path("./session/");
session_start();
ob_start();
if(isset($_SESSION['isAuth']))
{
  require_once("db.php");
  $uid = $_SESSION['uid'];
  $type = $_POST['type'];
  $f_id = $_POST['f_id'];
  $s_id = $_POST['s_id'];
  $t_id = $_POST['t_id'];
  if($type === '0')
  {
    $sql = "SELECT * FROM Ticket WHERE user_id = ? and f_id = ? and s_id is null and t_id is null";
    $search_compare = $db->prepare($sql);
    $search_compare->execute(array($uid, $f_id));
  }
  if($type === '1')
  {
    $sql = "SELECT * FROM Ticket WHERE user_id = ? and f_id = ? and s_id = ? and t_id is null";
    $search_compare = $db->prepare($sql);
    $search_compare->execute(array($uid, $f_id, $s_id));
  }
  if($type === '2')
  {
    $sql = "SELECT * FROM Ticket WHERE user_id = ? and f_id = ? and s_id = ? and t_id = ?";
    $search_compare = $db->prepare($sql);
    $search_compare->execute(array($uid, $f_id, $s_id, $t_id));
  }

  if($search_compare->fetchObject())
  {
    echo "already inserted";
  }
  else
  {
    if($type === '0')
    {
      $sql = "INSERT INTO Ticket (user_id, f_id)"
        ."VALUES (?, ?)";
      $add_compare = $db->prepare($sql);
      $add_compare->execute(array($uid, $f_id));
    }
    if($type === '1')
    {
      $sql = "INSERT INTO Ticket (user_id, f_id, s_id)"
        ."VALUES (?, ?, ?)";
      $add_compare = $db->prepare($sql);
      $add_compare->execute(array($uid, $f_id, $s_id));
    }
    if($type === '2')
    {
      $sql = "INSERT INTO Ticket (user_id, f_id, s_id, t_id)"
        ."VALUES (?, ?, ?, ?)";
      $add_compare = $db->prepare($sql);
      $add_compare->execute(array($uid, $f_id, $s_id, $t_id));
    }
    header('Location:search_ticket.php');
  }
}
?>
