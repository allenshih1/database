<?php require_once("header.php"); ?>
<?php
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
?>
  <table style="width:500px">
    <tr>
      <td> account </td>
      <td> identity </td>
    </tr>
    <tr>
      <form action="add_user_func.php" method="post">
      <td> <input type="text" name="account"> </td>
      <td> <input type="text" name="password"> </td>
      <td> <INPUT TYPE="checkbox" name="is_admin" value="TRUE">admin</td>
      <td> <button type="submit">新增</button> </td>
      <td> <a href="account_management.php"><button type="button">取消</button></a> </td>
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
