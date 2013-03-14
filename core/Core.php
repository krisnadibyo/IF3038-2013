<?php
function import($classPath)
{
    $classPath = preg_replace('/\./', '/', $classPath);
    $file = dirname(__FILE__) . '/../' . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Import Error `" . $file . "`", 1);
        exit();
    }
}

/* For use in CLI testing-debugging */
function println($str)
{
    echo $str . "\n";
}
