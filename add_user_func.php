<?
session_save_path("./session/");
session_start();

if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
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

  header('Location: add_user.php');
  }
else{

  if($account === "" || $password === "")
{
  $_SESSION['error'] = TRUE;
  header('Location: account_management.php');
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
    header('Location: account_management.php');
}
else{
$sql = "INSERT INTO User (account, password, is_admin)"
  ."VALUES (?, ?, ?)";
$sth = $db->prepare($sql);
$sth->execute(array($account, $PWD, $is_admin));

header('Location: account_management.php');
}}}
}
?>
