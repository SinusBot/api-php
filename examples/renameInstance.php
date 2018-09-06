<?php

include_once("../src/SinusBot.class.php");

use SinusBot\SinusBot;

$sinusbot = new SinusBot("http://127.0.0.1:8087");
$sinusbot->login("admin", "foobar");

$instances = $sinusbot->getInstances();

$instance = $instances[0];

$settings = $instance->getSettings();

$settings["name"] = $settings["nick"]." changed by php";

$instance->setSettings($settings);