<? require_once("header.php"); ?>
<h1>比價表</h1>
<?
if(isset($_SESSION['isAuth']))
{
  $uid = $_SESSION['uid'];
  $order = 'flight_number ASC';
  if(isset($_GET['orderKey']) && isset($_GET['orderDirection']))
  {
    $order = $_GET['orderKey']." ".$_GET['orderDirection'].",".$order;
  }
  require_once("db.php");
  require_once("order_button.php");
  $sql = "SELECT * FROM Compare, Flight WHERE ".
    "Compare.user_id = ? and Compare.flight_id = Flight.id ORDER BY $order";
  $flights = $db->prepare($sql);
  $flights->execute(array($uid));
  $source = "comparison.php";
  ?>
  <table style="width:1000px">
    <tr>
      <td> Id <?echo OrderButton('id',$source);?> </td>
      <td> Flight_number <?echo OrderButton('flight_number',$source);?></td>
      <td> Departure <?echo OrderButton('departure',$source);?></td>
      <td> Destination <?echo OrderButton('destination',$source);?></td>
      <td> Departure_date <?echo OrderButton('departure_date',$source);?></td>
      <td> Arrival_date <?echo OrderButton('arrival_date',$source);?></td>
      <td> Price <?echo OrderButton('ticket_price',$source);?></td>
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
        <form action="delete_from_compare.php" method="post">
          <input type="hidden" name="flight_id" value="<?= $flight->id ?>">
          <button type="submit">刪除</button>
        </form>
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
