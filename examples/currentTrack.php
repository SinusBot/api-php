<?php

include_once("../src/autoload.php");

use SinusBot\SinusBot;

$sinusbot = new SinusBot("http://127.0.0.1:8087");
$sinusbot->login("admin", "foobar");

$instances = $sinusbot->getInstances();

foreach ($instances as $instance) {
    $status = $instance->getStatus();
    if ($status["playing"]) {
        echo "Instance: ".$instance->instance["nick"]." is playing";
    } else {
        echo "Instance: ".$instance->instance["nick"]." is not playing";
    }
    echo "\n";
}
?>
