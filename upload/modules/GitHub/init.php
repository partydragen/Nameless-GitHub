<?php
/*
 *  Made by Partydragen
 *  https://github.com/partydragen/Nameless-GitHub
 *  https://partydragen.com/
 *  NamelessMC version 2.1.2
 *
 *  GitHub module initialisation file
 */

// Load classes
require_once(ROOT_PATH . '/modules/GitHub/autoload.php');

// Initialise module
require_once(ROOT_PATH . '/modules/GitHub/module.php');
$module = new GitHub_Module($language);