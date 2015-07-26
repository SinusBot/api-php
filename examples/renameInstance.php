<?php
include("sinusbot.class.php");
$sinusbot = new SinusBot("127.0.0.1", 8087);
$sinusbot->login("admin", "foobar");

$daten = array();
$daten["nick"] = "Neuer Nickname";
$sinusbot->editSettings($daten, "6421eedc-9705-4706-a269-cf6f38fa1a33");
?>
