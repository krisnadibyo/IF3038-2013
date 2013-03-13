<?php
function import($classPath)
{
    $classPath = preg_replace('/\./', '/', $classPath);
    require_once dirname(__FILE__) . '/../' . $classPath . '.php';
}

/* For use in CLI testing-debugging */
function println($str)
{
    echo $str . "\n";
}
