<?php

use Spatie\Flash\Flash;
use Spatie\Flash\Message;

/**
 * @param string $text
 * @param string|array $class
 * @param null $url
 * @param string $target
 * @return Flash
 */
function flash(string $text = null, $class = null, $url=null, $target='_self'): Flash
{
    /** @var \Spatie\Flash\Flash $flash */
    $flash = app(Flash::class);

    if (is_null($text)) {
        return $flash;
    }

    $message = new Message($text, $class, $url, $target);

    $flash->flash($message);

    return $flash;
}
