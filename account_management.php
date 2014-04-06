<?php require_once("header.php"); ?>
<?php
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  require_once("db.php");
  $sql = "SELECT * FROM User";
  $users = $db->prepare($sql);
  $users->execute();
?>
  <table style="width:500px">
    <tr>
      <td> account </td>
      <td> identity </td>
    </tr>
<?php
  while($user = $users->fetchObject())
  {
?>
  <tr>
    <td> <?= $user->account ?> </td>
    <td> <?= ($user->is_admin ? "admin" : "user") ?> </td>
    <form action="edit_user_func.php" method="post">
    <input type="hidden" name="id" value="<?= $user->id ?>">
    <td><?php if(!$user->is_admin) { ?> <button type="submit"> 更改權限 </button><?php } ?></td>
    </form>
    <form action="delete_user_func.php" method="post">
    <input type="hidden" name="id" value="<?= $user->id ?>">
    <td> <button type="submit"> 刪除 </button></td>
    </form>
  </tr>
<?php } ?>
  </table>
<a href="add_user.php"> <button type="button">新增</button></a>
<?php
} else if(isset($_SESSION['isAuth'])){
?>
permission denied
<?php
} else {
?>
please login first
<?php
}
?>
<?php require_once("footer.php"); ?>
