<?php
/*
 *  Made by Partydragen
 *  https://github.com/partydragen/Nameless-GitHub
 *  https://partydragen.com/
 *  NamelessMC version 2.1.2
 *
 *  GitHub module autoload file
 */

// Load classes
spl_autoload_register(function ($class) {
    $path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'modules', 'GitHub', 'classes', $class . '.php']);
    if (file_exists($path)) {
        require_once($path);
    }
});

require_once(ROOT_PATH . '/modules/GitHub/classes/Provider/Github.php');
require_once(ROOT_PATH . '/modules/GitHub/classes/Provider/GithubResourceOwner.php');
require_once(ROOT_PATH . '/modules/GitHub/classes/Provider/Exception/GithubIdentityProviderException.php');