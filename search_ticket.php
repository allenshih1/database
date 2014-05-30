<?php require_once("header.php"); ?>
<?php
if(isset($_POST['departure']) && isset($_POST['destination']) && isset($_POST['max_transfer']))
{
  $_SESSION['departure'] = $_POST['departure'];
  $_SESSION['$destination'] = $_POST['destination'];
  $_SESSION['$max_transfer'] = $_POST['max_transfer'];
  $_SESSION['cancel'] = true;
}
else if($_SESSION['departure'] != ' ' && $_SESSION['destination'] != ' '  && $_SESSION['max_transfer'] != ' ')
{
  $_SESSION['departure'] =  $_SESSION['departure'];
  $_SESSION['$destination'] = $_SESSION['destination'];
  $_SESSION['$max_transfer'] = $_SESSION['max_transfer'];
  $_SESSION['cancel'] = true;
}
else
{
  $_SESSION['departure'] = ' ' ;
  $_SESSION['$destination'] = ' ' ;
  $_SESSION['$max_transfer'] = ' ' ;
  $_SESSION['cancel'] = false;
}
if($_SESSION['departure']!=)
$departure = $_SESSION['departure'];
$destination = $_SESSION['destination'];
$max_transfer =$_SESSION['max_transfer'];
$_SESSION['source'] = "search_ticket.php";



$order = ' ';
if(isset($_GET['orderKey']) && isset($_GET['orderDirection']))
{
  if(($_GET['orderKey'] == 'f_departure_date' || $_GET['orderKey'] == 'final_time' || $_GET['orderKey'] == 'price')
     && ($_GET['orderDirection'] == 'asc' || $_GET['orderDirection'] == 'desc' ))
    $order = "ORDER BY ".$_GET['orderKey']." ".$_GET['orderDirection'].",".$order;
}

echo "$max_transfer";
require_once("db.php");
require_once("order_button.php");
?>
<h1>機票查詢</h1>
<form action="search_ticket.php" method="POST">
  Departure_Airport
  <select name="departure">
  <?php
    $sql = "SELECT `name`, `abbr` FROM Country ORDER BY `name`";
    $countries = $db->prepare($sql);
    $countries->execute();
    while($country = $countries->fetchObject())
    {
      ?>
      <optgroup label="<?= $country->name ?>">
      <?php
      $sql = "SELECT `name`, `abbr` FROM Airport WHERE country = ?";
      $airports = $db->prepare($sql);
      $airports->execute(array($country->abbr));
      while($airport=$airports->fetchObject())
      {
    ?>
        <option value="<?= $airport->abbr ?>"> <?= $airport->name ?> </option>
    <?php
      }
    }
    ?>
  </select>
  Arrival_Airport
  <select name="destination">
  <?php
    $sql = "SELECT `name`, `abbr` FROM Country ORDER BY `name`";
    $countries = $db->prepare($sql);
    $countries->execute();
    while($country = $countries->fetchObject())
    {
      ?>
      <optgroup label="<?= $country->name ?>">
      <?php
      $sql = "SELECT `name`, `abbr` FROM Airport WHERE country = ?";
      $airports = $db->prepare($sql);
      $airports->execute(array($country->abbr));
      while($airport=$airports->fetchObject())
      {
    ?>
        <option value="<?= $airport->abbr ?>"> <?= $airport->name ?> </option>
    <?php
      }
    }
    ?>
  </select>
  Max_Transfer
  <select name="max_transfer">
    <option value="0"> 0 </option>
    <option value="1"> 1 </option>
    <option value="2"> 2 </option>
  </select>
  <button type="submit"><i class="fa fa-search"></i></button>
</form>
<?
  if(isset($_SESSION['search']) && $_SESSION['search']!=" ")
  {?>
    <a href=ticket_cancel.php>取消</a>
  <?}?>
<?php
  $sql =
"SELECT
	CASE type
		WHEN 0 THEN f_arrival_date
		WHEN 1 THEN s_arrival_date
		WHEN 2 THEN t_arrival_date
		ELSE             null
	END
	AS final_time,
	CASE type
		WHEN 0 THEN  0
		WHEN 1 THEN TIMEDIFF(s_departure_date, f_arrival_date)
		WHEN 2 THEN ADDTIME( TIMEDIFF(s_departure_date, f_arrival_date), TIMEDIFF(t_departure_date, s_arrival_date))
		ELSE             null
	END
	AS transfer_time,
		TIMEDIFF(CONVERT_TZ(f_arrival_date, (SELECT timezone FROM Airport WHERE abbr = f_destination), (SELECT timezone FROM Airport WHERE abbr = f_departure)), f_departure_date)
	AS f_flight_time,
	CASE type
		WHEN 1 THEN TIMEDIFF(CONVERT_TZ(s_arrival_date, (SELECT timezone FROM Airport WHERE abbr = s_destination), (SELECT timezone FROM Airport WHERE abbr = s_departure)), s_departure_date)
		WHEN 2 THEN TIMEDIFF(CONVERT_TZ(s_arrival_date, (SELECT timezone FROM Airport WHERE abbr = s_destination), (SELECT timezone FROM Airport WHERE abbr = s_departure)), s_departure_date)
		ELSE             null
	END
	AS s_flight_time,
	CASE type
		WHEN 2 THEN TIMEDIFF(CONVERT_TZ(t_arrival_date, (SELECT timezone FROM Airport WHERE abbr = t_destination), (SELECT timezone FROM Airport WHERE abbr = t_departure)), t_departure_date)
		ELSE             null
	END
	AS t_flight_time,
	CASE type
		WHEN 0 THEN  f_ticket_price
		WHEN 1 THEN (f_ticket_price + s_ticket_price) * 0.9
		WHEN 2 THEN (f_ticket_price + s_ticket_price + t_ticket_price) * 0.8
		ELSE             null
	END
	AS price,
	a.*
