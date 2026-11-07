<?php

namespace App\Controllers;

class LoginController
{
    public function index(): void
    {
        view('login');
    }

    public function login(): void
    {
        global $database;

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        salvarOld(['email' => $email]);

        $validacao = \Validacao::validar([
            'email' => ['required', 'email'],
            'senha' => ['required'],
        ], $_POST);

        if ($validacao->naoPassou('login')) {
            header('Location: /login');
            exit();
        }

        $usuario = $database->query(
            query: 'SELECT * FROM usuarios WHERE email = :email',
            params: [
                'email' => $email,
            ]
        )->fetch();

        if (! $usuario || ! password_verify($senha, $usuario['senha'])) {
            \Flash::set('login', ['email' => 'E-mail ou senha incorretos.']);
            header('Location: /login');
            exit();
        }

        limparOld();

        $_SESSION['auth'] = [
            'id' => $usuario['id'],
            'nome' => $usuario['nome'],
            'email' => $usuario['email'],
        ];

        header('Location: /dashboard');
        exit();
    }
}
