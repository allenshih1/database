<? require_once("header.php"); ?>
<? if(!isset($_SESSION['isAuth'])){ ?>
<FORM ACTION="login_func.php" METHOD="POST">
  <INPUT TYPE="TEXT" NAME="account" PLACEHOLDER="account" ><br>
  <INPUT TYPE="password" NAME="password" PLACEHOLDER="password"><br>
  <button  type="submit" >登入</button>
  <a href=register.php >註冊</a><br>
  <?
      if(isset($_SESSION['error']))
        echo"login error";
      unset($_SESSION['error']);
  ?>
</form>
<?}else{?>
  <a href=logout.php>登出</a><br>

<?}?>
<? require_once("footer.php"); ?>

