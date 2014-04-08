<?
  function OrderButton($key,$source)
  {
    $dsn = '<a href="'.$source.'?orderKey='.$key.'&orderDirection=asc">'.
        ' <i class="fa fa-sort"></i>'.
        ' </a>';

    if(isset($_GET['orderKey']) && isset($_GET['orderDirection']))
    {
      if($key == $_GET['orderKey'])
      {
        if($_GET['orderDirection'] == 'asc')
        {
          $dsn = '<a href="'.$source.'?orderKey='.$key.'&orderDirection=desc">'.
          ' <i class="fa fa-sort-desc"></i>'.
          ' </a>';
        }
        else if($_GET['orderDirection'] == 'desc')
        {
          $dsn = '<a href="'.$source.'?orderKey='.$key.'&orderDirection=asc">'.
          ' <i class="fa fa-sort-asc"></i>'.
          ' </a>';
        }
      }
    }
    return $dsn;
  }
?>
