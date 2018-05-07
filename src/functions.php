<?php

function tstart(string $label)
{
    $GLOBALS['time_measurement'][$label] = microtime(true);
}

function tend(string $label)
{
    $end = microtime(true);
    $time = $end - $GLOBALS['time_measurement'][$label];

    $watch = $label.': '.($time * 1000).' ms';
    if ($GLOBALS['kernel']->getEnvironment() == "dev") {
        dump($watch);
    } else {
        var_dump($watch);
    }
}
