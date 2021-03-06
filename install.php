<?php

require __DIR__ . '/vendor/autoload.php';

$files = ['db/dbinit.sql', 'db/countries.sql', 'db/continents.sql'];

foreach ($files as $filename) {

    $scripts = explode( ';', file_get_contents( __DIR__ . '/' . $filename ) );

    foreach ( $scripts as $query ) {
        $query = trim( $query );
        if ( ! empty( $query ) ) {
            controllers\DbProvider::getInstance()->exec( $query );
        }
    }

}

echo "Installation complete!\n";
die();
