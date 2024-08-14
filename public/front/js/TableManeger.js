!function (n) {
    "function" == typeof define && define.amd ? define(["jquery"], n) : "object" == typeof module && module.exports ? module.exports = function (e, t) {
        return void 0 === t && (t = "undefined" != typeof window ? require("jquery") : require("jquery")(e)),
            n(t),
            t
    }
        : n(jQuery)
}(function ($) {
    $.fn.ctableAdmin = function (options) {
        var $s = $.extend({
            repit: false,
            thead: [],
            tbody: []
        }, options);

        this.each(function () {
            var $this = $(this);
            var $content = $this.parent().parent().parent();
            var $select = $content.children('div:nth-child(1)').children('div:nth-child(1)').children('select');
            $content.append($('<div>', { class: 'col-12', css: { overflow: 'auto' } }));

            $this.attr('aria-table', 'create');
            $content.attr('aria-table', 'content');
            $select.attr('aria-table', 'select');
        });

        $('button[aria-table="create"]').on('click', function () {
            var $select = $('select[aria-table="select"]');
            var $content = $('div[aria-table="content"]');
            var $contentTable = $content.children('div:nth-child(2)');
            var $table = $contentTable.children('table');

            if (!$select.val()) return false;
            $option = $select.children(`option[value="${$select.val()}"]`);
            const obj = JSON.parse(atob($option.attr('data-value')));

            if (!$s.repit) {
                if ($table.children('tbody').children(`tr[aria-table="row${obj[$s.tbody[0].data]}"]`).length)
                    return boxAlert.minbox({ i: 'info', h: 'El registro ya existe' }); //boxAlert.minbox('info', '<h6 class="mb-0" style="font-size:.75rem">El registro ya existe</h6>', { background: "#628acc", color: "#ffffff" }, "top");
            }
            if (!$s.thead.length) return alert('no se puede seguir thead no está configurado');
            if (!$s.tbody.length) return alert('no se puede seguir tbody no está configurado');
            if (!$table.length) {
                const tabla = $('<table>', { class: 'table w-100 text-nowrap' }).append($('<thead>').html($('<tr>').html(`<th>${($s.thead).join('</th><th>')}</th><th>Acciones</th>`))).append($('<tbody>'));
                $contentTable.html(tabla);
                $table = $contentTable.children('table');
            }
            const actionDelete = $('<td>').append($('<button>', { class: 'btn btn-danger btn-sm px-2', 'type': 'button', 'aria-table': 'delete' }).html('<i class="far fa-trash-can"></i>'));


            const $tr = $('<tr>', { 'aria-table': `row${obj[$s.tbody[0].data]}` });
            $s.tbody.forEach(col => {
                const $td = $('<td>');
                if (col.render && typeof col.render === 'function') {
                    $td.html(col.render(obj[col.data]));
                } else {
                    $td.html(obj[col.data]);
                }
                $tr.append($td);
            });
            $tr.append(actionDelete);

            $table.children('tbody').append($tr);
            $select.val('').trigger('change.select2');

            $('button[aria-table="delete"]').on('click', function () {
                $(this).parent().parent().remove();
                var $content = $('div[aria-table="content"]');
                var $contentTable = $content.children('div:nth-child(2)');
                var $table = $contentTable.children('table');
                if (!$table.children('tbody').children('tr').length) {
                    $table.remove();
                }
            });
        });
    };
});