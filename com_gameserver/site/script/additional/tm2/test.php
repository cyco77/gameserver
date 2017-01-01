<?php

require_once('query.php');

$server = new Tm2Query();
var_dump($server->getAdditionalData('ls-server','','','cyco|77','Test1234'));
