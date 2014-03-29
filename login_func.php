<?
  session_save_path("./session/");
  session_start();
  $account = $_POST['account'];
  $password = $_POST['password'];
  $PWD = hash('sha256', $password);
  require_once('db.php');
  $sql = "SELECT `password`,`is_admin` FROM User "
     ."WHERE account = ? AND password = ? ";
  $login = $db->prepare($sql);
  $login->execute(array($account,$PWD));
  if($user = $login->fetchObject())
  {
    $_SESSION[isAuth] = "TRUE";
    $is_admin = $user->is_admin;
    $_SESSION['isAdmin'] = $is_admin;
  }
  if(isset($_SESSION['isAuth']))
  {
    if($is_admin)
      header('Location: admin_flight.php');
    else
      header('Location: flight.php');
  }
  else
  {
    $_SESSION['error']="TRUE";
    header('Location:login.php');
  }

?>
