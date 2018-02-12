<?php
ini_set('display_errors', 1);
require(__DIR__.'/../../vendor/autoload.php'); 

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/transcomfy.json');

$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->create();
    