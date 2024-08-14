$(document).ready(function () {
    formatSelect('modal_incidencias');
    formatSelect('modal_viewdetalle');
    formatSelect('modal_assign');
    formatSelect('modal_ordens');

    $('#id_empresa').on('change', function () {
        fillEmpresa($(this).val());
    });

    $('#tip_incidencia').on('change', function () {
        fillProblem($(this).val());
    });

    $('#inc_problem').on('change', function () {
        fillSubProblem($(this).val());
    });

    $('.modal').on('hidden.bs.modal', function () {
        changeCodInc(cod_incidencia);
        $(`#content_asig_personal table`).remove();
        $(`#content_asig_personalAssign table`).remove();
        $('#form-incidencias').attr('idu', '').attr('frm-accion', '0');
        fillEmpresa("");
        fillProblem("");
        fillSubProblem("");
        $('#content-seguimiento').html('');
    });

    setInterval(() => {
        let t = new Date();
        const formatD = (t) => { return t.toString().padStart(2, "0") }
        let _time = `${formatD(t.getHours())}:${formatD(t.getMinutes())}:${formatD(t.getSeconds())}`;
        let _date = `${formatD(t.getFullYear())}-${formatD(t.getMonth() + 1)}-${formatD(t.getDate())}`
        $('#fecha_f').val(_date).attr('disabled', true);
        $('#hora_f').val(_time).attr('disabled', true);
    }, 1000);


    $('#createMaterial').ctableAdmin({
        thead: ['#', 'PRODUCTO / MATERIAL', 'CANTIDAD'],
        tbody: [
            { data: 'id' },
            { data: 'producto' },
            { data: 'cantidad', render: function (data) {
                    return `<div class="input-group input-group-sm mb-3">
                                <button type="button" class="btn btn-primary btn-sm px-2" data-mdb-ripple-init><i class="fas fa-minus" style="font-size: .75rem;"></i></button>
                                <input type="number" class="form-control form-control-sm" style="width: 60px;" aria-label="Amount (to the nearest dollar)">
                                <button type="button" class="btn btn-primary btn-sm px-2" data-mdb-ripple-init><i class="fas fa-plus" style="font-size: .75rem;"></i></button>
                            </div>`;
                } 
            }
        ]
    });
});

const tb_incidencia = new DataTable('#tb_incidencia', {
    scrollX: true,
    scrollY: 300,
    ajax: {
        url: `${__url}/soporte/datatable`,
        dataSrc: function (json) {
            $('b[data-panel="_count"]').html(json.count.count);
            $('b[data-panel="_inc_a"]').html(json.count.inc_a);
            $('b[data-panel="_inc_s"]').html(json.count.inc_s);
            $('b[data-panel="_inc_p"]').html(json.count.inc_p);
            return json.data;
        },
        error: function (xhr, error, thrown) {
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: xhr.responseJSON });
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'cod_incidencia' },
        { data: 'id_empresa' },
        { data: 'id_sucursal' },
        { data: 'direccion' },
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
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(9)').addClass('td-acciones');
    },
    processing: true
});

function updateTable() {
    tb_incidencia.ajax.reload();
}

function showEdit(id) {
    $('#modal_incidencias').modal('show');
    $('#modal_incidencias .modal-dialog .modal-content').append(`<div class="loader-of-modal"><div class="gear"><div><label></label><span></span><span></span><span></span><span></span></div></div></div>`);

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/show/${id}`,
        contentType: 'application/json',
        success: function (data) {
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
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
                tecnicoAsigManenger('create', 'selectPersonal', 'content_asig_personal');
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: 'Error al registrar el usuario' });
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
        beforeSend: boxAlert.loading,
        success: function (data) {
            if (data.success) {
                boxAlert.minbox({ h: data.message });
                updateTable();
                return true;
            }
            boxAlert.box('error', '¡Ocurrio un error!', data.message);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: obj_error.message });
            console.log(obj_error);
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

async function reloadInd(cod, estado) {
    if (!await boxAlert.confirm(`¿Esta seguro de <b>${estado == 2 ? 're' : ''}iniciar</b> la incidencia?`)) return true;
    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/initInc/${cod}`,
        data: JSON.stringify({ 'estado': estado }),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        beforeSend: boxAlert.loading,
        success: function (data) {
            if (data.success) {
                boxAlert.minbox({ h: data.message });
                updateTable();
                return true;
            }
            boxAlert.box('error', '¡Ocurrio un error!', data.message);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: obj_error.message });
            console.log(obj_error);
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

function showDetail(e, cod) {
    const tr = e.parentNode.parentNode.parentNode.parentNode;
    const tds = tr.querySelectorAll('td');
    $('#modal_viewdetalle [aria-item="cod"]').html(tds[0].innerHTML);
    $('#modal_viewdetalle [aria-item="empresa"]').html(tds[1].innerHTML);
    $('#modal_viewdetalle [aria-item="direccion"]').html(tds[3].innerHTML);
    $('#modal_viewdetalle [aria-item="sucursal"]').html(tds[2].innerHTML);
    $('#modal_viewdetalle').modal('show');
    $('#modal_viewdetalle .modal-dialog .modal-content').append(`<div class="loader-of-modal"><div class="gear"><div><label></label><span></span><span></span><span></span><span></span></div></div></div>`);

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/detail/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            $('#modal_viewdetalle .modal-dialog .modal-content .loader-of-modal').remove();
            $('#content-seguimiento').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error al registrar el usuario');
            console.log(jqXHR.responseJSON);
        }
    });
}

