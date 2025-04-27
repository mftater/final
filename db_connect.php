<?php
  $host_name = 'db5017664491.hosting-data.io';
  $database = 'dbs14127978';
  $user_name = 'dbu4055689';
  $password = 'finalproject!';

  $link = new mysqli($host_name, $user_name, $password, $database);

  if ($link->connect_error) {
    die('<p>Failed to connect to MySQL: '. $link->connect_error .'</p>');
  } 
?>