FROM
(
	SELECT
		CASE
			WHEN s_id is null THEN 0
			WHEN t.id is null THEN 1
			ELSE 2
		END
		AS type,
		b.*,
		t.id AS t_id,
		t.flight_number AS t_flight_number,
		t.departure AS t_departure,
		t.destination AS t_destination,
		t.departure_date AS t_departure_date,
		t.arrival_date AS t_arrival_date,
		t.ticket_price AS t_ticket_price
	FROM
		(
			SELECT
				f.id AS f_id,
				f.flight_number AS f_flight_number,
				f.departure AS f_departure,
				f.destination AS f_destination,
				f.departure_date AS f_departure_date,
				f.arrival_date AS f_arrival_date,
				f.ticket_price AS f_ticket_price,
				s.id AS s_id,
				s.flight_number AS s_flight_number,
				s.departure AS s_departure,
				s.destination AS s_destination,
				s.departure_date AS s_departure_date,
				s.arrival_date AS s_arrival_date,
				s.ticket_price AS s_ticket_price
			FROM `Flight` AS f JOIN
			(
				SELECT * FROM `Flight` UNION
				SELECT
					null AS id,
					null AS flight_number,
					null AS departure,
					null AS destination,
					null AS departure_date,
					null AS arrival_date,
					null AS ticket_price
			) AS s
			ON
				(f.destination = s.departure AND f.arrival_date + interval 2 hour <= s.departure_date)
				OR s.id is null
		) AS b
		JOIN
		(
			SELECT * FROM `Flight` UNION
			SELECT
				null AS id,
				null AS flight_number,
				null AS departure,
				null AS destination,
				null AS departure_date,
				null AS arrival_date,
				null AS ticket_price
		) AS t
	ON
		(s_destination = t.departure AND s_arrival_date + interval 2 hour <= t.departure_date)
		OR t.id is null
	WHERE
		f_departure = ?
		AND
		CASE
			WHEN s_id is null THEN f_destination
			WHEN t.id is null THEN s_destination
			ELSE t.destination
		END = ?
) AS a
WHERE type <= ? ".$order;

  $tickets = $db->prepare($sql);
  $tickets->execute(array($departure, $destination, $max_transfer));
?>
  <table style="width:1000px">
    <tr>
      <td> Result </td>
      <td> Flight_Number </td>
      <td> Departure_Airport </td>
      <td> Destination_Airport </td>
      <td> Departure_Time <?echo OrderButton('f_departure_date',$_SESSION['source']);?></td>
      <td> Arrival_Time <?echo OrderButton('final_time',$_SESSION['source']);?></td>
      <td> Flight_Time </td>
      <td> Transfer Time </td>
      <td> Price <?echo OrderButton('price',$_SESSION['source']);?></td>
    </tr>
<?php
  $i = 1;
  while($ticket = $tickets->fetchObject())
  {
?>
  <tr>
    <td> <?= $i ?> </td>
    <td> <?= $ticket->f_flight_number ?> </td>
    <td> <?= $ticket->f_departure ?> </td>
    <td> <?= $ticket->f_destination ?> </td>
    <td> <?= $ticket->f_departure_date ?> </td>
    <td> <?= $ticket->f_arrival_date ?> </td>
    <td> <?= $ticket->f_flight_time ?> </td>
    <td> <?= $ticket->transfer_time ?> </td>
    <td> <?= $ticket->price ?> </td>
  </tr>
<?php
    if($ticket->type >= 1)
    {
?>
  <tr>
    <td> </td>
    <td> <?= $ticket->s_flight_number ?> </td>
    <td> <?= $ticket->s_departure ?> </td>
    <td> <?= $ticket->s_destination ?> </td>
    <td> <?= $ticket->s_departure_date ?> </td>
    <td> <?= $ticket->s_arrival_date ?> </td>
    <td> <?= $ticket->s_flight_time ?> </td>
    <td> </td>
    <td> </td>
  </tr>
<?php
    }
    if($ticket->type >= 2)
    {
?>
  <tr>
    <td> </td>
    <td> <?= $ticket->t_flight_number ?> </td>
    <td> <?= $ticket->t_departure ?> </td>
    <td> <?= $ticket->t_destination ?> </td>
    <td> <?= $ticket->t_departure_date ?> </td>
    <td> <?= $ticket->t_arrival_date ?> </td>
    <td> <?= $ticket->t_flight_time ?> </td>
    <td> </td>
    <td> </td>
  </tr>
<?php
    }
  $i++;
  }
?>
  </table>
<?php require_once("footer.php"); ?>
