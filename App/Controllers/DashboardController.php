<?php

namespace App\Controllers;

use App\Models\Nota;

class DashboardController
{
    public function __invoke(): void
    {
        $usuario = $this->requireAuth();

        $busca = trim($_GET['q'] ?? '');
        $notaId = isset($_GET['nota']) ? (int) $_GET['nota'] : null;

        $notas = Nota::listarDoUsuario($usuario->id, $busca);
        $notasPorMes = $this->agruparPorMes($notas);

        $notaSelecionada = null;
        if ($notaId !== null) {
            $notaSelecionada = Nota::buscarDoUsuario($notaId, $usuario->id);
        }

        if ($notaSelecionada === null && $notas !== []) {
            $notaSelecionada = Nota::buscarDoUsuario((int) $notas[0]['id'], $usuario->id);
        }

        view('dashboard', [
            'notasPorMes' => $notasPorMes,
            'notaSelecionada' => $notaSelecionada,
            'busca' => $busca,
        ]);
    }

    public function store(): void
    {
        $usuario = $this->requireAuth();

        $id = Nota::criar($usuario->id);

        header('Location: /dashboard?nota=' . $id);
        exit();
    }

    public function update(): void
    {
        $usuario = $this->requireAuth();

        $id = (int) ($_POST['id'] ?? 0);
        $titulo = trim($_POST['titulo'] ?? '');
        $conteudo = trim($_POST['nota'] ?? '');
        $q = trim($_POST['q'] ?? '');
        $queryBusca = $q !== '' ? '&q=' . rawurlencode($q) : '';

        if ($id <= 0 || $titulo === '') {
            \Flash::set('dashboard', ['erro' => 'Título é obrigatório.']);
            header('Location: /dashboard' . ($q !== '' ? '?q=' . rawurlencode($q) : ''));
            exit();
        }

        if (! Nota::atualizar($id, $usuario->id, $titulo, $conteudo)) {
            \Flash::set('dashboard', ['erro' => 'Não foi possível salvar. Nota não encontrada.']);
            header('Location: /dashboard' . ($q !== '' ? '?q=' . rawurlencode($q) : ''));
            exit();
        }

        \Flash::set('dashboard', ['sucesso' => 'Nota atualizada.']);
        header('Location: /dashboard?nota=' . $id . $queryBusca);
        exit();
    }

    public function destroy(): void
    {
        $usuario = $this->requireAuth();

        $id = (int) ($_POST['id'] ?? 0);
        $q = trim($_POST['q'] ?? '');
        $suffix = $q !== '' ? '?q=' . rawurlencode($q) : '';

        if ($id > 0) {
            Nota::excluir($id, $usuario->id);
            \Flash::set('dashboard', ['sucesso' => 'Nota removida.']);
        }

        header('Location: /dashboard' . $suffix);
        exit();
    }

    private function requireAuth(): object
    {
        if (! auth()) {
            header('Location: /login');
            exit();
        }

        return auth();
    }

    /** @param list<array<string, mixed>> $notas */
    private function agruparPorMes(array $notas): array
    {
        $meses = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO',
        ];

        $agrupados = [];

        foreach ($notas as $nota) {
            $data = new \DateTime($nota['data_criacao']);
            $rotulo = $meses[(int) $data->format('n')] . ' ' . $data->format('Y');
            $agrupados[$rotulo][] = $nota;
        }

        return $agrupados;
    }
}
