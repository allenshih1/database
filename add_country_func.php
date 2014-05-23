<? require_once('check_exist.php'); ?>
<?
session_save_path("./session/");
session_start();

if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $abbr = $_POST['abbr'];
  $name = $_POST['name'];

  if($abbr === "" || $name === "")
  {
    echo "insert error";
  }
  else
  {
    require_once('db.php');
    $sql = "SELECT name FROM Country WHERE abbr = ? ";
    $sth = $db->prepare($sql);
    $sth->execute(array($id));
    if($airport = $sth->fetchObject())
    {
      echo "repeated abbr";
    }
    else
    {
      $sql = "INSERT INTO Country (abbr, name)"
        ."VALUES (?, ?)";
      $sth = $db->prepare($sql);
      $sth->execute(array($abbr, $name));

      header('Location: country_management.php');
    }
  }
}
?>
