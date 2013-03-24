<?php
require_once(__DIR__."/../vendor/autoload.php");

use AppConfig\AppConfig,
    AppConfig\Loaders\YamlLoader;

$yfile = __DIR__."/fixtures/config-good.yaml";

//AppConfig::load(new YamlLoader($confFile));


$yaml = yaml_parse_file($yfile);

//print_r($yaml);

$yl = new YamlLoader($yfile);

AppConfig::load($yl);

//print_r(AppConfig::getContainer());

print_r(AppConfig::get('structure', 'apartment.nonexistent.A'));