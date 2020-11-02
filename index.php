<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="datatables/datatables.min.css">

    <title>CRUD</title>
</head>


<body>
    <h1 class="text-center">Registros</h1>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-bordered table-hover" id="tablaarticulos">
                    <thead>
                        <tr>
                            <td>Código</td>
                            <td>Descripción</td>
                            <td>Precio</td>
                            <td>Modificar</td>
                            <td>Borrar</td>
                        </tr>
                    </thead>
                </table>
                <button class="btn btn-sm btn-primary" id="botonagregar">Agregar artículo</button>
            </div>
        </div>
    </div>

    <!-- Formulario (Agregar, Modificar) -->

    <div class="modal fade" id="formularioarticulo" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="codigo">
                    <div class="form-row">
                        <div class="form-group" col-md-12>
                            <label>Descripción</label>
                            <input type="text" id="descripcion" class="form-control" placeholder="">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Precio:</label>
                        <input type="number" id="precio" class="form-control" placeholder="">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="confirmaragregar" class="btn btn-success">Agregar</button>
                    <button type="button" id="confirmarmodificar" class="btn btn-success">Modificar</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let tabla1 = $("#tablaarticulos").DataTable({
                "ajax": {
                    url: "datos.php?accion=listar",
                    dataSrc: ""
                },
                "columns": [{
                        "data": "codigo"
                    },
                    {
                        "data": "descripcion"
                    },
                    {
                        "data": "precio"
                    },
                    {
                        "data": null,
                        "orderable": false
                    },
                    {
                        "data": null,
                        "orderable": false
                    },



                ],

                "columnDefs": [{
                    targets: 3,
                    "defaultContent": "<button class='btn btn-sm btn-warning botonmodificar'>Modificar</button>",
                    data: null
                }, {
                    targets: 4,
                    "defaultContent": "<button class='btn btn-sm btn-danger botonborrar'>Borrar</button>",
                    data: null
                }],
                "language": {
                    "url": "DataTables/spanish.json",
                },
            });

            //Eventos de botones de la aplicación
            $('#botonagregar').click(function() {
                $('#confirmaragregar').show();
                $('#confirmarmodificar').hide();
                limpiarFormulario();
                $("#formularioarticulo").modal('show');
            });

            $('#confirmaragregar').click(function() {
                $("#formularioarticulo").modal('hide');
                let registro = recuperarDatosFormulario();
                agregarRegistro(registro);
            });

            $('#confirmarmodificar').click(function() {
                $("#formularioarticulo").modal('hide');
                let registro = recuperarDatosFormulario();
                modificarRegistro(registro);
            });

            $('#tablaarticulos tbody').on('click', 'button.botonmodificar', function() {
                $('#confirmaragregar').hide();
                $('#confirmarmodificar').show();
                let registro = tabla1.row($(this).parents('tr')).data();
                recuperarRegistro(registro.codigo);
            });

            $('#tablaarticulos tbody').on('click', 'button.botonborrar', function() {
                if (confirm("¿Realmente quiere borrar el artículo?")) {
                    let registro = tabla1.row($(this).parents('tr')).data();
                    borrarRegistro(registro.codigo);
                }
            });

            // funciones que interactuan con el formulario de entrada de datos
            function limpiarFormulario() {
                $('#codigo').val('');
                $('#descripcion').val('');
                $('#precio').val('');
            }

            function recuperarDatosFormulario() {
                let registro = {
                    codigo: $('#codigo').val(),
                    descripcion: $('#descripcion').val(),
                    precio: $('#precio').val()
                };
                return registro;
            }


            // funciones para comunicarse con el servidor via ajax
            function agregarRegistro(registro) {
                $.ajax({
                    type: 'POST',
                    url: 'datos.php?accion=agregar',
                    data: registro,
                    success: function(msg) {
                        tabla1.ajax.reload();
                    },
                    error: function() {
                        alert("Hay un problema");
                    }
                });
            }

            function borrarRegistro(codigo) {
                $.ajax({
                    type: 'GET',
                    url: 'datos.php?accion=borrar&codigo=' + codigo,
                    data: '',
                    success: function(msg) {
                        tabla1.ajax.reload();
                    },
                    error: function() {
                        alert("Hay un problema");
                    }
                });
            }

            function recuperarRegistro(codigo) {
                $.ajax({
                    type: 'GET',
                    url: 'datos.php?accion=consultar&codigo=' + codigo,
                    data: '',
                    success: function(datos) {
                        $('#codigo').val(datos[0].codigo);
                        $('#descripcion').val(datos[0].descripcion);
                        $('#precio').val(datos[0].precio);
                        $("#formularioarticulo").modal('show');
                    },
                    error: function() {
                        alert("Hay un problema");
                    }
                });
            }

            function modificarRegistro(registro) {
                $.ajax({
                    type: 'POST',
                    url: 'datos.php?accion=modificar&codigo=' + registro.codigo,
                    data: registro,
                    success: function(msg) {
                        tabla1.ajax.reload();
                    },
                    error: function() {
                        alert("Hay un problema");
                    }
                });
            }

        });
    </script>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
    <script src="datatables/datatables.min.js"></script>
</body>

</html>