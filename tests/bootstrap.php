<?php

if (isset($_ENV['BOOTSTRAP_INIT_DATABASE'])) {
    passthru('php bin/console doctrine:schema:drop --force --env=test');
    passthru('php bin/console doctrine:schema:create --env=test');
    passthru('php bin/console doctrine:database:import --env=test app/shared/sql/test/user.sql');
}

require __DIR__.'/../vendor/autoload.php';