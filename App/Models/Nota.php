<?php

namespace App\Models;

use Core\Crypto;

class Nota
{
    private const COL_ATUALIZACAO = 'data_atualizacaao';

    /** @return list<array<string, mixed>> */
    public static function listarDoUsuario(int $usuarioId, string $busca = ''): array
    {
        global $database;

        $params = ['usuario_id' => $usuarioId];
        // Não traz o corpo cifrado na listagem (só id/título/datas).
        $sql = 'SELECT id, usuario_id, titulo, data_criacao FROM notas WHERE usuario_id = :usuario_id';

        if ($busca !== '') {
            // Conteúdo da nota fica cifrado; busca apenas no título.
            $sql .= ' AND titulo LIKE :busca';
            $params['busca'] = '%' . $busca . '%';
        }

        $sql .= ' ORDER BY data_criacao DESC';

        return $database->query($sql, $params)->fetchAll();
    }

    public static function buscarDoUsuario(int $id, int $usuarioId): ?array
    {
        global $database;

        $nota = $database->query(
            'SELECT * FROM notas WHERE id = :id AND usuario_id = :usuario_id',
            [
                'id' => $id,
                'usuario_id' => $usuarioId,
            ]
        )->fetch();

        if (! $nota) {
            return null;
        }

        return self::comNotaDecifrada($nota, $usuarioId);
    }

    public static function criar(int $usuarioId, string $titulo = 'Nova nota', string $conteudo = ''): int
    {
        global $database;

        $agora = date('Y-m-d H:i:s');
        $notaCifrada = Crypto::encryptNota($conteudo, $usuarioId);

        $database->query(
            'INSERT INTO notas (usuario_id, titulo, nota, data_criacao, ' . self::COL_ATUALIZACAO . ')
             VALUES (:usuario_id, :titulo, :nota, :data_criacao, :data_atualizacao)',
            [
                'usuario_id' => $usuarioId,
                'titulo' => $titulo,
                'nota' => $notaCifrada,
                'data_criacao' => $agora,
                'data_atualizacao' => $agora,
            ]
        );

        return (int) $database->query('SELECT last_insert_rowid() AS id')->fetch()['id'];
    }

    public static function atualizar(int $id, int $usuarioId, string $titulo, string $conteudo): bool
    {
        global $database;

        $notaCifrada = Crypto::encryptNota($conteudo, $usuarioId);

        $statement = $database->query(
            'UPDATE notas SET titulo = :titulo, nota = :nota, ' . self::COL_ATUALIZACAO . ' = :data_atualizacao
             WHERE id = :id AND usuario_id = :usuario_id',
            [
                'titulo' => $titulo,
                'nota' => $notaCifrada,
                'data_atualizacao' => date('Y-m-d H:i:s'),
                'id' => $id,
                'usuario_id' => $usuarioId,
            ]
        );

        return $statement->rowCount() > 0;
    }

    public static function excluir(int $id, int $usuarioId): void
    {
        global $database;

        $database->query(
            'DELETE FROM notas WHERE id = :id AND usuario_id = :usuario_id',
            [
                'id' => $id,
                'usuario_id' => $usuarioId,
            ]
        );
    }

    /** @param array<string, mixed> $linha */
    private static function comNotaDecifrada(array $linha, int $usuarioId): array
    {
        $linha['nota'] = Crypto::decryptNota((string) ($linha['nota'] ?? ''), $usuarioId);

        return $linha;
    }
}
