$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$("#TrabajosList").DataTable({
    "language": {"url": "vendor/datatables/Spanish.json"},
    "ajax": {
        "type": "POST",
        "url": "vistaTrabajo/listadoTrabajo",
        "dataType":"json",
    },
    "columns": [
        {"data": "numero"},
        {"data": "tipoDoc"},
        {"data": "plazo"},
        {"data": "etapa"},
        {"data": "estado"},
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
$("#TrabajosList tbody").on('click','a.botonActualizar',function(){
    var info = $(this).data('idtabla');
    console.log(info);
});
$("#TrabajosList tbody").on('click','a.botonContinuar',function(){
    var info = $(this).data('idtabla');
    console.log(info);
});
$(".ContinuarTrabajo").on('click', function(){
    document.location.href='vistaTrabajos';
});
$(".modificarTrabajo").on('click', function(){
    alert(' se mostrara modal ');
});
