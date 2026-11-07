<?php
/** @var array<string, list<array<string, mixed>>> $notasPorMes */
/** @var array<string, mixed>|null $notaSelecionada */
/** @var string $busca */

$flashDashboard = Flash::get('dashboard') ?? [];
?>
<div class="min-h-screen bg-neutral-200 flex flex-col">
    <header class="bg-neutral-700 text-white border-b-4 border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap items-center gap-3">
            <a href="/dashboard" class="text-lg font-bold shrink-0 hover:text-neutral-200 transition-colors">SaveLocker</a>

            <form action="/dashboard" method="get" class="flex-1 min-w-[200px] max-w-xl">
                <?php if ($notaSelecionada): ?>
                    <input type="hidden" name="nota" value="<?= (int) $notaSelecionada['id'] ?>">
                <?php endif; ?>
                <input
                    type="search"
                    name="q"
                    value="<?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Search..."
                    class="w-full rounded-md border-0 bg-neutral-100 text-neutral-900 px-4 py-2 text-sm placeholder:text-neutral-500 focus:outline-none focus:ring-2 focus:ring-sky-400"
                >
            </form>

            <form action="/notas" method="post" class="shrink-0">
                <button
                    type="submit"
                    class="rounded-md bg-neutral-500 hover:bg-neutral-400 text-white px-4 py-2 text-sm font-medium transition-colors"
                >
                    + Nota
                </button>
            </form>

            <div class="flex items-center gap-2 shrink-0 ml-auto">
                <button
                    type="button"
                    id="toggle-visibilidade"
                    class="p-1 rounded hover:bg-neutral-600 transition-colors"
                    title="Mostrar/ocultar conteúdo sensível"
                    aria-label="Mostrar ou ocultar notas"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </button>

                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="flex items-center gap-1 cursor-pointer py-1 px-2 rounded hover:bg-neutral-600">
                        <span><?= htmlspecialchars(auth()->nome, ENT_QUOTES, 'UTF-8') ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    <ul tabindex="0" class="dropdown-content menu bg-neutral-100 text-neutral-900 rounded-box z-10 w-40 p-2 shadow-lg mt-1">
                        <li class="text-xs text-neutral-500 px-3 py-1 truncate"><?= htmlspecialchars(auth()->email, ENT_QUOTES, 'UTF-8') ?></li>
                        <li><a href="/logout">Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div class="flex-1 max-w-7xl w-full mx-auto flex min-h-0">
        <aside class="w-56 shrink-0 bg-neutral-100 border-r border-neutral-300 overflow-y-auto">
            <?php if ($notasPorMes === []): ?>
                <p class="p-4 text-sm text-neutral-500">Nenhuma nota ainda. Clique em + Nota.</p>
            <?php else: ?>
                <?php foreach ($notasPorMes as $mes => $notasDoMes): ?>
                    <div class="border-b border-neutral-300">
                        <p class="px-3 py-2 text-xs font-semibold text-neutral-600 tracking-wide"><?= htmlspecialchars($mes, ENT_QUOTES, 'UTF-8') ?></p>
                        <ul>
                            <?php foreach ($notasDoMes as $nota): ?>
                                <?php
                                $ativo = $notaSelecionada && (int) $nota['id'] === (int) $notaSelecionada['id'];
                                $href = '/dashboard?nota=' . (int) $nota['id'];
                                if ($busca !== '') {
                                    $href .= '&q=' . urlencode($busca);
                                }
                                ?>
                                <li>
                                    <a
                                        href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"
                                        class="block px-4 py-2 text-sm truncate border-l-4 transition-colors <?= $ativo
                                            ? 'bg-sky-100 border-sky-500 text-neutral-900 font-medium'
                                            : 'bg-white border-transparent text-neutral-700 hover:bg-neutral-50' ?>"
                                    >
                                        <?= htmlspecialchars($nota['titulo'], ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </aside>

        <main class="flex-1 bg-neutral-200 p-6 overflow-y-auto">
            <?php if (! empty($flashDashboard['sucesso'])): ?>
                <div class="max-w-2xl mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    <?= htmlspecialchars($flashDashboard['sucesso'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
            <?php if (! empty($flashDashboard['erro'])): ?>
                <div class="max-w-2xl mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <?= htmlspecialchars($flashDashboard['erro'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($notaSelecionada): ?>
                <form action="/notas/atualizar" method="post" class="max-w-2xl flex flex-col gap-4 h-full">
                    <input type="hidden" name="id" value="<?= (int) $notaSelecionada['id'] ?>">
                    <?php if ($busca !== ''): ?>
                        <input type="hidden" name="q" value="<?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?>">
                    <?php endif; ?>

                    <label class="block">
                        <span class="text-sm font-medium text-neutral-700">Título</span>
                        <input
                            type="text"
                            name="titulo"
                            value="<?= htmlspecialchars($notaSelecionada['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                            required
                            class="mt-1 w-full rounded-md border border-neutral-400 bg-white px-3 py-2 text-neutral-900 focus:outline-none focus:ring-2 focus:ring-sky-400"
                        >
                    </label>

                    <label class="block flex-1">
                        <span class="text-sm font-medium text-neutral-700">Nota</span>
                        <textarea
                            name="nota"
                            rows="12"
                            id="campo-nota"
                            class="mt-1 w-full rounded-md border border-neutral-400 bg-white px-3 py-2 text-neutral-900 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 resize-y min-h-[240px]"
                        ><?= htmlspecialchars($notaSelecionada['nota'], ENT_QUOTES, 'UTF-8') ?></textarea>
                    </label>

                    <div class="flex items-center justify-between gap-4 pt-2">
                        <button
                            type="submit"
                            formaction="/notas/deletar"
                            formmethod="post"
                            name="acao"
                            value="deletar"
                            onclick="return confirm('Deletar esta nota?')"
                            class="rounded-md bg-red-700 hover:bg-red-800 text-white px-5 py-2 text-sm font-medium transition-colors"
                        >
                            Deletar
                        </button>
                        <button
                            type="submit"
                            class="rounded-md bg-neutral-500 hover:bg-neutral-600 text-white px-5 py-2 text-sm font-medium transition-colors"
                        >
                            Atualizar
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="max-w-md text-neutral-600">
                    <p class="text-lg font-medium text-neutral-800 mb-2">Bem-vindo ao SaveLocker</p>
                    <p class="text-sm">Use <strong>+ Nota</strong> para guardar senhas, dados bancários e outras informações com segurança.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
(function () {
    const btn = document.getElementById('toggle-visibilidade');
    const nota = document.getElementById('campo-nota');
    if (!btn || !nota) return;

    let oculto = false;
    btn.addEventListener('click', function () {
        oculto = !oculto;
        nota.style.filter = oculto ? 'blur(6px)' : '';
        nota.style.userSelect = oculto ? 'none' : '';
    });
})();
</script>
