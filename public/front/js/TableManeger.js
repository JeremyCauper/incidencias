class CTable {
    _this = null;
    _selector = null;
    $s = {
        repit: false,
        thead: [],
        tbody: [],
        extract: []
    };
    static _accion = true;
    static _newRow = 1;
    static _data = {};

    constructor(selector, options = {}) {
        this._this = $(selector);
        this._selector = selector;
        this.$s = {
            ...this.$s,
            ...options
        };

        var $contentSelect = this._this.parent();
        var $contentTable = this._this.parent().parent().parent();

        $contentSelect.attr('ctable-conselect', selector).append(
            $('<button>', {
                type: "button",
                class: 'btn btn-primary px-2 ms-1 rounded',
                "ctable-create": selector,
                "data-mdb-ripple-init": ""
            }).html('<i class="fas fa-plus" style="pointer-events: none;"></i>')
        );

        $contentTable.attr('ctable-content', selector).append(
            $('<div>', {
                class: 'col-12',
                "ctable-contable": selector,
                css: { overflow: 'auto' }
            })
        );

        // Agregar el manejador de clics para el botón
        $(`button[ctable-create="${selector}"]`).on('click', () => {            
            this.create();
        });
    }

    get acciones() {
        return CTable._accion;
    }

    set acciones(bol) {
        CTable._accion = bol;
    }

    get newRow() {
        return CTable._newRow;
    }

    set newRow(nw) {
        CTable._newRow = nw;
    }

    get data() {
        return CTable._data;
    }

    set data(dt) {
        CTable._data = dt;
    }

    create() {
        const $selector = this._selector;
        var $select = $($selector);
        var $contentT = $(`[ctable-contable="${$selector}"]`);
        var $table = $(`[ctable-table="${$selector}"]`);

        if (!$select.val()) return false;
        const $option = $select.children(`option[value="${$select.val()}"]`);
        const obj = JSON.parse(atob($option.attr('data-value')));

        if (!this.$s.repit) {
            if ($table.children('tbody').children(`tr[ctable-repit="${obj[this.$s.tbody[0].data]}"]`).length)
                return boxAlert.minbox({ i: 'info', h: 'El registro ya existe' });
        }
        if (!this.$s.thead.length) return alert('no se puede seguir thead no está configurado');
        if (!this.$s.tbody.length) return alert('no se puede seguir tbody no está configurado');
        if (!$table.length) {
            const tabla = $('<table>',
                {
                    class: 'table w-100 text-nowrap',
                    'ctable-table': $selector 
                }
            ).append($('<thead>').html($('<tr>').html(`<th>${(this.$s.thead).join('</th><th>')}</th><th class="text-center">Acciones</th>`))).append($('<tbody>'));
            $contentT.html(tabla);
            $table = $contentT.children('table');
        }
        const extract = this.$s.extract;
        const $id = obj[extract[0]];

        if ($id in this.data) {
            this.data[$id].eliminado = 0;
        } else {
            var obj_td = {};
            extract.forEach(e => {
                obj_td[e] = obj[e];
            });
            obj_td['eliminado'] = 0;
            obj_td['registro'] = this.newRow;
            this.data[$id] = obj_td;
        }

        const $tr = $('<tr>', { 'ctable-table-tr-id': $id, 'ctable-table-tr': this.acciones, 'ctable-table-rnew': this.newRow, 'ctable-repit': obj[this.$s.tbody[0].data] });
        this.$s.tbody.forEach(col => {
            const $td = $('<td>', { 'ctable-table-col': col.data });
            if (col.render && typeof col.render === 'function') {
                $td.html(col.render(obj[col.data]));
            } else {
                $td.html(obj[col.data]);
            }
            $tr.append($td);
        });
        const actionDelete = $('<td>', { class: 'text-center'}).append($('<i>', { class: "far fa-trash-can text-danger", type: "button", onclick: "(new CTable()).delete(this)" }));
        const dontDelete = $('<td>', { class: 'text-center'}).append($('<i>', { class: "fas fa-ban text-primary" }));

        if (this.acciones)
            $tr.append(actionDelete);
        else
            $tr.append(dontDelete);

        $table.children('tbody').append($tr);
        $select.val('').trigger('change.select2');
        this.acciones = true;
        this.newRow = 1;
    }

    delete($this) {
        const $tr = $($this).parent().parent();
        const $table = $tr.parent().parent();
        const $id = $tr.attr('ctable-table-tr-id');
        if (this.data[$id].registro) {
            delete this.data[$id];
        } else {
            this.data[$id].eliminado = 1;
        }
        $tr.remove();
        if (!$table.children('tbody').children('tr').length) {
            $table.remove();
        }
    }

    deleteTable() {
        const $table = $(`table[ctable-table="${this._selector}"]`);
        this.data = {};
        $table.remove();
    }

    fillTable(val, Adel = true) {
        this.acciones = Adel;
        this.newRow = 0;
        $(this._selector).val(val).trigger('change.select2');
        this.create();
    }

    extract() {
        const $selector = this._selector;
        var $select = $($selector);
        if ($select.val()) {
            boxAlert.box({ i: 'warning', t: 'Advertencia!', h: "El selector aun tiene un valor por añadir" });
            return false;
        }
        return this.data;
    }
}
const cTable = new CTable();