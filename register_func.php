<?
session_save_path("./session/");
session_start();

$account = $_POST['account'];
$password = $_POST['password'];
$PWD = hash('sha256', $password);
if(isset($_POST['is_admin']))
{
  $is_admin = 1;
}
else
{
  $is_admin = 0;
}
if(preg_match("/ /", $account) || preg_match("/ /", $password)){
  $_SESSION['error'] = TRUE;
  header('Location: register.php');
  }
else{

  if($account === "" || $password === "")
{
  $_SESSION['error'] = TRUE;
  header('Location: register.php');
}
else
{
require_once('db.php');
$sql = "SELECT account FROM User WHERE account = ? ";
$sth = $db->prepare($sql);
$sth->execute(array($account));
if($user = $sth->fetchObject())
{
    $_SESSION['repeat'] = TRUE;
    header('Location: register.php');
}
else{
$sql = "INSERT INTO User (account, password, is_admin)"
  ."VALUES (?, ?, ?)";
$sth = $db->prepare($sql);
$sth->execute(array($account, $PWD, $is_admin));

header('Location: login.php');
}}}
?>
