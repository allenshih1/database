<? require_once("header.php"); ?>
<a href=login.php>返回</a><br>
<?
if(isset($_SESSION['isAuth']))
echo"please logout first";

else{
?>
<FORM ACTION="register_func.php" METHOD="POST">
  <INPUT TYPE="text" NAME="account" PLACEHOLDER="account"><br>
  <INPUT TYPE="password" NAME="password" PLACEHOLDER="password"><br>
  <INPUT TYPE="checkbox" name="is_admin" value="TRUE">admin<br>
  <button TYPE="submit">註冊</button>
</FORM>
<?}?>
<? if(isset($_SESSION['error'])){
    echo "error!!";
    unset($_SESSION['error']);
   }
   if(isset($_SESSION['repeat'])){
    echo "this accout has been registered !!";
    unset($_SESSION['repeat']);
   }

?>
<? require_once("footer.php"); ?>
