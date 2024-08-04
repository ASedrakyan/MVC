<?php

spl_autoload_register(function ($class_name) {
    include(ROOT . str_replace(['/','\\'],DIRECTORY_SEPARATOR,$class_name) . '.php');
});