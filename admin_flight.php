<? require_once("header.php"); ?>
<?
if(isset($_SESSION['isAuth']))
{
  require_once("db.php");
  if(isset($_POST['delete']))
  {
    $sql = "DELETE FROM Flight WHERE id = ?";
    $delete_flight = $db->prepare($sql);
    $delete_flight->execute(array($_POST['delete']));
  }
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
      $sql = "INSERT INTO Flight ( flight_number, departure, destination, departure_date, arrival_date)"
        ."VALUES (?, ?, ?, ?, ?)";
      $create_flight = $db->prepare($sql);
      $create_flight->execute(
        array($_POST['flight_number'],
        $_POST['departure'],
        $_POST['destination'],
        $_POST['departure_date'],
        $_POST['arrival_date']));
    }
  }
  if(isset($_POST['update_flight']))
  {
    $uf = false;
    if(preg_match('/ /', $_POST['flight_number']) || $_POST['flight_number'] ===''){
      echo "flight_number cannot contain space or be empty<br>";
      $uf = true;
    }
    if(preg_match('/ /', $_POST['departure']) || $_POST['departure'] ===''){
      echo "depature cannot contain space or be empty<br>";
      $uf = true;
    }
    if(preg_match('/ /', $_POST['destination']) || $_POST['destination'] ===''){
      echo "destination cannot contain space or be empty<br>";
      $uf = true;
    }
    if($_POST['departure_date'] ===''){
      echo "depature_date cannot be empty<br>";
      $uf = true;
    }
    if($_POST['arrival_date'] ===''){
      echo "arrival_date cannot be empty<br>";
      $uf = true;
    }
    if($uf !== true)
    {
    $sql = "UPDATE Flight SET flight_number = ?,"
      ." departure = ?,"
      ." destination = ?,"
      ." departure_date = ?,"
      ." arrival_date = ?"
      ." WHERE ID = ?";
    $update_flight = $db->prepare($sql);
    $update_flight->execute(
      array($_POST['flight_number'],
      $_POST['departure'],
      $_POST['destination'],
      $_POST['departure_date'],
      $_POST['arrival_date'],
      $_POST['id']));
    }
  }
  $sql = "SELECT * FROM Flight";
  $flights = $db->prepare($sql);
  $flights->execute();
  ?>
  <a href=logout.php>登出</a><br>
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
    <form action="admin_flight.php" method="post">
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
        <form action="admin_flight.php" method="post">
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
      <form action="admin_flight.php" method="post">
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
else
{
?>
  <a href=login.php>返回</a><br>
  Please login first
<?
}
?>
<? require_once("footer.php"); ?>
