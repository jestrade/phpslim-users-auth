<?php
$base = __DIR__ . '/../app/';

$folders = [
    'lib',
    'model',
    'route'
];

foreach($folders as $f)
{
    foreach (glob($base . "$f/*.php") as $filename)
    {
        require $filename;
    }
}