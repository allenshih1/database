<?
session_save_path("./session/");
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>My Flight site</title>
  </head>
  <body>
<?php
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
?>
<table>
<tr>
<td> <a href="admin_flight.php"> 班機管理 </a> </td>
<td> <a href="account_management.php"> 帳號管理 </a> </td>
<td> <a href="airport_management.php"> 機場管理 </a> </td>
<td> <a href=logout.php> 登出 </a> </td>
</tr>
</table>
<?php
}
elseif(isset($_SESSION['isAuth']))
{
?>
<table>
<tr>
<td> <a href="flight.php"> 班機 </a> </td>
<td> <a href=logout.php> 登出 </a> </td>
</tr>
</table>
<?php
}
?>
