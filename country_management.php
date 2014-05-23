<?php require_once("header.php"); ?>
<h1>國家管理</h1>
<?php
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  require_once("db.php");
  $sql = "SELECT * FROM Country";
  $airports = $db->prepare($sql);
  $airports->execute();
?>
  <table style="width:800px">
    <tr>
      <td> abbreviation </td>
      <td> name </td>
    </tr>
<?php
  while($airport = $airports->fetchObject())
  {
?>
  <tr>
    <td> <?= $airport->abbr ?> </td>
    <td> <?= $airport->name ?> </td>
    <form action="edit_country.php" method="post">
    <input type="hidden" name="id" value="<?= $airport->id ?>">
    <input type="hidden" name="abbr" value="<?= $airport->abbr ?>">
    <input type="hidden" name="name" value="<?= $airport->name ?>">
    <td> <button type="submit"> 編輯 </button></td>
    </form>
    <form action="delete_country_func.php" method="post">
    <input type="hidden" name="id" value="<?= $airport->id ?>">
    <td> <button type="submit"> 刪除 </button></td>
    </form>
  </tr>
<?php } ?>
  </table>
<a href="add_country.php"> <button type="button">新增</button></a>
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
