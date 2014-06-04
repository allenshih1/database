<? require_once("header.php"); ?>
<h1>機票比價表</h1>
<?
if(isset($_SESSION['isAuth']))
{
  require_once("db.php");
  $uid = $_SESSION['uid'];
  $sql = "
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
			s.ticket_price AS s_ticket_price,
			t.id AS t_id,
			t.flight_number AS t_flight_number,
			t.departure AS t_departure,
			t.destination AS t_destination,
			t.departure_date AS t_departure_date,
			t.arrival_date AS t_arrival_date,
			t.ticket_price AS t_ticket_price
		FROM
			Ticket,
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
			) as f,
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
			) as s,
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
			) as t
		WHERE
			user_id = ? AND f_id = f.id AND ( s_id = s.id OR (s_id is null AND s.id is null) ) AND ( t_id = t.id OR (t_id is null AND t.id is null) )
	) AS a
) AS c
";

  $tickets = $db->prepare($sql);
  $tickets->execute(array($uid));
  ?>
  <table style="width:1200px">
    <tr>
      <td> Result </td>
      <td> Flight_Number </td>
      <td> Departure_Airport </td>
      <td> Destination_Airport </td>
      <td> Departure_Time </td>
      <td> Arrival_Time </td>
      <td> Flight_Time </td>
      <td> Total_Flight_Time </td>
      <td> Transfer_Time </td>
      <td> Total_Time </td>
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
    <td> <?= $ticket->flight_time ?> </td>
    <td> <?= $ticket->transfer_time ?> </td>
    <td> <?= $ticket->total_time ?> </td>
    <td> <?= $ticket->price ?> </td>
    <td>
      <form action="delete_from_ticket.php" method="POST">
        <input type="hidden" name="type" value="<?= $ticket->type ?>">
        <input type="hidden" name="f_id" value="<?= $ticket->f_id ?>">
        <input type="hidden" name="s_id" value="<?= $ticket->s_id ?>">
        <input type="hidden" name="t_id" value="<?= $ticket->t_id ?>">
        <button type="submit">刪除</button>
    </td>
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
}
else
{
?>
  <a href=login.php>返回</a><br>
  Please login first
<?php
}
?>
</table>
<?php require_once("footer.php"); ?>
