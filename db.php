<?
require_once('config.php');
$dsn ="mysql:host=$db_host;dbname=$db_name";
$db = new PDO($dsn, $db_user,$db_password);
?>
