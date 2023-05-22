$(document).ready(function () {
    // Iniciar o datatable
    var dataTable = getUsers();

    function getUsers() {
        return $("#usersTable").DataTable({
            dom: 'B<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
            buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
            ajax: {
                url: "/app/routers/accessControlRouter.php",
                type: 'POST',
                data: { action: 'getUsers' },
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
                title: 'ID',
                className: 'text-center' // Adiciona a classe para centralizar o dado

            },
            {
                data: 'name',
                title: 'NOME',
                className: 'text-center' // Adiciona a classe para centralizar o dado

            },
            {
                data: 'email',
                title: 'EMAIL',
                className: 'text-center' // Adiciona a classe para centralizar o dado

            },
            {
                data: 'phone',
                title: 'TELEFONE',
                className: 'text-center' // Adiciona a classe para centralizar o dado

            },
            {
                data: 'created_at',
                title: 'DATA DE CRIAÇÃO',
                className: 'text-center', // Adiciona a classe para centralizar o dado
                render: function (data) {
                    if (data === null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: 'permissions',
                title: 'PERMISSÕES',
                className: 'text-center', // Adiciona a classe para centralizar o dado
                render: function (data, type, row) {
                    var permissions = '';
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            permissions += '<span class="badge bg-success" title="' + data[i].acronym + '">' + data[i].description + '</span> ';
                        }
                    } else {
                        permissions = '-';
                    }
                    return permissions;
                },
                width: '150px', // Defina a largura mínima da coluna
                maxWidth: '200px' // Defina a largura máxima da coluna
            },
            // Renderizar coluna de ações
            {
                title: 'AÇÕES',
                className: 'text-center', // Adiciona a classe para centralizar o dado
                render: function (data, type, row) {
                    return '<div class="dropdown">' +
                        '<button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        'Ações' +
                        '</button>' +
                        '<div class="dropdown-menu">' +
                        '<a class="dropdown-item" id="editUserButton" href="#" data-user-id="' + row.id + '"><i class="fas fa-pencil-alt"></i> Editar Usuário</a>' +
                        '<a class="dropdown-item" id="editPermissionsButton" href="#" data-user-id="' + row.id + '"><i class="fas fa-cogs"></i> Editar Permissões</a>' +
                        '</div>' +
                        '</div>';
                }
            },
            ],
        });
    }

    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');


    // Adicionar evento de clique ao botão "Criar usuário"
    $(document).on('click', '#createUserButton', function () {
        var modal = $('#registerModal');
        modal.find('#registerForm')[0].reset();
        modal.modal('show');
    });

    // Evento de abertura do modal para edição do usuário
    $(document).on('click', '#editUserButton', function () {
        var userId = $(this).data('user-id');
        var modal = $('#editUserModal');

        $.ajax({
            url: '/app/routers/accessControlRouter.php',
            type: 'POST',
            data: { id: userId, action: 'getUserById' },
            success: function (response) {
                if (response) {
                    var user = response.user;
                    modal.find('#name_edit').val(user.name);
                    modal.find('#phone_edit').val(user.phone);
                    modal.find('#email_edit').val(user.email);
                    modal.find('#phone_edit').val(user.phone);
                    modal.attr('data-user-id', userId); // Define o ID do usuário
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

    // Evento submit editar usuário
    $("#editUserForm").submit(function (event) {
        event.preventDefault();
        var url = "/app/routers/accessControlRouter.php";

        // Obtém o ID do usuário do modal
        var userId = $('#editUserModal').attr('data-user-id');
        var formData = new FormData(this);
        formData.append('id', userId); // Adiciona o ID do usuário aos dados enviados
        formData.append('action', 'editUser');
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    $('#editUserModal').modal('hide');
                    sweetAlertToast('success', 'Seu usuário foi editada com sucesso. \nVocê poderá consultar as informações na lista abaixo.');
                    dataTable.ajax.reload();
                    $('#editUserForm')[0].reset();
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

    var userId = '';
    // Evento de abertura do modal para edição de permissões do usuário
    $(document).on('click', '#editPermissionsButton', function () {
        userId = $(this).data('user-id');
        var modal = $('#editPermissionsModal');

        $.ajax({
            url: '/app/routers/accessControlRouter.php',
            type: 'POST',
            data: { id: userId, action: 'getPermissionsByUser' },
            success: function (response) {
                if (response) {
                    var permissions = response.permissions;
                    var userPermissions = response.userPermissions;

                    // Limpar o formulário do modal
                    var form = $('#editPermissionsForm');
                    form.empty();

                    // Dividir as permissões em duas colunas
                    var numPermissions = permissions.length;
                    var numColumns = 2;
                    var permissionsPerColumn = Math.ceil(numPermissions / numColumns);

                    for (var c = 0; c < numColumns; c++) {
                        var column = $('<div>').addClass('column');

                        // Criar checkboxes estilizados para as permissões da coluna atual
                        for (var i = c * permissionsPerColumn; i < (c + 1) * permissionsPerColumn && i < numPermissions; i++) {
                            var permission = permissions[i];
                            var permissionId = permission.id;
                            var isChecked = userPermissions.includes(permissionId);

                            var checkboxWrapper = $('<div>').addClass('checkbox-wrapper');
                            var checkbox = $('<input type="checkbox">')
                                .attr('id', 'permission_' + permissionId)
                                .attr('name', 'permissions[]')
                                .attr('value', permissionId)
                                .prop('checked', isChecked);
                            var label = $('<label>')
                                .attr('for', 'permission_' + permissionId)
                                .text(' ' + permission.description);

                            checkboxWrapper.append(checkbox);
                            checkboxWrapper.append(label);
                            column.append(checkboxWrapper);
                        }

                        form.append(column);
                    }

                    // Adicionar o botão de salvar
                    var saveButton = $('<button>')
                        .attr('type', 'submit')
                        .addClass('btn btn-primary')
                        .text('Salvar');

                    var closeButton = $('<button>')
                        .attr('type', 'button')
                        .addClass('btn btn-secondary')
                        .attr('data-bs-dismiss', 'modal')
                        .text('Fechar');

                    var modalFooter = $('<div>')
                        .addClass('modal-footer')
                        .append(saveButton)
                        .append(closeButton);

                    form.append(modalFooter);


                    // Exibir o modal de edição de permissões
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

    // Evento de envio do formulário de edição de permissões do usuário
    $(document).on('submit', '#editPermissionsForm', function (event) {
        event.preventDefault();

        // var userId = $("#editPermissionsModal").data('user-id');
        var form = $(this);
        var formData = form.serializeArray();

        // Adicione qualquer outro dado necessário ao formData, se necessário

        formData.push({ name: 'id', value: userId });
        formData.push({ name: 'action', value: 'editUserPermissions' });

        $.ajax({
            url: '/app/routers/accessControlRouter.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response) {
                    // Atualizar a exibição ou fazer outras ações necessárias após a atualização das permissões
                    sweetAlertToast('success', 'As permissões do usuário foram atualizada!');
                    dataTable.ajax.reload();
                    // Fechar o modal após a conclusão bem-sucedida
                    $('#editPermissionsModal').modal('hide');

                } else {
                    console.log(response);
                }
            },
            error: function () {
                console.log('Erro na requisição AJAX');
            }
        });
    });


    //evento submit registro de usuario
    $("#registerForm").submit(function (event) {
        event.preventDefault();
        let url = "/app/routers/loginRouter.php";
        let formData = new FormData();
        formData.append("name", $("#name").val());
        formData.append("phone", $("#phone").val());
        formData.append("email", $("#email2").val());
        formData.append("password", $("#password2").val());
        formData.append("action", "register");

        if (!isPasswordStrong(formData.get("password"))) {
            sweetAlertToast(
                "error",
                "A senha deve conter caracteres especiais, números e letras maiúsculas."
            );
        } else {
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        // Registro bem-sucedido
                        $('#registerModal').modal('hide');
                        sweetAlertToast('success', 'Usuário foi criado com sucesso! \n Utilize o e-mail e senha para fazer login!');
                        dataTable.ajax.reload();
                        $('#registerForm')[0].reset();

                    } else {
                        console.log(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 400) {
                        sweetAlertToast('error', 'O email já está em uso!');
                    } else {
                        console.log("Erro na requisição:", error);
                    }
                }
            });
        }
    });
    // Validator de senhas fortes
    function isPasswordStrong(password) {
        // Verifica se a senha contém pelo menos um caractere especial, um número e uma letra maiúscula
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasUpperCase = /[A-Z]/.test(password);
        const isMinLength = password.length >= 8; // comprimento mínimo conforme necessário

        return hasSpecialChar && hasNumber && hasUpperCase && isMinLength;
    }

    // Evento para alternar a visualização da senha no campo de senha do cadastro
    $("#togglePassword").click(function () {
        const passwordInput = $("#password2");
        const passwordFieldType = passwordInput.attr("type");

        // Alternar entre 'password' e 'text' para mostrar ou ocultar a senha
        if (passwordFieldType === "password") {
            passwordInput.attr("type", "text");
            $("#togglePassword i").removeClass("fas fa-eye").addClass("fas fa-eye-slash");
        } else {
            passwordInput.attr("type", "password");
            $("#togglePassword i").removeClass("fas fa-eye-slash").addClass("fas fa-eye");
        }
    });

    // Evento para alternar a visualização da senha no campo de senha do cadastro
    $("#togglePassword2").click(function () {
        const passwordInput = $("#password_edit");
        const passwordFieldType = passwordInput.attr("type");

        // Alternar entre 'password' e 'text' para mostrar ou ocultar a senha
        if (passwordFieldType === "password") {
            passwordInput.attr("type", "text");
            $("#togglePassword2 i").removeClass("fas fa-eye").addClass("fas fa-eye-slash");
        } else {
            passwordInput.attr("type", "password");
            $("#togglePassword2 i").removeClass("fas fa-eye-slash").addClass("fas fa-eye");
        }
    });
});
