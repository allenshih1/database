<? require_once('check_exist.php'); ?>
<?php
session_save_path("./session/");
session_start();
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  require_once("db.php");
    if(isset($_POST['create_flight']))
  {
    $cf = false;
    if(preg_match('/ /', $_POST['flight_number']) || $_POST['flight_number'] ===''){
      echo "flight_number cannot contain space or be empty<br>";
      $cf = true;
    }
    if(preg_match('/ /', $_POST['departure']) || $_POST['departure'] ===''){
      echo "depature cannot contain space or be empty<br>";
      $cf = true;
    }
    if(preg_match('/ /', $_POST['destination']) || $_POST['destination'] ===''){
      echo "destination cannot contain space or be empty<br>";
      $cf = true;
    }
    if($_POST['departure_date'] ===''){
      echo "depature_date cannot be empty<br>";
      $cf = true;
    }
    if($_POST['arrival_date'] ===''){
      echo "arrival_date cannot be empty<br>";
      $cf = true;
    }
    if($cf !== true)
    {
      $sql = "INSERT INTO Flight ( flight_number, departure, destination, departure_date, arrival_date, ticket_price)"
        ."VALUES (?, ?, ?, ?, ?, ?)";
      $create_flight = $db->prepare($sql);
      $create_flight->execute(
        array($_POST['flight_number'],
        $_POST['departure'],
        $_POST['destination'],
        $_POST['departure_date'],
        $_POST['arrival_date'],
        $_POST['ticket_price']));
      header('Location:admin_flight.php');
    }
  }
}
?>
