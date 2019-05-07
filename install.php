<?php

namespace iof;

require __DIR__ . '/vendor/autoload.php';

Config::init();

$scripts = explode(';', file_get_contents(__DIR__.'/db/dbinit.sql'));

foreach ($scripts as $query) {
    $query = trim($query);
    if (!empty($query)) {
        DbProvider::getInstance()->exec( $query );
    }
}

echo "Installation complete!\n";
die();
