<?php

include_once("../src/autoload.php");

$sinusbot = new SinusBot\API("http://127.0.0.1:8087");
$sinusbot->login("admin", "foobar");

$instances = $sinusbot->getInstances();

$instance = $instances[0];

$settings = $instance->getSettings();

$settings["name"] = $settings["nick"]." changed by php";

$instance->setSettings($settings);
