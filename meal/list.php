<?php
    $id = $_GET['id'];
    $link=mysql_connect("localhost", "robertbastian_mb", "Lor3m1psum") or die(); 
    mysql_select_db("robertbastian_mb") or die(); 
    mysql_set_charset('utf8',$link);
    $result = mysql_query("SELECT firstname,lastname,number,users.user FROM users INNER JOIN (SELECT user,COUNT(user) AS number FROM  bookings WHERE eventid = ".$id." GROUP BY user) counts ON counts.user = users.user ORDER BY lastname ASC");
    $list = [];
    while ($row = mysql_fetch_row($result))
    {
        $list[] = $row;
    }
    $result = mysql_query("SELECT type,date FROM events WHERE id = ".$id);
    $result = mysql_fetch_row($result);
    $names = ['formal' => "Formal Hall", "informal" => "Informal Hall", "brunch" => "Brunch"];
    $title = $names[$result[0]].", ".date("l M j",strtotime($result[1]));
    $response = [$title,$list];
    print (json_encode($response));
?>