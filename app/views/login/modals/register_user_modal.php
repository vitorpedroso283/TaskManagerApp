<!-- Modal Cadastre-se -->
<div class="modal fade" id="registerModal" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 1050;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registerModalLabel">Cadastre-se</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form id="registerForm" name="registerForm" method="POST">
          <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" autocomplete="off" placeholder="Insira o nome completo" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Telefone</label>
            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Insira seu telefone" maxlength="15" onkeyup="handlePhone(event)" required />

          </div>
          <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email2" name="email" autocomplete="off" placeholder="Insira um e-mail válido" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <input type="password" class="form-control" id="password2" name="password" autocomplete="off" placeholder="Escolha uma senha forte" required>
          </div>
          <button type="submit" class="btn btn-primary btn-register">Cadastrar</button>
        </form>
      </div>
    </div>
  </div>
</div>