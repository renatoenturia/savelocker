<?php

namespace App\Controllers;

class IndexController
{
    public function __invoke(): void
    {
        if (! auth()) {
            header('Location: /login');
            exit();
        }

        header('Location: /dashboard');
        exit();
    }
}
