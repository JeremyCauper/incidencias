class CTable {
    _this = null;
    $s = {
        repit: false,
        thead: [],
        tbody: [],
        extract: []
    };
    static _accion = true;

    constructor(param, options = {}) {
        this._this = $(param);
        const $id = this._this.attr('id');
        this.$s = { ...this.$s, ...options };
        var $content = this._this.parent().parent().parent();
        var $select = $content.children('div:nth-child(1)').children('div:nth-child(1)').children('select');
        $content.append($('<div>', { class: 'col-12', css: { overflow: 'auto' } }));

        this._this.attr('aria-table-create', $id);
        $content.attr('aria-table-content', $id);
        $select.attr('aria-table-select', $id);

        this._this.on('click', (e) => {
            var $button = $(e.target);
            this.create($button);
        });
    }

    get acciones() {
        return CTable._accion;
    }

    set acciones(bol) {
        CTable._accion = bol;
    }

    create($this) {
        const $id = $this.attr('id');
        var $content = $(`div[aria-table-content="${$id}"]`);
        var $select = $(`select[aria-table-select="${$id}"]`);
        var $contentT = $content.children('div:nth-child(2)');
        var $table = $contentT.children('table');

        if (!$select.val()) return false;
        const $option = $select.children(`option[value="${$select.val()}"]`);
        const obj = JSON.parse(atob($option.attr('data-value')));

        if (!this.$s.repit) {
            if ($table.children('tbody').children(`tr[aria-repit="${obj[this.$s.tbody[0].data]}"]`).length)
                return boxAlert.minbox({ i: 'info', h: 'El registro ya existe' });
        }
        if (!this.$s.thead.length) return alert('no se puede seguir thead no está configurado');
        if (!this.$s.tbody.length) return alert('no se puede seguir tbody no está configurado');
        if (!$table.length) {
            const tabla = $('<table>', { class: 'table w-100 text-nowrap', 'aria-table-table': $id }).append($('<thead>').html($('<tr>').html(`<th>${(this.$s.thead).join('</th><th>')}</th><th class="text-center">Acciones</th>`))).append($('<tbody>'));
            $contentT.html(tabla);
            $table = $contentT.children('table');
        }

        const $tr = $('<tr>', { 'aria-table-tr': this.acciones, 'aria-repit': obj[this.$s.tbody[0].data] });
        this.$s.tbody.forEach(col => {
            const $td = $('<td>', { 'aria-item': col.data });
            if (col.render && typeof col.render === 'function') {
                $td.html(col.render(obj[col.data]));
            } else {
                $td.html(obj[col.data]);
            }
            $tr.append($td);
        });
        const actionDelete = $('<td>', { class: 'text-center'}).append($('<i>', { class: 'far fa-trash-can text-danger', 'type': 'button', 'onclick': 'cTable.delete(this)' }));
        const dontDelete = $('<td>', { class: 'text-center'}).append($('<i>', { class: 'fas fa-ban text-primary' }));

        if (this.acciones)
            $tr.append(actionDelete);
        else
            $tr.append(dontDelete);

        $table.children('tbody').append($tr);
        $select.val('').trigger('change.select2');
        this.acciones = true;
    }

    delete($this) {
        const $tr = $($this).parent().parent();
        const $table = $tr.parent().parent();
        $tr.remove();
        if (!$table.children('tbody').children('tr').length) {
            $table.remove();
        }
    }

    deleteTable(id) {
        const $id = $(id).attr('id');
        const $table = $(`table[aria-table-table="${$id}"]`);
        $table.remove();
    }

    fillTable(id, value, Adel = true) {
        const $button = $(id);
        const $id = $button.attr('id');
        this.acciones = Adel;

        const $select = $(`select[aria-table-select="${$id}"]`);
        $select.val(value).trigger('change.select2');
        $button.trigger('click');
    }

    extract() {
        const arr = this.$s.extract;
        const $id = $(this._this).attr('id');
        const $trs = $(`table[aria-table-table="${$id}"]`).children('tbody').children('tr');
        const data = [];
        $trs.each(function (i, t) {
            if ($(t).attr('aria-table-tr') === 'true') {
                const tds = $(t).children('td');
                var obj_td = {};

                arr.forEach(e => {
                    tds.each(function (i, td) {
                        if ($(td).attr('aria-item') == e) {
                            obj_td[e] = $(td).text();
                        }
                    });
                });
                data.push(obj_td);
            }
        });
        return data;
    }
}
const cTable = new CTable();