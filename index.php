<?php

ini_set('display_errors',true);

require 'vendor/autoload.php';

$mystifly = new Tsp\Mystifly\SoapClient();
echo "<pre>";

return print_r($mystifly->createSession());
return print_r($mystifly->airLowFareSearch($inputs));
