<? require_once("header.php"); ?>
<?
if(isset($_SESSION['isAuth']))
{
  require_once("db.php");
  $sql = "SELECT * FROM Flight";
  $flights = $db->prepare($sql);
  $flights->execute();
  ?>
  <table style="width:800px">
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
