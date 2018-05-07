<?php

function tstart(string $label)
{
    $GLOBALS['time_measurement'][$label] = microtime(true);
}

function tend(string $label)
{
    $end = microtime(true);
    $time = $end - $GLOBALS['time_measurement'][$label];
    $time *= 1000; // to seconds

    $watch = $label.': '.$time.' ms';
    dump($watch);

//    if ($GLOBALS['kernel']->getEnvironment() == "dev") {
//    } else {
//        var_dump($watch);
//    }
}
