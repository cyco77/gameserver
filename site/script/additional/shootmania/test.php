<?php

require_once('query.php');

$server = new ShootmaniaQuery();
var_dump($server->getAdditionalData('storn4','','','cyco|77','Test1234'));
