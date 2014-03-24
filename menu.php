<? require_once("header.php"); ?>
<?if(!isset($_SESSION['isAuth'])){?>
    <a href="login.php">登入</a>
<?}else{?>
    <a href="logout.php">登出</a>
<?}?>

    <br><a href="register.php">註冊</a><br>

<?if(isset($_SESSION['isAuth'])){?>
    <a href="flight.php">flight</a>
<?}?>
<? require_once("footer.php"); ?>
