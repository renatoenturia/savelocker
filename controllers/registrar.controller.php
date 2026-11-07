<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';

    salvarOld(compact('nome', 'email'));

    $validacao = Validacao::validar([
        'nome' => ['required'],
        'email' => ['required', 'email'],
        'senha' => ['required', 'confirmed'],
    ], $_POST);

    if ($validacao->naoPassou('registrar')) {
        header('Location: /registrar');
        exit();
    }

    $emailExiste = $database->query(
        query: 'SELECT id FROM usuarios WHERE email = :email',
        params: ['email' => $email]
    )->fetch();

    if ($emailExiste) {
        Flash::set('registrar', ['email' => 'Este e-mail já está cadastrado.']);
        header('Location: /registrar');
        exit();
    }

    $database->query(
        query: 'INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)',
        params: [
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT),
        ]
    );

    limparOld();
    Flash::set('login', ['sucesso' => 'Conta criada! Faça login para continuar.']);

    header('Location: /login');
    exit();
}

view('registrar');
