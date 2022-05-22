<?php

$date="2018-01-07 21:35:15";
$todaydate = date("Y-m-d H:i:s");

$ago = strtotime($todaydate) - strtotime($date);
  if ($ago >= 86400) {
    $diff = floor($ago/86400).' days ago';
  } elseif ($ago >= 3600) {
    $diff = floor($ago/3600).' hours ago';
  } elseif ($ago >= 60) {
    $diff = floor($ago/60).' minutes ago';
  } else {
    $diff = $ago.' seconds ago';
  }
  echo $diff;

?>