<?php

namespace App\Controllers;

class LogoutController
{
    public function __invoke(): void
    {
        unset($_SESSION['auth']);
        limparOld();

        header('Location: /login');
        exit();
    }
}
