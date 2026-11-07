<div class="hero  min-h-screen flex mx-auto max-w-screen-lg">
  <div class="hero-content ">
    <div>
        <p class="py-2 text-xl">Bem vindo ao </p>
      <h1 class="text-6xl font-bold">SaveLocker</h1>
      <p class="py-2 text-xl">onde você guarda <span class="italic">tudo</span> com segurança</p>
      <?php if (! empty($_SESSION['auth'])): ?>
        <p class="py-2">Olá, <?= htmlspecialchars($_SESSION['auth']['nome']) ?>!</p>
        <a href="/logout" class="btn btn-primary">Sair</a>
      <?php else: ?>
        <a href="/login" class="btn btn-primary">Login</a>
        <a href="/registrar" class="btn btn-link">Registrar</a>
      <?php endif; ?>
    </div>
  </div>
</div>
