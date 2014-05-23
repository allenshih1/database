<?php require_once("header.php"); ?>
<?php
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $id = $_POST['id'];
  $abbr = $_POST['abbr'];
  $name = $_POST['name'];
  $longitude = $_POST['longitude'];
  $latitude = $_POST['latitude'];
?>
  <table style="width:500px">
    <tr>
      <td> abbr </td>
      <td> name </td>
    </tr>
    <tr>
      <form action="edit_country_func.php" method="post">
      <input type="hidden" name="id" value="<?= $id ?>">
      <td> <input type="text" name="abbr" value="<?= $abbr ?>"> </td>
      <td> <input type="text" name="name" value="<?= $name ?>"> </td>
      <td> <button type="submit">儲存</button> </td>
      <td> <a href="country_management.php"><button type="button">取消</button></a> </td>
      </form>
    </tr>
  </table>
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
