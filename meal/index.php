<?php
    header('Content-Type: text/html; charset=utf-8');
    $_SESSION['user'] = "orie2927";
    $link=mysql_connect("localhost", "robertbastian_mb", "Lor3m1psum") or die(); 
    mysql_select_db("robertbastian_mb") or die(); 
    mysql_set_charset('utf8',$link);
    $result = mysql_query("SELECT DISTINCT date FROM events WHERE date > NOW() ORDER BY date ASC");
    $days = [];
    while ($date = mysql_fetch_row($result))
      $days[$date['0']] = [];      
    foreach ($days as $date => $whatever)
    {
      $result = mysql_query(sprintf("SELECT id,type,name,menu FROM events WHERE date = '%s' ORDER BY type ASC",$date));
      while ($event = mysql_fetch_array($result))
        $days[$date][$event['type']] = $event;
    }    
    $result = mysql_query(sprintf("SELECT * FROM users WHERE user = '%s'",$_SESSION['user']));
    $user = mysql_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Oriel College Meal Booking System</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
      [class*="col-"] 
      {
        padding-top: 15px;
        padding-bottom: 15px;
      }
    </style>

    <script type="text/javascript">
        var user = <?="'".json_encode($user)."'"?>
    </script>
  </head>
  <body style="padding-top:70px" class="text-center" data-spy="scroll" data-target="navbar">
    <!-- Navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="/">
            <img height=30 alt="Oriel Crest" src="http://www.oriel.ox.ac.uk/sites/default/files/ORIEL%20CREST%20FEB%202011_2%20copy%20web.jpg"/>
            Oriel Meal Booking System
          </a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="#">Week 1</a></li>
            <li><a href="#">Week 2</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="javascript:$('#settingsModal').modal()">Settings</a></li>
            <li><a href="javascript:alert('Not implemented')">Log out</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">
      <div class="row">
        <?php foreach($days as $dateString => $events): 
                $date = strtotime($dateString);
        ?>
        <div class="col-md-3">
          <h3> 
            <?php print(date("l",$date)) ?> <br>
            <small><?php print(date("M j",$date))?></small>
          </h3>
        <?php 
            if (isSet($events['brunch'])) makeBox($events['brunch']);
            if (isSet($events['lunch'])) makeBox($events['lunch']);
            if (isSet($events['informal'])) makeBox($events['informal']);
            if (isSet($events['formal'])) makeBox($events['formal']);
        ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    
    <?php
    function makeBox($event)
    {
      $properNames = ['formal' => 'Formal Hall', 'informal' => 'Informal Hall', 'brunch' => "Brunch", 'lunch' => "Lunch"];
    ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">
            <?php
              if($event['name']!="")
                echo $event['name'];
              else
                echo $properNames[$event['type']];
            ?>
          </h3>              
        </div>
        <?php if ($event['type'] != 'brunch'): ?>
        <div class="panel-body">
          <?php 
            if($event['menu'] != "")
              foreach(json_decode($event['menu'],true) as $line)
                echo "<p>".preg_replace("/V$/", " <span class='glyphicon glyphicon-leaf'></span>", $line)."</p>";
            else
                echo "<h4><small>Menu not yet available</small></h4>";
          ?>   
        </div>
        <?php endif; ?>
        <?php if ($event['type'] != 'lunch'):?>
        <div class="panel-footer">
          <?php makeButton($event['id'])?>
        </div>
        <?php endif; ?>
      </div>
    <?php 
    }

    function makeButton($id)
    {
      $result = mysql_query(sprintf("SELECT COUNT(*) FROM `bookings` WHERE `eventid` = %d",$id));
      $bookings = mysql_fetch_array($result)[0];
      $result = mysql_query(sprintf("SELECT SUM(`capacity`) FROM `events` WHERE `id` = %d",$id));
      $capacity = mysql_fetch_array($result)[0];
      $result = mysql_query(sprintf("SELECT COUNT(*) FROM `bookings` WHERE (`eventid` = %d AND `user` = '%s')",$id,$_SESSION['user']));
      $booked = (mysql_fetch_array($result)[0]) > 0;

    ?> 
      <div class=<?=($booked)?"'btn-group'":"'btn-group hidden'"?>>
        <button type="button" class="btn btn-sm btn-success">Booked</button>
        <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href=<?="'javascript:editBooking(".$id.")'"?>>Edit booking</a></li>
          <li><a href=<?="'javascript:deleteBooking(".$id.")'"?>>Delete booking</a></li>
          <li class="divider"></li>
          <li class="disabled"><a href="#"><?=($capacity-$bookings)?>/<?=$capacity?> places left</a></li>
          <li><a href=<?="'javascript:showList(".$id.")'"?>>Show list</a></li>
        </ul>
      </div>
      <div class=<?=(!$booked)?"'btn-group'":"'btn-group hidden'"?>>
        <button type="button" class="btn btn-sm btn-primary" onclick=<?="'bookPreset(".$id.")'"?>>Book</button>
        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href=<?="'javascript:nonDefaultBooking(".$id.")'"?>>Book <?=($user['vegetarian'])?"normal":"vegetarian"?> place</a></li>
          <li><a href=<?="'javascript:customBooking(".$id.")'"?>>Book more places</a></li>
          <li class="divider"></li>
          <li class="disabled"><a href="#"><?=($capacity-$bookings)?>/<?=$capacity?> places left</a></li>
          <li><a href=<?="'javascript:showList(".$id.")'"?>>Show list</a></li>
        </ul>
      </div>
    <?php }?>

<!-- Settings -->
    <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header text-center">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Settings</h4>
          </div>
          <div class="modal-body center-block">
            <table class="table text-left">
              <tr><td>Name</td><td><?=$user['firstname']." ".$user['lastname']?></td><tr>
              <tr><td>Username</td><td><?=$user['user']?></td><tr>
              <tr><td>Email</td><td><?=$user['email']?></td></tr>
              <tr><td>Allowed Guests</td><td>
                <span class=<?=($user['allowedGuests'])?"'glyphicon glyphicon-ok'":"'glyphicon glyphicon-error'"?>></span>
              </td></tr>
              <tr><td>Vegetarian</td><td>
                <div class="btn-group" data-toggle="buttons" id="veggie">
                  <label class="btn btn-primary">
                    <input type="radio" value=1>Yes
                  </label>
                  <label class="btn btn-primary">
                    <input type="radio" value=0>No
                  </label>
                </div>
              </td></tr>
            </table>
          </div>
          <!--<div class="modal-footer">
            <p class="text-center"> With the exception of the fields marked with an asterisk, all of the information held in this page is derived directly from the main University Card database. Information is updated overnight each day from the card database, so if something here is incorrect, you should get your card updated.</p>
          </div>-->
        </div>
      </div>
    </div>
<!-- List -->
    <div class="modal fade" id="listModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="listLabel"></h4>
          </div>
          <div class="modal-body">
            <h4 id="noBookings"><small>No bookings yet</small></h4>
            <h4 id="bookingsFail"><small>Couldn't load list</small><h4>
            <table class="table table-striped text-left">
            <thead><tr><th>First Name</th><th>Last Name</th><th>Places</th></tr></thead>
            <tbody id="listTable"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="script.js"></script>
  </body>
</html>