<!-- Modal editar -->
<div class="modal fade" id="editUserModal" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 1050;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registerModalLabel">Editar Usuário</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form id="editUserForm" name="editUserForm" method="POST">
          <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name_edit" name="name" autocomplete="off" placeholder="Insira o nome completo" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Telefone</label>
            <input type="tel" class="form-control" id="phone_edit" name="phone" placeholder="Insira seu telefone" maxlength="15" onkeyup="handlePhone(event)" required />

          </div>
          <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email_edit" name="email" autocomplete="off" placeholder="Insira um e-mail válido" readonly>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <div class="input-group">
              <input type="password" class="form-control" id="password_edit" name="password" autocomplete="off" placeholder="Se não quiser alterar a senha, só deixar em branco.">
              <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-register">Salvar</button>
        </form>
      </div>
    </div>
  </div>
</div>