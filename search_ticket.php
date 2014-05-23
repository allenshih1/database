<?php require_once("header.php"); ?>
<?php
$departure = $_POST['departure'];
$destination = $_POST['destination'];
$max_transfer = $_POST['max_transfer'];
echo "$max_transfer";
require_once("db.php");
?>
<h1>機票查詢</h1>
<form action="search_ticket.php" method="POST">
  Departure_Airport
  <select name="departure">
  <?php
    $sql = "SELECT `abbr` FROM Airport";
    $airports = $db->prepare($sql);
    $airports->execute();
    while($airport=$airports->fetchObject())
    {
    ?>
    <option value="<?= $airport->abbr ?>"> <?= $airport->abbr ?> </option>
    <?php
    }
    ?>
  </select>
  Arrival_Airport
  <select name="destination">
  <?php
    $sql = "SELECT `abbr` FROM Airport";
    $airports = $db->prepare($sql);
    $airports->execute();
    while($airport=$airports->fetchObject())
    {
    ?>
    <option value="<?= $airport->abbr ?>"> <?= $airport->abbr ?> </option>
    <?php
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
<?php
  $sql = 
"SELECT
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
			WHEN t_id is null THEN 1
			ELSE 2
		END
		AS type,
		f.id AS f_id,
		f.flight_number AS f_flight_number,
		f.departure AS f_departure,
		f.destination AS f_destination,
		f.departure_date AS f_departure_date,
		f.arrival_date AS f_arrival_date,
		f.ticket_price AS f_ticket_price,
		b.*
	FROM
		`Flight` AS f LEFT JOIN
		(
			SELECT 
				s.id AS s_id,
				s.flight_number AS s_flight_number,
				s.departure AS s_departure,
				s.destination AS s_destination,
				s.departure_date AS s_departure_date,
				s.arrival_date AS s_arrival_date,
				s.ticket_price AS s_ticket_price,
				t.id AS t_id,
				t.flight_number AS t_flight_number,
				t.departure AS t_departure,
				t.destination AS t_destination,
				t.departure_date AS t_departure_date,
				t.arrival_date AS t_arrival_date,
				t.ticket_price AS t_ticket_price
			FROM `Flight` AS s LEFT JOIN `Flight` AS t 
			ON s.destination = t.departure AND s.departure_date + interval 2 hour <= t.departure_date
		) AS b
	ON f.destination = s_departure AND f.departure_date + interval 2 hour <= s_departure_date
	WHERE 
		f.departure = ?
		AND
		CASE
			WHEN s_id is null THEN f.destination
			WHEN t_id is null THEN s_destination
			ELSE t_destination
		END = ?
) AS a
WHERE type <= ?";

  $tickets = $db->prepare($sql);
  $tickets->execute(array($departure, $destination, $max_transfer));
?>
  <table style="width:1000px">
    <tr>
      <td> Result </td>
      <td> Flight_Number </td>
      <td> Departure_Airport </td>
      <td> Destination_Airport </td>
      <td> Departure_Time </td>
      <td> Arrival_Time </td>
      <td> Flight_Time </td>
      <td> Transfer Time </td>
      <td> Price </td>
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
