<?php
ini_set('display_errors',true);
require 'vendor/autoload.php';
$mapper     = new Tsp\Mystifly\MystiflyMapper();
$mystifly   = new Tsp\Mystifly\SoapClient();
echo "<pre>";
print_r(
    $mapper->makeResponseCreateSession(
        $mystifly->createSession()
    )
);