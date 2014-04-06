<? require_once("header.php"); ?>
<?
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  require_once("db.php");
  $sql = "SELECT * FROM Flight";
  $flights = $db->prepare($sql);
  $flights->execute();
  ?>
  <table style="width:1000px">
    <tr>
      <td> id </td>
      <td> flight_number </td>
      <td> departure </td>
      <td> destination </td>
      <td> departure_date </td>
      <td> arrival_date </td>
    </tr>
  <?
  while($flight = $flights->fetchObject())
  {
  ?>
    <tr>
    <?
    if(isset($_POST['update']) && $_POST['update'] == $flight->id)
    {?>
    <form action="update_flight_func.php" method="post">
      <td> <?= $flight->id ?></td>
      <td> <input type="text" name="flight_number" value="<?= $flight->flight_number ?>"> </td>
      <td> <input type="text" name="departure" value="<?= $flight->departure ?>"> </td>
      <td> <input type="text" name="destination" value="<?= $flight->destination ?>"> </td>
      <td> <input type="datetime-local" name="departure_date" value="<? echo date("Y-m-d\TH:i:s", strtotime($flight->departure_date)); ?>" step="1"> </td>
      <td> <input type="datetime-local" name="arrival_date" value="<? echo date("Y-m-d\TH:i:s", strtotime($flight->arrival_date)); ?>" step="1"> </td>
      <td>
        <input type="hidden" name="update_flight" value="TRUE">
        <input type="hidden" name="id" value="<?= $flight->id ?>">
        <button type="submit">儲存</button>
      </td>
      </form>
      <td>
        <a href="admin_flight.php"><button type="button">取消</button></a>
      </td>
      <?
    }
    else
    {
  ?>
      <td> <?= $flight->id; ?> </td>
      <td> <?= $flight->flight_number; ?> </td>
      <td> <?= $flight->departure; ?> </td>
      <td> <?= $flight->destination; ?> </td>
      <td> <?= $flight->departure_date; ?> </td>
      <td> <?= $flight->arrival_date; ?> </td>

  <?
    if(!isset($_POST['create']) && !isset($_POST['update']))
    {
  ?>
      <td>
        <form action="admin_flight.php" method="post">
          <input type="hidden" name="update" value="<?= $flight->id ?>">
          <button type="submit">修改</button>
        </form>
      </td>
      <td>
        <form action="delete_flight.php" method="post">
          <input type="hidden" name="delete" value="<?= $flight->id ?>">
          <button type="submit">刪除</button>
        </form>
      </td>
  <?
      }
    }
?>
    </tr>
    <?
  }
  if(isset($_POST['create']))
  {
  ?>
    <tr>
      <form action="create_flight.php" method="post">
      <td> </td>
      <td> <input type="text" name="flight_number"> </td>
      <td> <input type="text" name="departure"> </td>
      <td> <input type="text" name="destination"> </td>
      <td> <input type="datetime-local" name="departure_date" step="1"> </td>
      <td> <input type="datetime-local" name="arrival_date" step="1"> </td>
      <td>
        <input type="hidden" name="create_flight" value="TRUE">
        <button type="submit">儲存</button>
      </td>
      </form>
      <td>
        <a href="admin_flight.php"><button type="button">取消</button></a>
      </td>
    </tr>
  <?
  }
  ?>
  </table>
  <? if(!isset($_POST["create"]) && !isset($_POST["update"])){ ?>
  <form action="admin_flight.php" method="post">
    <input type="hidden" name="create" value="TRUE">
    <button type="submit">新增</button>
  </form>
  <?}?>
<?
}
else if(isset($_SESSION['isAuth']))
{
?>
  Permission denied
<?
}
else
{
?>
  <a href=login.php>返回</a><br>
  Please login first
<?
}
?>
<? require_once("footer.php"); ?>
