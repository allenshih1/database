<?
session_save_path("./session/");
session_start();

if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $account = $_POST['account'];
  $password = $_POST['password'];
  $PWD = hash('sha256', $password);
  if(isset($_POST['is_admin']))
    $is_admin = 1;
  else
    $is_admin = 0;

  if(preg_match("/ /", $account) || preg_match("/ /", $password) || $account === "" || $password === "")
  {
    echo "insert error";
  }
  else
  {
    require_once('db.php');
    $sql = "SELECT account FROM User WHERE account = ? ";
    $sth = $db->prepare($sql);
    $sth->execute(array($account));
    if($user = $sth->fetchObject())
    {
      echo "repeated account name";
    }
    else
    {
      $sql = "INSERT INTO User (account, password, is_admin)"
        ."VALUES (?, ?, ?)";
      $sth = $db->prepare($sql);
      $sth->execute(array($account, $PWD, $is_admin));

      header('Location: account_management.php');
    }
  }
}
?>
