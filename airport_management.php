<?php require_once("header.php"); ?>
<h1>機場管理</h1>
<?php
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  require_once("db.php");
  $sql = "SELECT * FROM Airport";
  $airports = $db->prepare($sql);
  $airports->execute();
?>
  <table style="width:800px">
    <tr>
      <td> name </td>
      <td> longitude </td>
      <td> latitude </td>
    </tr>
<?php
  while($airport = $airports->fetchObject())
  {
?>
  <tr>
    <td> <?= $airport->name ?> </td>
    <td> <?= $airport->longitude ?> </td>
    <td> <?= $airport->latitude ?> </td>
    <form action="edit_airport.php" method="post">
    <input type="hidden" name="id" value="<?= $airport->id ?>">
    <input type="hidden" name="name" value="<?= $airport->name ?>">
    <input type="hidden" name="longitude" value="<?= $airport->longitude ?>">
    <input type="hidden" name="latitude" value="<?= $airport->latitude ?>">
    <td> <button type="submit"> 編輯 </button></td>
    </form>
    <form action="delete_airport_func.php" method="post">
    <input type="hidden" name="id" value="<?= $airport->id ?>">
    <td> <button type="submit"> 刪除 </button></td>
    </form>
  </tr>
<?php } ?>
  </table>
<a href="add_airport.php"> <button type="button">新增</button></a>
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
