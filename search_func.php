<form action = "<?$_SESSION['source']?>" method ="GET">
  <select name = "choice">
    <option value="flight_number">Flight_number</option>
    <option value="departure">Departure</option>
    <option value="destination">Destination</option>
  </select>
  <input type="TEXT" name="keyword" placeholder="keyword">
  <button type="submit"><i class="fa fa-search"></i></button>

<?if(isset($_SESSION['searchError']))
  {
     echo "keyword cannot empty or include space";
     unset($_SESSION['searchError']);
  }
  if(isset($_SESSION['search']) && $_SESSION['search']!=" ")
  {?>
    <a href=cancel.php>取消</a>
  <?

  }

?>
</form>