function assign(e, id) {
    const tr = e.parentNode.parentNode.parentNode.parentNode;
    const tds = tr.querySelectorAll('td');
    $('#modal_assign [aria-item="cod"]').html(tds[0].innerHTML);
    $('#modal_assign [aria-item="empresa"]').html(tds[1].innerHTML);
    $('#modal_assign [aria-item="direccion"]').html(tds[3].innerHTML);
    $('#modal_assign [aria-item="sucursal"]').html(tds[2].innerHTML);

    $('#modal_assign').modal('show');
    $('#modal_assign .modal-dialog .modal-content').append(`<div class="loader-of-modal"><div class="gear"><div><label></label><span></span><span></span><span></span><span></span></div></div></div>`);

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/show/${id}`,
        contentType: 'application/json',
        success: function (data) {
            $('#modal_assign .modal-dialog .modal-content .loader-of-modal').remove();
            (data.personal_asig).forEach(element => {
                $('#selectPersonalAssign').val(element.value).trigger('change.select2');
                tecnicoAsigManenger('create', 'selectPersonalAssign', 'content_asig_personalAssign');
            });
            $('#selectPersonalAssign').val('').trigger('change.select2');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({ i: 'error', t: 'Error al extraer datos de la incidencia', h: obj_error.message });
            console.log(jqXHR.responseJSON);
        }
    });
}

async function createAssign() {
    if (!await boxAlert.confirm('¿Esta seguro de realizar esta accion?')) return true;
    $('#modal_assign .modal-dialog .modal-content').append(`<div class="loader-of-modal"><div class="gear"><div><label></label><span></span><span></span><span></span><span></span></div></div></div>`);

    const cod = $('#modal_assign [aria-item="cod"]').html();
    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/editAssign`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify({
            cod_inc: cod,
            personal_asig: tecnicoAsigManenger('extract', cod, 'content_asig_personalAssign')
        }),
        success: function (data) {
            console.log(data);
            
            $('#modal_assign .modal-dialog .modal-content .loader-of-modal').remove();
            if (data.success) {
                cod_incidencia = data.data.cod_inc;
                boxAlert.minbox({ h: data.message });
                updateTable();
                return true;
            }
            boxAlert.box({ i : 'error', t : '¡Ocurrio un error!', h : data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: obj_error.message });
            console.log(obj_error);
            $('#modal_assign .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

async function createOrden(e, cod) {
    const tr = e.parentNode.parentNode.parentNode.parentNode;
    const tds = tr.querySelectorAll('td');
    $('#modal_ordens [aria-item="cod"]').html(tds[0].innerHTML);
    $('#modal_ordens [aria-item="empresa"]').html(tds[1].innerHTML);
    $('#modal_ordens [aria-item="direccion"]').html(tds[3].innerHTML);
    $('#modal_ordens [aria-item="sucursal"]').html(tds[2].innerHTML);
    $('#modal_ordens [aria-item="registrado"]').html(tds[4].innerHTML);

    $('#modal_ordens').modal('show');
    $('#modal_ordens .modal-dialog .modal-content').append(`<div class="loader-of-modal"><div class="gear"><div><label></label><span></span><span></span><span></span><span></span></div></div></div>`);

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/detailOrden/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            $('#modal_ordens .modal-dialog .modal-content .loader-of-modal').remove();
            $('#modal_ordens [aria-item="tecnicos"]').html(data.tecnicos);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error al registrar el usuario');
            console.log(jqXHR.responseJSON);
        }
    });
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

function tecnicoAsigManenger(accion, select, content, row) {
    switch (accion) {
        case 'create':
            const personal = $(`#${select}`).val();
            if (!personal)
                return false;
            if (!$(`#${content} table`).length) {
                const tabla = $('<table>', { class: 'table w-100 text-nowrap' });
                const thead = $('<thead>').html($('<tr>').html('<th>#</th><th>Nro. Documento</th><th>Nombres y Apellidos</th><th>Acciones</th>'));
                $(`#${content}`).html(tabla.append(thead).append($('<tbody>')));
            }
            const obj = personal.split('|');
            const tr = $('<tr>', { 'aria-row': `reg${obj[0]}`, 'tr-personal': obj[0] }).html(`<td>${obj[0]}</td><td>${obj[1]}</td><td>${obj[2]}</td><td><button type="button" class="btn btn-danger btn-sm px-2"  onclick="tecnicoAsigManenger('delete', '${select}', '${content}', 'reg${obj[0]}')"><i class="far fa-trash-can"></i></button></td>`);

            if ($(`#${content} table tbody tr[aria-row="reg${obj[0]}"]`).length)
                return boxAlert.minbox('info', '<h6 class="mb-0" style="font-size:.75rem">NO PUEDO INGRESAR EL MISMO PERSONAL DOS VECES</h6>', { background: "#628acc", color: "#ffffff" }, "top");
            $(`#${content} table tbody`).append(tr);
            $(`#${select}`).val('').trigger('change.select2');
            break;

        case 'delete':
            $(`#${content} table tbody tr[aria-row="${row}"]`).remove();
            if (!$(`#${content} table tbody tr`).length) {
                $(`#${content} table`).remove();
            }
            break;

        case 'extract':
            let c_ind = select;
            const dataPer = [];
            Array.from($(`#${content} table tbody tr`)).some(function (elemento) {
                const trattr = elemento.getAttribute("tr-personal");
                dataPer.push({ 'cod_incidencia': c_ind, 'id_usuario': trattr });
            });
            return dataPer;
            break;
    }
}