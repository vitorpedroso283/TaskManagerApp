
$(document).ready(function () {
    //evento submit login
    $("#loginForm").submit(function (event) {
        event.preventDefault();
        let url = "/app/routers/loginRouter.php";
        let formData = new FormData();
        formData.append("email", $("#email").val());
        formData.append("password", $("#password").val());
        formData.append("action", 'login');
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    // login bem-sucedido
                    window.location.href = '/app/views/home';
                } else {
                    console.log(response.message);
                }
            },
            //pegar os erros
            error: function (xhr, status, error) {
                if (xhr.status === 401) {
                    sweetAlertToast('error', 'Usuário ou senha incorreta!');
                } else {
                    console.log("Erro na requisição:", error);
                }
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
                        sweetAlertToast('success', 'Seu usuário foi criado com sucesso! \n Utilize seu e-mail e senha para fazer login!');

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
        const passwordInput = $("#password");
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

