<?php

namespace Spatie\Flash;

class Message
{
    public string $message;

    public ?string $class;

    public ?string $url;

    public ?string $target;

    public function __construct(string $message, $class = null, $url=null, $target=null)
    {
        if (is_array($class)) {
            $class = implode(' ', $class);
        }

        $this->message = $message;

        $this->class = $class;

        $this->url = $url;

        $this->target = $target;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'class' => $this->class,
            'url' => $this->url,
            'target' => $this->target,
        ];
    }
}
