<? require_once("header.php"); ?>
<?
if(isset($_SESSION['isAuth']))
{
  $order = 'flight_number ASC';
  if(isset($_GET['orderKey']) && isset($_GET['orderDirection']))
  {
    $order = $_GET['orderKey']." ".$_GET['orderDirection'].",".$order;
  }
  require_once("db.php");
  require_once("order_button.php");
  $sql = "SELECT * FROM Flight ORDER BY $order";
  $flights = $db->prepare($sql);
  $flights->execute();
  $source = "flight.php";
  ?>
  <table style="width:800px">
    <tr>
      <td> id <?echo OrderButton('id',$source);?> </td>
      <td> flight_number <?echo OrderButton('flight_number',$source);?></td>
      <td> departure <?echo OrderButton('departure',$source);?></td>
      <td> destination <?echo OrderButton('destination',$source);?></td>
      <td> departure_date <?echo OrderButton('departure_date',$source);?></td>
      <td> arrival_date <?echo OrderButton('arrival_date',$source);?></td>
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
