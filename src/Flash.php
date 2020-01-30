<?php

namespace Spatie\Flash;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

/** @mixin \Spatie\Flash\Message */
class Flash
{
    use Macroable;

    protected Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function __get(string $name)
    {
        return $this->getMessage()->$name ?? null;
    }

    public function getMessage(): ?Message
    {
        $flashedMessageProperties = $this->session->get('laravel_flash_message');

        if (! $flashedMessageProperties) {
            return null;
        }

        return new Message($flashedMessageProperties['message'], $flashedMessageProperties['class'], $flashedMessageProperties['url'], $flashedMessageProperties['target']);
    }

    public function flash(Message $message): void
    {
        if ($message->class && static::hasMacro($message->class)) {
            $methodName = $message->class;

            $this->$methodName($message->message);

            return;
        }

        $this->flashMessage($message);
    }

    public function flashMessage(Message $message): void
    {
        if (request()->ajax()){

            return response()->json($message->toArray());

        }

        $this->session->flash('laravel_flash_message', $message->toArray());

        if($message->url)
            return redirect($message->url);

        return back();


    }

    public static function levels(array $methodClasses): void
    {
        foreach ($methodClasses as $method => $classes) {
            self::macro($method, fn (string $message) => $this->flashMessage(new Message($message, $classes)));
        }
    }

    public function toArray() :array
    {
        return $this->getMessage()->toArray();
    }
}
