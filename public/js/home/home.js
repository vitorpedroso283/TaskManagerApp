$(document).ready(function () {
    //iniciar o datatable
    getTasks();

    function getTasks() {
        let formData = {
            action: 'getTasks'
        };

        $("#taskTable").DataTable({
            dom: 'B<"row"<"col-sm-6" l> <"col-sm-6" f>> rtip',
            buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
            ajax: {
                url: "/app/routers/taskManagerRouter.php",
                type: 'POST',
                data: formData,
                dataSrc: function (json) {
                    return json;
                },
                error: function (xhr, status, error) {
                    // Caso ocorra um erro na requisição
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
                [1, 'asc']
            ],
            columnDefs: [{
                targets: [0],
                visible: true,
                searchable: true
            },
            {
                orderable: true,
                targets: "_all"
            }
            ],
            columns: [
                {
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
                    title: 'DATA DE CONCLUSÃO'
                },
                {
                    data: 'status',
                    title: 'STATUS'
                },
            ],
            // configurações da tabela
            initComplete: function () {
                var table = this;
                var filtros = $('#filtros');

                table.api().columns().every(function () {
                    var column = this;
                    var select = $('<select class="select2"><option value="">FILTRO</option></select>')
                        .appendTo($('<th>').appendTo(filtros))
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());

                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>');
                    });
                });

                $(".select2").select2({
                    width: "100%"
                });
            }
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-success mr-1');
    }

});