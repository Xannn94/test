<?php


namespace App;


class FlashMessages
{
    private CONST FLASH_SESSION_KEY = 'flash';
    private static $instance = null;
    private $messages = [];

    private function __construct()
    {
        $this->messages = $_SESSION[self::FLASH_SESSION_KEY];
        $_SESSION[self::FLASH_SESSION_KEY] = [];
    }

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function set($name, $message)
    {
        $_SESSION[self::FLASH_SESSION_KEY][$name] = $message;
    }

    public function get(string $name)
    {
        return isset($this->messages[$name]) ? $this->messages[$name] : null;
    }
}