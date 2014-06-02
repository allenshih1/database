<?php require_once("header.php"); ?>
<?php
if(isset($_POST['departure']) && isset($_POST['destination']) && isset($_POST['max_transfer']))
{
  $_SESSION['departure'] = $_POST['departure'];
  $_SESSION['destination'] = $_POST['destination'];
  $_SESSION['max_transfer'] = $_POST['max_transfer'];
  $_SESSION['overnight'] = $_POST['overnight'];
  $_SESSION['cancel'] = false;
}
else if(!isset($_SESSION['departure']) ||  $_SESSION['departure'] == ' ')
{
  $_SESSION['departure'] = ' ' ;
  $_SESSION['destination'] = ' ' ;
  $_SESSION['max_transfer'] = ' ' ;
  $_SESSION['overnight'] = ' ' ;
  $_SESSION['cancel'] = false;
}
else if($_SESSION['departure'] != ' ' && $_SESSION['destination'] != ' '  && $_SESSION['max_transfer'] != ' ')
{
  $_SESSION['cancel'] = true;
}

$departure = $_SESSION['departure'];
$destination = $_SESSION['destination'];
$max_transfer = $_SESSION['max_transfer'];
$overnight = $_SESSION['overnight'];
$_SESSION['source'] = "search_ticket.php";

if($overnight==='yes')
  $overnightsql = ' AND (DATE(f_arrival_date) = DATE(s_departure_date) OR s_id is null) AND (DATE(s_arrival_date) = DATE(t_departure_date) OR t_id is null) ';

$order = ' ';
if(isset($_GET['orderKey']) && isset($_GET['orderDirection']))
{
  if(($_GET['orderKey'] == 'f_departure_date' || $_GET['orderKey'] == 'final_time' || $_GET['orderKey'] == 'price' ||
      $_GET['orderKey'] == 'total_time' || $_GET['orderKey'] == 'flight_time' || $_GET['orderKey'] == 'transfer_time')
     && ($_GET['orderDirection'] == 'asc' || $_GET['orderDirection'] == 'desc' ))
    $order = "ORDER BY ".$_GET['orderKey']." ".$_GET['orderDirection'];
}

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
        <option value="<?= $airport->abbr ?>"<?php if($departure === $airport->abbr) echo ' selected="selected"'?>> <?= $airport->abbr.",".$airport->name ?> </option>
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
        <option value="<?= $airport->abbr ?>"<?php if($destination === $airport->abbr) echo ' selected="selected"'?>> <?= $airport->abbr.",".$airport->name ?> </option>
    <?php
      }
    }
    ?>
  </select>
  Max_Transfer
  <select name="max_transfer">
    <option value="0"<?php if($max_transfer === '0') echo ' selected="selected"'?>> 0 </option>
    <option value="1"<?php if($max_transfer === '1') echo ' selected="selected"'?>> 1 </option>
    <option value="2"<?php if($max_transfer === '2') echo ' selected="selected"'?>> 2 </option>
  </select>
  <input type="checkbox" name="overnight" value="yes"<?php if($overnight === 'yes') echo " checked"; ?>>overnight
  <button type="submit"><i class="fa fa-search"></i></button>
</form>
<?
  if($_SESSION['cancel'] == true )
  {?>
    <a href=ticket_cancel.php>取消</a>
  <?}?>
<?php
  $sql =
  "
SELECT
	TIMEDIFF(final_time, f_departure_date) AS total_time,
	CASE type
		WHEN 0 THEN f_flight_time
		WHEN 1 THEN ADDTIME(f_flight_time, s_flight_time)
		WHEN 2 THEN ADDTIME(ADDTIME(f_flight_time, s_flight_time), t_flight_time)
		ELSE             null
	END
	AS flight_time,
  c.*
FROM
(
SELECT
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
				(f.destination = s.departure AND f.arrival_date + interval 2 hour <= s.departure_date AND f.departure != s.destination)
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
) AS c
WHERE type <= ? ".$overnightsql.$order;

  $tickets = $db->prepare($sql);
  $tickets->execute(array($departure, $destination, $max_transfer));
?>
  <table style="width:1200px">
    <tr>
      <td> Result </td>
      <td> Flight_Number </td>
      <td> Departure_Airport </td>
      <td> Destination_Airport </td>
      <td> Departure_Time <?echo OrderButton('f_departure_date',$_SESSION['source']);?></td>
      <td> Arrival_Time <?echo OrderButton('final_time',$_SESSION['source']);?></td>
      <td> Flight_Time </td>
      <td> Total_Flight_Time <?echo OrderButton('flight_time',$_SESSION['source']);?></td>
      <td> Transfer_Time <?echo OrderButton('transfer_time',$_SESSION['source']);?></td>
      <td> Total_Time <?echo OrderButton('total_time',$_SESSION['source']);?></td>
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
    <td> <?= $ticket->flight_time ?> </td>
    <td> <?= $ticket->transfer_time ?> </td>
    <td> <?= $ticket->total_time ?> </td>
    <td> <?= $ticket->price ?> </td>
<?php
      if(isset($_SESSION['isAuth']))
      {
        $uid = $_SESSION['uid'];
        $type = $ticket->type;
        $f_id = $ticket->f_id;
        $s_id = $ticket->s_id;
        $t_id = $ticket->t_id;
        if($type === '0')
        {
          $sql = "SELECT * FROM Ticket WHERE user_id = ? and f_id = ? and s_id is null and t_id is null";
          $search_compare = $db->prepare($sql);
          $search_compare->execute(array($uid, $f_id));
        }
        if($type === '1')
        {
          $sql = "SELECT * FROM Ticket WHERE user_id = ? and f_id = ? and s_id = ? and t_id is null";
          $search_compare = $db->prepare($sql);
          $search_compare->execute(array($uid, $f_id, $s_id));
        }
        if($type === '2')
        {
          $sql = "SELECT * FROM Ticket WHERE user_id = ? and f_id = ? and s_id = ? and t_id = ?";
          $search_compare = $db->prepare($sql);
          $search_compare->execute(array($uid, $f_id, $s_id, $t_id));
        }

        if(!($search_compare->fetchObject()))
        {
?>
    <td>
      <form action="add_to_ticket.php" method="POST">
        <input type="hidden" name="type" value="<?= $ticket->type ?>">
        <input type="hidden" name="f_id" value="<?= $ticket->f_id ?>">
        <input type="hidden" name="s_id" value="<?= $ticket->s_id ?>">
        <input type="hidden" name="t_id" value="<?= $ticket->t_id ?>">
        <button type="submit">加入比價表</button>
      </form>
    </td>
  </tr>
<?php
        }
      }
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
    <td> </td>
    <td> </td>
  </tr>
<?php
    }
  $i++;
  }
?>
  </table>
<?php require_once("search_sql.php"); ?>
<?php require_once("footer.php"); ?>
