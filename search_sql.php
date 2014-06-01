<?php
  $sql_txt =
  "
SELECT
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
			f_departure = '".$departure."'
			AND
			CASE
				WHEN s_id is null THEN f_destination
				WHEN t.id is null THEN s_destination
				ELSE t.destination
			END = '".$destination."'
	) AS a
) AS c
WHERE type <= '".$max_transfer."' ".$order;
echo "<pre>".$sql_txt."</pre>";
?>
