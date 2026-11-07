<?php $erros = Flash::get('login') ?? []; ?>

<div class="min-h-screen grid lg:grid-cols-2">
    <?php require __DIR__ . '/partials/welcome-brand.view.php'; ?>

    <div class="bg-white text-neutral-900 flex items-center justify-center p-10 min-h-[62vh] lg:min-h-screen">
        <div class="w-full max-w-md">
            <h2 class="text-3xl font-bold text-center mb-10">Faça o seu login</h2>

            <?php if (! empty($erros['sucesso'])): ?>
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    <?= htmlspecialchars($erros['sucesso']) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/login" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-neutral-600 mb-2">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="<?= old('email') ?>"
                        class="w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-900 outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 <?= isset($erros['email']) ? 'border-red-400' : '' ?>"
                        required
                    />
                    <?php if (! empty($erros['email'])): ?>
                        <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($erros['email']) ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="senha" class="block text-sm font-medium text-neutral-600 mb-2">Senha</label>
                    <input
                        id="senha"
                        type="password"
                        name="senha"
                        class="w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-900 outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 <?= isset($erros['senha']) ? 'border-red-400' : '' ?>"
                        required
                    />
                    <?php if (! empty($erros['senha'])): ?>
                        <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($erros['senha']) ?></p>
                    <?php endif; ?>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-indigo-300 hover:bg-indigo-400 transition-colors py-3 text-lg font-semibold text-neutral-900"
                >
                    Login
                </button>
            </form>

            <p class="text-center mt-8">
                <a href="/registrar" class="text-indigo-500 font-medium underline underline-offset-2 hover:text-indigo-600">
                    Quero me registrar
                </a>
            </p>
        </div>
    </div>
</div>
