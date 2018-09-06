<?php

include_once("../src/autoload.php");

use SinusBot\SinusBot;

$sinusbot = new SinusBot("http://127.0.0.1:8087");
$sinusbot->login("admin", "foobar");

$files = $sinusbot->getFiles();

function return_if_exists($key, $arr) {
  if (array_key_exists($key, $arr)) {
    return $arr[$key];
  }
  return "-";
}

foreach ($files as $file) {
  echo "uuid: ".$file['uuid'].' artist: '.return_if_exists("artist", $file).' title: '.return_if_exists("title", $file)." album: ".(array_key_exists("album", $file)?("(".$file['album'].")"):"")."\n";
}
?>
