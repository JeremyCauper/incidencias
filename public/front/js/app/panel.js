$(document).ready(function () {
    $('#id_empresa').on('change', function () {
        $('#id_sucursal').html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
        if (!$(this).val()) return false;
        var option = $('#id_empresa option[value="' + $(this).val() + '"]').attr('select-ruc');
        sucursales[option].forEach(s => {
            $('#id_sucursal').append($('<option>').val(s.id).html(s.sucursal));
        });
        $('#id_sucursal').attr('disabled', false);
    });

    $('#tip_incidencia').on('change', function () {
        $('#inc_problem, #inc_subproblem').html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
        if (!$(this).val()) return false;
        obj_problem.forEach(e => {
            if (e.tipo_incidencia == $(this).val())
                $('#inc_problem').append($('<option>').val(e.id).text(e.text));
        });
        $('#inc_problem').attr('disabled', false);
    });

    $('#inc_problem').on('change', function () {
        $('#inc_subproblem').html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
        if (!$(this).val()) return false;
        obj_subproblem.forEach(e => {
            if (e.id_problema == $(this).val())
                $('#inc_subproblem').append($('<option>').val(e.id).text(e.text));
        });
        $('#inc_subproblem').attr('disabled', false);
    });
});

const tb_incidencia = new DataTable('#tb_incidencia', {
    scrollX: true,
    scrollY: 300,
    ajax: {
        url: `${__url}/soporte/datatable`,
        dataSrc: "",
        error: function (xhr, error, thrown) {
            boxAlert.box('error', 'Ocurrio un error', 'Error en la solicitud Ajax: ' + error);
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'cod_incidencia' },
        { data: 'id_empresa' },
        { data: 'id_sucursal' },
        { data: 'created_at' },
        { data: 'id_tipo_estacion' },
        { data: 'id_tipo_incidencia' },
        {
            data: 'id_problema', render: function (data, type, row) {
                return `${data} / ${row.id_subproblema}`;
            }
        },
        { data: 'estado_informe' },
        { data: 'acciones' }
    ],
    processing: true
});

function updateTable() {
    tb_incidencia.ajax.reload();
}

function tecnicoAsigManenger(accion, row) {
    switch (accion) {
        case 'create':
            const personal = $('#selectPersonal').val();
            if (!personal)
                return false;
            if (!$(`#content_asig_personal table`).length) {
                const tabla = $('<table>', { class: 'table w-100 text-nowrap' });
                const thead = $('<thead>').html($('<tr>').html('<th>#</th><th>Nro. Documento</th><th>Nombres y Apellidos</th><th>Acciones</th>'));
                $('#content_asig_personal').html(tabla.append(thead).append($('<tbody>')));
            }
            const obj = personal.split('|');
            const tr = $('<tr>', { 'aria-row': `reg${obj[0]}`, 'tr-personal': obj[0] }).html(`<td>${obj[0]}</td><td>${obj[1]}</td><td>${obj[2]}</td><td><button type="button" class="btn btn-danger btn-sm px-2"  onclick="tecnicoAsigManenger('delete', 'reg${obj[0]}')"><i class="far fa-trash-can"></i></button></td>`);

            if ($(`#content_asig_personal table tbody tr[aria-row="reg${obj[0]}"]`).length)
                return boxAlert.minbox('info', '<h6 class="mb-0" style="font-size:.75rem">NO PUEDO INGRESAR EL MISMO PERSONAL DOS VECES</h6>', { background: "#628acc", color: "#ffffff" }, "top");
            $(`#content_asig_personal table tbody`).append(tr);
            $('#selectPersonal').val('').trigger('change.select2');
            break;

        case 'delete':
            $(`#content_asig_personal table tbody tr[aria-row="${row}"]`).remove();
            if (!$(`#content_asig_personal table tbody tr`).length) {
                $('#content_asig_personal table').remove();
            }
            break;

        case 'extract':
            const c_ind = $('[name="cod_inc"]').val();
            const dataPer = [];
            Array.from($(`#content_asig_personal table tbody tr`)).some(function (elemento) {
                const trattr = elemento.getAttribute("tr-personal");
                dataPer.push({ 'cod_incidencia': c_ind, 'id_usuario': trattr });
            });
            return dataPer;
            break;
    }
}