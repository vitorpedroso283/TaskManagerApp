<!-- Modal editar permissões -->
<div class="modal fade" id="editPermissionsModal" aria-labelledby="editPermissionsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="z-index: 1050;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPermissionsModalLabel">Editar Permissões</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form id="editPermissionsForm" name="editPermissionsForm" method="POST">
          <h3>Permissões do usuário</h3>

          <div class="row">
            <div class="col">
              <h4>Coluna 1</h4>
              <div id="permissionsColumn1" class="column"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>