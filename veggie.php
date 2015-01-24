<?php
    $user = $_GET['user'];
    $veggie = $_GET['v'];
    $link=mysql_connect("localhost", "robertbastian_mb", "") or die(); 
    mysql_select_db("robertbastian_mb") or die(); 
    mysql_set_charset('utf8',$link);
    $result = mysql_query(sprintf("UPDATE `users` SET `vegetarian` = %d WHERE user = '%s'",$veggie,$user));
    $response = ['success' => ($result !== false)];
    print (json_encode($response));
?>