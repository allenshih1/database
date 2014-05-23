<?php require_once("header.php"); ?>
<?php
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
?>
  <table style="width:800">
    <tr>
      <td> id </td>
      <td> name </td>
    </tr>
    <tr>
      <form action="add_country_func.php" method="post">
      <td> <input type="text" name="abbr"> </td>
      <td> <input type="text" name="name"> </td>
      <td> <button type="submit">新增</button> </td>
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
