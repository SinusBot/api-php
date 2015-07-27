<?php
include("sinusbot.class.php");
$sinusbot = new SinusBot("http://127.0.0.1:8087");
$sinusbot->login("admin", "foobar");

$files = $sinusbot->getFiles();
for ($i = 0; $i < count($files); $i++) {
  echo $files[$i]['uuid'].': '.$files[$i]['artist'].' - '.$files[$i]['title'].' ('.$files[$i]['album'].')<br>';
}
?>
