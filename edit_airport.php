<?php require_once("header.php"); ?>
<?php
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $id = $_POST['id'];
  $abbr = $_POST['abbr'];
  $name = $_POST['name'];
  $country = $_POST['country'];
  $longitude = $_POST['longitude'];
  $latitude = $_POST['latitude'];
  $timezone = $_POST['timezone'];
?>
  <table style="width:500px">
    <tr>
      <td> id </td>
      <td> name </td>
      <td> longitude </td>
      <td> latitude </td>
    </tr>
    <tr>
      <form action="edit_airport_func.php" method="post">
      <input type="hidden" name="id" value="<?= $id ?>">
      <td> <input type="text" name="abbr" value="<?= $abbr ?>"> </td>
      <td> <input type="text" name="name" value="<?= $name ?>"> </td>
      <td> <input type="text" name="country" value="<?= $country ?>"> </td>
      <td> <input type="text" name="longitude" value="<?= $longitude ?>"> </td>
      <td> <input type="text" name="latitude" value="<?= $latitude ?>"> </td>
      <td> <input type="text" name="timezone" value="<?= $timezone ?>"> </td>
      <td> <button type="submit">儲存</button> </td>
      <td> <a href="airport_management.php"><button type="button">取消</button></a> </td>
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
