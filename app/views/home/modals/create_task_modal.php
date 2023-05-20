<!-- Modal de Criação/Edição de Tarefa -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Criar/Editar Tarefa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <div class="mb-3">
                        <label for="taskName" class="form-label">Nome da Tarefa</label>
                        <input type="text" class="form-control" id="taskName" name="taskName" required>
                    </div>
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Descrição da Tarefa</label>
                        <textarea class="form-control" id="taskDescription" name="taskDescription" required></textarea>
                    </div>
                    <!-- Mais campos da tarefa aqui -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>