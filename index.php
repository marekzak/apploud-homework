<?php

declare(strict_types=1);

const CACHE_DIR = __DIR__ . '/temp/cache';
const LOG_DIR = __DIR__ . '/log';

(require __DIR__ . '/src/bootstrap.php')->run();

function dd($var, $title = NULL, array $options = [])
{
    Tracy\Debugger::barDump($var, $title, $options);
}
