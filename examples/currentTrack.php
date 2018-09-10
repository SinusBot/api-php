<?php

include_once("../src/autoload.php");

$sinusbot = new SinusBot\API("http://127.0.0.1:8087");
$sinusbot->login("admin", "foobar");

$instances = $sinusbot->getInstances();

foreach ($instances as $instance) {
    $isPlaying = $instance->isPlaying();
    if ($isPlaying) {
        echo "Instance: ".$instance->getNick()." is playing";
    } else {
        echo "Instance: ".$instance->getNick()." is not playing";
    }
    echo "\n";
}
?>
