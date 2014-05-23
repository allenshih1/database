<?php require_once("header.php"); ?>
<?php
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
?>
  <table style="width:1000px">
    <tr>
      <td> Id </td>
      <td> Flight_number </td>
      <td> Departure </td>
      <td> Destination </td>
      <td> Departure_date </td>
      <td> Arrival_date </td>
      <td> Price </td>
    </tr>

    <tr>
      <form action="create_flight_func.php" method="post">
      <td> </td>
      <td> <input type="text" name="flight_number"> </td>
      <td>
        <select name="departure">
      <?php
      $sql = "SELECT * FROM Airport";
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
      </td>
      <td>
        <select name="destination">
      <?php
      $sql = "SELECT * FROM Airport";
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
      </td>
      <td> <input type="datetime-local" name="departure_date" value="<? echo date("Y-m-d\TH:i:s", time()); ?>" step="1"> </td>
      <td> <input type="datetime-local" name="arrival_date" value="<? echo date("Y-m-d\TH:i:s", time()); ?>" step="1"> </td>
      <td> <input type="text" name="ticket_price"> </td>
      <td>
        <input type="hidden" name="create_flight" value="TRUE">
        <button type="submit">新增</button>
      </td>
      </form>
      <td>
        <a href="admin_flight.php"><button type="button">取消</button></a>
      </td>
    </tr>
  </table>

<?php
} else if(isset($_SESSION['isAuth'])){
?>
permission denied
<?php
} else {
?>
please login first
<?php
}
?>
<?php require_once("footer.php"); ?>
