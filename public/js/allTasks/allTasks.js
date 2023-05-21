$(document).ready(function () {
    // Iniciar o datatable
    var dataTable;

    getUserPermissions();
    // Função para obter as permissões do usuário usando AJAX
    function getUserPermissions() {
        $.ajax({
            url: '/app/routers/accessControlRouter.php',
            type: 'POST',
            data: {
                action: 'getUserPermissions'
            },
            success: function (response) {
                if (response) {
                    let userPermissions = response;
                    if (!userPermissions.includes('C')) {
                        $("#createTaskButton").hide();
                    }
                    dataTable = getTasks(userPermissions);
                }
            },
            error: function () {
                console.log('Erro na requisição AJAX');
            }
        });
    }
    function getTasks(userPermissions) {
        console.log(userPermissions);

        // Verificar se possui a permissão 'P' para exibir os botões de exportação
        var buttons = userPermissions.includes('P') ? [
            { extend: 'copy', className: 'btn btn-primary mr-1' },
            { extend: 'csv', className: 'btn btn-primary mr-1' },
            { extend: 'print', className: 'btn btn-primary mr-1' },
            { extend: 'pdf', className: 'btn btn-primary mr-1' },
            { extend: 'excel', className: 'btn btn-primary mr-1' }
        ] : [];

        // Verificar se possui a permissão 'R' antes de chamar a função getTasks
        if (userPermissions.includes('R')) {
            return $("#taskTable").DataTable({
                dom: 'B<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
                buttons: buttons,
                ajax: {
                    url: "/app/routers/taskManagerRouter.php",
                    type: 'POST',
                    data: { action: 'getTasks', callGetTasks: 'all' },
                    dataSrc: function (json) {
                        return json;
                    },
                    error: function (xhr, status, error) {
                        if (xhr.status === 204) {
                            sweetAlertToast('error', 'Sem tarefas cadastradas!');
                        } else {
                            console.log("Erro na requisição:", error);
                        }
                    }
                },
                lengthMenu: [
                    [10, 25, 50, 75, 100, -1],
                    [10, 25, 50, 75, 100, "Todos os Registros"]
                ],
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                    targets: [0],
                    visible: false,
                    searchable: true
                },
                {
                    orderable: true,
                    targets: "_all"
                }
                ],
                columns: [{
                    data: 'id',
                    title: 'ID'
                },
                {
                    data: 'task_name',
                    title: 'NOME DA TAREFA'
                },
                {
                    data: 'description',
                    title: 'DESCRIÇÃO'
                },
                {
                    data: 'created_at',
                    title: 'DATA DE CRIAÇÃO'
                },
                {
                    data: 'finished_at',
                    title: 'DATA DE CONCLUSÃO',
                    render: function (data) {
                        if (data === null) {
                            return '-';
                        } else {
                            return data;
                        }
                    }
                },
                {
                    data: 'status',
                    title: 'STATUS',
                    render: function (data, type, row) {
                        if (data === 0) {
                            return '<span class="btn btn-warning">Pendente</span>';
                        } else if (data === 1) {
                            return '<span class="btn btn-success">Concluído</span>';
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: 'status',
                    title: 'CONCLUÍDO',
                    render: function (data, type, row) {
                        if (data === 1 && userPermissions.includes('A')) {
                            return '<input id="changeStatusButton" data-status="' + data + '" data-task-id="' + row.id + '" type="checkbox" checked>';
                        } else if (data === 1) {
                            return '<i class="fas fa-check"></i>';
                        }
                        else {
                            return '<input id="changeStatusButton" data-status="' + data + '" data-task-id="' + row.id + '" type="checkbox">';
                        }
                    }
                },
                // Renderizar coluna de ações
                {
                    title: 'AÇÕES',
                    render: function (data, type, row) {
                        var actions = '';

                        // Verificar se possui a permissão 'U' para exibir o botão de edição
                        if (userPermissions.includes('U')) {
                            actions += '<a class="dropdown-item" id="editTaskButton" href="#" data-task-id="' + row.id + '"><i class="fas fa-pencil-alt"></i> Editar</a>';
                        }

                        // Verificar se possui a permissão 'D' para exibir o botão de exclusão
                        if (userPermissions.includes('D')) {
                            actions += '<a class="dropdown-item" id="deleteTaskButton" href="#" data-task-id="' + row.id + '"><i class="fas fa-trash"></i> Excluir</a>';
                        }

                        return '<div class="dropdown">' +
                            '<button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                            'Ações' +
                            '</button>' +
                            '<div class="dropdown-menu">' +
                            actions +
                            '</div>' +
                            '</div>';
                    }
                }],
            });
        } else {
            // Permissão 'R' não encontrada, retornar null ou exibir uma mensagem de erro
            return null;
        }
    }


    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');


    // Adicionar evento de clique ao botão "Criar Nova Tarefa"
    $(document).on('click', '#createTaskButton', function () {
        var modal = $('#taskModal');
        modal.find('#taskForm')[0].reset();
        modal.modal('show');
    });

    // Evento de abertura do modal para edição da tarefa
    $(document).on('click', '#editTaskButton', function () {
        var taskId = $(this).data('task-id');
        var modal = $('#editModal');

        $.ajax({
            url: '/app/routers/taskManagerRouter.php',
            type: 'POST',
            data: { id: taskId, action: 'viewTask' },
            success: function (response) {
                if (response.success) {
                    var task = response.task;
                    console.log(task);
                    modal.find('#name_edit').val(task.name);
                    modal.find('#description_edit').val(task.description);
                    modal.attr('data-task-id', taskId); // Define o ID da tarefa
                    modal.modal('show');
                } else {
                    console.log(response.message);
                }
            },
            error: function () {
                console.log('Erro na requisição AJAX');
            }
        });
    });

    // Evento submit editar tarefas
    $("#editForm").submit(function (event) {
        event.preventDefault();
        var url = "/app/routers/taskManagerRouter.php";

        // Obtém o ID da tarefa do modal
        var taskId = $('#editModal').attr('data-task-id');
        var formData = new FormData(this);
        formData.append('id', taskId); // Adiciona o ID da tarefa aos dados enviados
        formData.append('action', 'editTask');
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $('#editModal').modal('hide');
                    sweetAlertToast('success', 'Sua tarefa foi editada com sucesso. \nVocê poderá consultar as informações na lista abaixo.');
                    dataTable.ajax.reload();
                    $('#editForm')[0].reset();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 400) {
                    sweetAlertToast('Erro ao processar!');
                } else {
                    console.log("Erro na requisição:", error);
                }
            }
        });
    });

    // Evento de exclusão da tarefa
    $(document).on('click', '#deleteTaskButton', function () {
        var taskId = $(this).data('task-id');

        // Exibir o diálogo de confirmação antes de excluir a tarefa
        Swal.fire({
            title: 'Você tem certeza de que deseja excluir a tarefa?',
            text: "Não será possível reverter esta ação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Executar a exclusão da tarefa
                $.ajax({
                    url: '/app/routers/taskManagerRouter.php',
                    type: 'POST',
                    data: { id: taskId, action: 'deleteTask' },
                    success: function (response) {
                        if (response.success) {
                            // Tarefa excluída com sucesso
                            sweetAlertToast('success', 'Sua tarefa foi excluída com sucesso.');
                            dataTable.ajax.reload();
                        } else {
                            console.log(response.message);
                        }
                    },
                    error: function () {
                        console.log('Erro na requisição AJAX');
                    }
                });
            }
        });
    });

    // Evento de troca de status
    $(document).on('click', '#changeStatusButton', function () {
        var taskId = $(this).data('task-id');
        var status = $(this).data('status');
        var currentStatus = $(this).prop('checked') ? 1 : 0;
        var confirmButtonText = '';

        if (status === 1) {
            confirmButtonText = 'Sim, voltar para pendente!';
            textStatus = 'A tarefa voltará para a tela de pendêcnias!'
        } else if (status === 0) {
            confirmButtonText = 'Sim, finalizar tarefa!';
            textStatus = 'A ação só poderá ser desfeita por um administrador!'
        }

        Swal.fire({
            title: 'Deseja realmente realizar essa ação?',
            text: textStatus,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText
        }).then((result) => {
            if (result.isConfirmed) {
                // Chamar a função para atualizar o status da tarefa
                updateTaskStatus(taskId, currentStatus);
            } else {
                dataTable.ajax.reload();
            }
        });


        console.log(taskId);
    });
    // evento para atualizar o status da tarefa
    function updateTaskStatus(taskId, status) {
        // Fazer a requisição AJAX para atualizar o status da tarefa no servidor
        $.ajax({
            url: '/app/routers/taskManagerRouter.php',
            type: 'POST',
            data: { id: taskId, status: status, action: 'updateTaskStatus' },
            success: function (response) {
                if (response.success) {
                    // Status da tarefa atualizado com sucesso
                    sweetAlertToast('success', 'Status da tarefa atualizado.');
                    dataTable.ajax.reload();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 400) {
                    sweetAlertToast('Você não possui acesso para atualizar a tarefa!');
                } else {
                    console.log("Erro na requisição:", error);
                }
            }
        });
    }


    // Evento submit registro de tarefas
    $("#taskForm").submit(function (event) {
        event.preventDefault();
        var url = "/app/routers/taskManagerRouter.php";
        var formData = new FormData(this);
        formData.append('action', 'createTask');


        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $('#taskModal').modal('hide');
                    sweetAlertToast('success', 'Sua tarefa foi registrada com sucesso. \nVocê poderá consultar as informações na lista abaixo.');
                    dataTable.ajax.reload();
                    $('#taskForm')[0].reset();
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 400) {
                    sweetAlertToast('Erro ao processar!');
                } else {
                    console.log("Erro na requisição:", error);
                }
            }
        });
    });
});
