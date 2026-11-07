<?php

class Database
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = new PDO('sqlite:' . DATABASE);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->connection->exec('
            CREATE TABLE IF NOT EXISTS usuarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                senha TEXT NOT NULL
            )
        ');
        $this->connection->exec('
            CREATE TABLE IF NOT EXISTS notas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                usuario_id INTEGER NOT NULL,
                titulo TEXT NOT NULL,
                nota TEXT NOT NULL DEFAULT "",
                data_criacao TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                data_atualizacaao TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
            )
        ');

        $this->garantirColunaUsuarioEmNotas();
    }

    private function garantirColunaUsuarioEmNotas(): void
    {
        $colunas = $this->connection->query('PRAGMA table_info(notas)')->fetchAll();
        if ($colunas === []) {
            return;
        }

        $nomes = array_column($colunas, 'name');
        if (! in_array('usuario_id', $nomes, true)) {
            $this->connection->exec('ALTER TABLE notas ADD COLUMN usuario_id INTEGER');
        }
    }

    public function query(string $query, array $params = []): PDOStatement
    {
        $statement = $this->connection->prepare($query);
        $statement->execute($params);

        return $statement;
    }
}
