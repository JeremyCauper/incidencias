$(document).ready(function () {
    $('#id_empresa').on('change', function () {
        fillEmpresa($(this).val());
    });

    $('#tip_incidencia').on('change', function () {
        fillProblem($(this).val());
    });

    $('#inc_problem').on('change', function () {
        fillSubProblem($(this).val());
    });

    $('#modal_frm_incidencias').on('hidden.bs.modal', function () {
        changeCodInc(cod_incidencia);
        $(`#content_asig_personal table`).remove();
        $('#form-incidencias').attr('idu', '').attr('frm-accion', '0');
        fillEmpresa("");
        fillProblem("");
        fillSubProblem("");
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

function showEdit(id) {
    $('#modal_frm_incidencias').modal('show');
    $('#form-incidencias .modal-dialog .modal-content').append(`<div class="loader-of-modal" style="position: absolute;height: 100%;width: 100%;z-index: 999;background: #dadada60;border-radius: inherit;align-content: center;"><div class="loader"></div></div>`);

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/show/${id}`,
        contentType: 'application/json',
        success: function (data) {
            $('#form-incidencias .modal-dialog .modal-content .loader-of-modal').remove();
            $('#form-incidencias').attr('idu', id).attr('frm-accion', '1');
            changeCodInc(data.cod_incidencia);
            $('#id_empresa').val(data.id_empresa).trigger('change.select2');
            fillEmpresa(data.id_empresa);
            $('#id_sucursal').val(data.id_sucursal).trigger('change.select2');
            $('#tel_contac').val(data.telefono).trigger('change.select2');
            $('#nro_doc').val(data.nro_doc);
            $('#nom_contac').val(data.nombres);
            $('#car_contac').val(data.cargo).trigger('change.select2');
            $('#cor_contac').val(data.correo);
            $('#tip_estacion').val(data.id_tipo_estacion).trigger('change.select2');
            $('#priori_inc').val(data.prioridad).trigger('change.select2');
            $('#tip_soport').val(data.id_tipo_soporte).trigger('change.select2');
            $('#tip_incidencia').val(data.id_tipo_incidencia).trigger('change.select2');
            fillProblem(data.id_tipo_incidencia);
            $('#inc_problem').val(data.id_problema).trigger('change.select2');
            fillSubProblem(data.id_problema);
            $('#inc_subproblem').val(data.id_subproblema).trigger('change.select2');
            $('#fecha_imforme').val(data.fecha_informe);
            $('#hora_informe').val(data.hora_informe);
            $('#observasion').val(data.observasion);

            (data.personal_asig).forEach(element => {
                $('#selectPersonal').val(element.value).trigger('change.select2');
                tecnicoAsigManenger('create');
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error al registrar el usuario');
            console.log(jqXHR.responseJSON);
        }
    });
}

async function idelete(id) {
    if (!await boxAlert.confirm('¿Esta seguro de elimniar?, no se podrá revertir los cambios')) return true;
    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/destroy/${id}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        success: function (data) {
            if (data.success) {
                boxAlert.minbox('success', data.message, { background: "#3b71ca", color: "#ffffff" }, "top");
                updateTable();
                return true;
            }
            boxAlert.box('error', '¡Ocurrio un error!', data.message);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box('error', '¡Ocurrio un error!', obj_error.message);
            console.log(obj_error);
            $('#form-incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

function showDetail(id) {
    $('#modal_viewdetalle').modal('show');
}

function changeCodInc(val) {
    $('#cod_inc').val(val);
    $('#cod_inc_text').html(val);
}

function fillEmpresa(val) {
    $('#id_sucursal').html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
    if (!val) return false;
    var option = $(`#id_empresa option[value="${val}"]`).attr('select-ruc');
    sucursales[option].forEach(s => {
        $('#id_sucursal').append($('<option>').val(s.id).html(s.sucursal));
    });
    $('#id_sucursal').attr('disabled', false);
}

function fillProblem(val) {
    $('#inc_problem, #inc_subproblem').html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
    if (!val) return false;
    obj_problem.forEach(e => {
        if (e.tipo_incidencia == val)
            $('#inc_problem').append($('<option>').val(e.id).text(e.text));
    });
    $('#inc_problem').attr('disabled', false);
}

function fillSubProblem(val) {
    $('#inc_subproblem').html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
    if (!val) return false;
    obj_subproblem.forEach(e => {
        if (e.id_problema == val)
            $('#inc_subproblem').append($('<option>').val(e.id).text(e.text));
    });
    $('#inc_subproblem').attr('disabled', false);
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