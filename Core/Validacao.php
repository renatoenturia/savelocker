<?php

class Validacao
{
    protected array $erros = [];

    public static function validar(array $regras, array $post): self
    {
        $validacao = new self();

        foreach ($regras as $campo => $validacoes) {
            $valor = trim($post[$campo] ?? '');

            foreach ($validacoes as $regra) {
                if ($regra === 'required' && $valor === '') {
                    $validacao->erros[$campo] = "O campo {$campo} é obrigatório.";
                    break;
                }

                if ($regra === 'email' && $valor !== '' && ! filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                    $validacao->erros[$campo] = 'Informe um e-mail válido.';
                    break;
                }

                if ($regra === 'confirmed' && $valor !== ($post["{$campo}_confirmacao"] ?? '')) {
                    $validacao->erros[$campo] = 'A confirmação não confere.';
                    break;
                }
            }
        }

        return $validacao;
    }

    public function naoPassou(string $key): bool
    {
        if ($this->erros === []) {
            return false;
        }

        Flash::set($key, $this->erros);

        return true;
    }
}
