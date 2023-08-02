$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function obtenerDatos() {
    return $.ajax({
        url: 'vistaTrabajo/tablaResultado', // Aquí debes proporcionar la URL de tu fuente de datos
        method: 'post',
        dataType: 'json',
        data:{id_informe:1 },
    });
}
Antecedentes();
$(".antecedentes").on('click', function(){
    Antecedentes();/// a la funcion hay que agregarle el id del informe
});
$(".minutas").on('click', function(){
    Minuta();
});
$(".prorroga").on('click', function(){
   Oficio();
});
$(".descargos").on('click', function(){
    CarpDescargo();
});
$(".revDescargos").on('click', function(){
    Descargo();
});
$(".actualizaDescargos").on('click', function(){
    ActualizacionDescargo();
});
function Antecedentes(){
    $(".tituloBoton").text('Antecedentes Preliminares');
    $(".infoAdicional").removeClass('oculta');
    $(".contenidoBoton div").remove();
    $.ajax({
       url:'VistaTrabajo/Antecedentes',
       type:'post',
       data:{informe:2},
       success: function(response){
           $(".contenidoBoton").append(response.contenido);
           $("#justificacion").on('click', function(){
               $("#tituloItem").text("JUSTIFICACION");
               $("#campoActualizar").val('justificacion');
               $("#itemInforme").text(response.consulta[0].justificacion);
               $("#modal").modal('show');
           });
           $(".AntGenerales").on('click', function(){
               $("#tituloItem").text("ANTECEDENTES GENERALES");
               $("#campoActualizar").val('antecedentes_generales');
               $("#itemInforme").text(response.consulta[0].antecedentes_generales);
               $("#modal").modal('show');
           });
           $(".Objetivo").on('click', function(){
               $("#tituloItem").text("OBJETIVO");
               $("#campoActualizar").val('objetivo');
               $("#itemInforme").text(response.consulta[0].objetivo);
               $("#modal").modal('show');
           });
           $(".Metodologia").on('click', function(){
               $("#tituloItem").text("METODOLOGÍA");
               $("#campoActualizar").val('metodologia');
               $("#itemInforme").text(response.consulta[0].metodologia);
               $("#modal").modal('show');
           });
           $(".Muestra").on('click', function(){
               $("#tituloItem").text("OBTETIVO Y MUESTRA");
               $("#campoActualizar").val('muestra');
               $("#itemInforme").text(response.consulta[0].muestra);
               $("#modal").modal('show');
           });

        let tabResultado = $("#tablaResultado").DataTable({
               "language": {"url": "vendor/datatables/Spanish.json"},
               "ajax": {
                   "type": "POST",
                   "url": "vistaTrabajo/tablaResultado",
                   "dataType":"json",
                   "data": {id_informe:1},
               },"columns": [
                   {"data": "numero"},
                   {"data": "capitulo"},
                   {"data": "titulo"},
                   {"data": "estamento"},
                   {"data": "acciones"}
               ], "responsive": "true",
               "dom": "<'row'" +
                   "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                   "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                   ">" +
                   "<'table-responsive'tr>" +
                   "<'row'" +
                   "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                   "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                   ">"
           });

        $("#tablaResultado tbody").on('click','a.botonActualizar',function(){
            var info = $(this).data('idtabla');
           console.log(info);
        });
           $("#tablaResultado tbody").on('click','a.botonContinuar',function(){
               var info = $(this).data('idtabla');
               console.log(info);
           });
       }
    });
}

function Minuta(){
    $(".tituloBoton").text('Creación Minuta y Acta');
    $(".infoAdicional").removeClass('oculta');
    $(".contenidoBoton div").remove();
    $.ajax({
        url:'vistaTrabajo/Minuta',
        type:'post',
        data:{},
        success:function(response){
            $(".contenidoBoton").append(response);
        }
    });
}
function Oficio(){
    $(".tituloBoton").text('Efectua Prórroga CGR');
    $(".infoAdicional").addClass('oculta');
    $(".contenidoBoton div").remove();
    $.ajax({
        url:'vistaTrabajo/Oficio',
        type:'post',
        data:{},
        success:function(response){
            $(".contenidoBoton").append(response);
        }
    });
}
function CarpDescargo(){
    $(".tituloBoton").text('Generación Carpeta Descargo');
    $(".infoAdicional").addClass('oculta');
    $(".contenidoBoton div").remove();
    $.ajax({
        url:'vistaTrabajo/CarpetaDescargo',
        type:'post',
        data:{},
        success: function(response){
            $(".contenidoBoton").append(response);
            $("#Estamentos").DataTable({
                "language": {"url": "vendor/datatables/Spanish.json"},
                "columns": [
                    {"data": "estamento"},
                    {"data": "personal"},
                    {"data": "cordinador"},
                    {"data": "acciones"}
                ], "responsive": "true",
                "dom": "<'row'" +
                    "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                    "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                    ">" +
                    "<'table-responsive'tr>" +
                    "<'row'" +
                    "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                    "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                    ">"
            });
        }
    });

}
function Descargo(){
    $(".tituloBoton").text('Revisión de Descargo');
    $(".infoAdicional").addClass('oculta');
    $(".contenidoBoton div").remove();
    $.ajax({
        url:'vistaTrabajo/Descargo',
        type:'post',
        data:{},
        success: function(response){
            $(".contenidoBoton").append(response);
        }
    });
}
function ActualizacionDescargo(){
    $(".tituloBoton").text('Actualizar Información Descargo');
    $(".infoAdicional").addClass('oculta');
    $(".contenidoBoton div").remove();
    $.ajax({
        url:'vistaTrabajo/ActualizacionDescargo',
        type:'post',
        data:{},
        success: function(response){
            $(".contenidoBoton").append(response);
            $("#observaciones").DataTable({
                "language": {"url": "vendor/datatables/Spanish.json"},
                "columns": [
                    {"data": "numero"},
                    {"data": "cpitulo"},
                    {"data": "titulo"},
                    {"data": "agregar"},
                    {"data": "acciones"}
                ], "responsive": "true",
                "dom": "<'row'" +
                    "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                    "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                    ">" +
                    "<'table-responsive'tr>" +
                    "<'row'" +
                    "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                    "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                    ">"
            });
        }
    });
}







