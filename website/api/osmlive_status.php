<?php

$path = "/home/osm-planet/osmlive/_diff"; 

$latest_ctime = 0;
$latest_filename = '';    

$d = dir($path);
while (false !== ($entry = $d->read())) {
  $filepath = "{$path}/{$entry}";
  // could do also other checks than just checking whether the entry is a file
  if (is_file($filepath) && filemtime($filepath) > $latest_ctime) {
    $latest_ctime = filemtime($filepath);
    $latest_filename = $entry;
  }
}

echo date('Y-m-d H:i:s', $latest_ctime);


?>
