<? require_once("header.php"); ?>
<h1>班機</h1>
<?
if(isset($_SESSION['isAuth']))
{
  $order = 'flight_number ASC';
  if(isset($_GET['orderKey']) && isset($_GET['orderDirection']))
  {
    if(($_GET['orderKey'] == 'id' || $_GET['orderKey'] == 'flight_number' || $_GET['orderKey'] == 'destination' ||
      $_GET['orderKey'] == 'departure' || $_GET['orderKey'] == 'departure_date' || $_GET['orderKey'] == 'arrival_date' ||
      $_GET['orderKey'] == 'ticket_price')&& ($_GET['orderDirection'] == 'asc' || $_GET['orderDirection'] == 'desc' ))
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
  $_SESSION['source'] = "flight.php";
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
      <td> <? echo $flight->id; ?> </td>
      <td> <? echo $flight->flight_number; ?> </td>
      <td> <? echo $flight->departure; ?> </td>
      <td> <? echo $flight->destination; ?> </td>
      <td> <? echo $flight->departure_date; ?> </td>
      <td> <? echo $flight->arrival_date; ?> </td>
      <td> <? echo $flight->ticket_price; ?> </td>
      <td>
      <?php
        $sql = "SELECT * FROM Compare WHERE flight_id = ? and user_id = ?";
        $favorite = $db->prepare($sql);
        $favorite->execute(array($flight->id, $_SESSION['uid']));
        if(!($favorite->fetchObject()))
        {
      ?>
        <form action="add_to_compare.php" method="post">
          <input type="hidden" name="flight_id" value="<?= $flight->id ?>">
          <button type="submit">加入比價表</button>
        </form>
      <?php
        }
      ?>
      </td>
  </tr>
  <?
  }
}
else
{
?>
  <a href=login.php>返回</a><br>
  Please login first
<?
}
?>
</table>
<? require_once("footer.php"); ?>
