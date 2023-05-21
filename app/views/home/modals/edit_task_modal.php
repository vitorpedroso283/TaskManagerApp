<!-- Modal de Criação/Edição de Tarefa -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true" data-mode="createTask">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Editar Tarefa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome da Tarefa</label>
                        <input type="text" class="form-control" id="name_edit" name="name" placeholder="Digite o nome da tarefa" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição da Tarefa</label>
                        <textarea class="form-control" id="description_edit" name="description" placeholder="Digite a descrição da tarefa" required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
