<?php
include("sinusbot.class.php");
$sinusbot = new SinusBot("127.0.0.1", 8087);
$sinusbot->login("admin", "foobar");

$instances = $sinusbot->getInstances();
for ($i = 0; $i < count($instances); $i++) {
  $status = $sinusbot->getStatus($instances[$i]['uuid']);
  if ($status['playing']) {
      echo $instances[$i]["nick"].' spielt '.(($status["currentTrack"]["type"] == "url") ? $status["currentTrack"]["tempTitle"] : $status["currentTrack"]["title"]).' von '.(($status["currentTrack"]["type"] == "url") ? $status["currentTrack"]["tempArtist"] : $status["currentTrack"]["artist"]).'<br>';
  } else {
      echo $instances[$i]["nick"].' ist gestoppt.<br>';
  }
}
?>
