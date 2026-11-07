<?php

function view(string $view, array $params = []): void
{
    extract($params);
    require __DIR__ . '/../views/tamplate/app.php';
}

function old(string $campo): string
{
    return htmlspecialchars($_SESSION['old'][$campo] ?? '', ENT_QUOTES, 'UTF-8');
}

function salvarOld(array $dados): void
{
    $_SESSION['old'] = $dados;
}

function limparOld(): void
{
    unset($_SESSION['old']);
}

function auth(): ?object
{
    if (empty($_SESSION['auth'])) {
        return null;
    }

    return (object) $_SESSION['auth'];
}

function dd(mixed ...$vars): void
{
    foreach ($vars as $var) {
        var_dump($var);
    }

    exit();
}
