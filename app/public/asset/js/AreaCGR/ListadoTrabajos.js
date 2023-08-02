$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$("#TrabajosList").DataTable();
$(".ContinuarTrabajo").on('click', function(){
    document.location.href='vistaTrabajos';
});
$(".modificarTrabajo").on('click', function(){
    alert(' se mostrara modal ');
});
