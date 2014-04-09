<? require_once("header.php"); ?>
<?
if(isset($_SESSION['isAuth']) && $_SESSION['isAdmin'])
{
  $order = 'flight_number ASC';
  if(isset($_GET['orderKey']) && isset($_GET['orderDirection']))
  {
    $order = $_GET['orderKey']." ".$_GET['orderDirection'].",".$order;
  }
  if(isset($_GET['choice']) && isset($_GET['keyword']))
  {
    if($_GET['keyword'] ==="" || preg_match("/ /",$_GET['keyword']))
      $_SESSION['searchError'] = true;
    else{
       $_SESSION['search'] = "WHERE ".$_GET['choice']." like '%".$_GET['keyword']."%'";
    }
  }
  if(!isset($_SESSION['search']))
    $_SESSION['search'] = " ";
  require_once("db.php");
  require_once("order_button.php");
  $sql = "SELECT * FROM Flight ".$_SESSION['search']." ORDER BY $order";
  $flights = $db->prepare($sql);
  $flights->execute();
  $_SESSION['source'] = "admin_flight.php";
  require_once("search_func.php");
  ?>
  <table style="width:1000px">
    <tr>
      <td> Id <?echo OrderButton('id',$_SESSION['source']);?> </td>
      <td> Flight_number <?echo OrderButton('flight_number',$_SESSION['source']);?></td>
      <td> Departure <?echo OrderButton('departure',$_SESSION['source']);?></td>
      <td> Destination <?echo OrderButton('destination',$_SESSION['source']);?></td>
      <td> Departure_date <?echo OrderButton('departure_date',$_SESSION['source']);?></td>
      <td> Arrival_date <?echo OrderButton('arrival_date',$_SESSION['source']);?></td>
      <td> Price <?echo OrderButton('ticket_price',$_SESSION['source']);?></td>
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
      <td>
        <select name="departure">
      <?php
      $sql = "SELECT * FROM Airport";
      $airports = $db->prepare($sql);
      $airports->execute();

      while($airport=$airports->fetchObject())
      {
      ?>
          <option value="<?= $airport->name ?>"<?= $airport->name===$flight->departure ? "selected = \"selected\"" : "" ?>> <?= $airport->name ?> </option>
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
          <option value="<?= $airport->name ?>"<?= $airport->name===$flight->destination ? "selected = \"selected\"" : "" ?>> <?= $airport->name ?> </option>
      <?php
      }
      ?>
        </select>
      </td>
      <td> <input type="datetime-local" name="departure_date" value="<? echo date("Y-m-d\TH:i:s", strtotime($flight->departure_date)); ?>" step="1"> </td>
      <td> <input type="datetime-local" name="arrival_date" value="<? echo date("Y-m-d\TH:i:s", strtotime($flight->arrival_date)); ?>" step="1"> </td>
      <td> <input type="text" name="ticket_price" value="<?= $flight->ticket_price ?>"> </td>
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
      <td> <?= $flight->ticket_price; ?> </td>

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
      <td>
        <form action="add_to_compare.php" method="post">
          <input type="hidden" name="flight_id" value="<?= $flight->id ?>">
          <button type="submit">加入比價表</button>
        </form>
      </td>
  <?
      }
    }
?>
    </tr>
    <?
  }
  ?>
    <tr>
      <form action="create_flight.php" method="post">
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
          <option value="<?= $airport->name ?>"> <?= $airport->name ?> </option>
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
          <option value="<?= $airport->name ?>"> <?= $airport->name ?> </option>
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
