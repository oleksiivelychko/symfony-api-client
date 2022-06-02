<?php

use Symfony\Component\Dotenv\Dotenv;

require './vendor/autoload.php';

(new Dotenv())->bootEnv('.env');
