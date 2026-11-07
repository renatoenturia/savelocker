<?php

class Flash
{
    public static function set(string $key, mixed $value): void
    {
        $_SESSION['flash'][$key] = $value;
    }

    public static function get(string $key): mixed
    {
        $value = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);

        return $value;
    }
}
