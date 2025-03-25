$(document).ready(function () {
    const config = {
        "select": {
            placeholder: '-- Seleccione --',
            minimumResultsForSearch: Infinity
        },
        "multiple": {
            minimumResultsForSearch: Infinity
        },
        "search": {},
        "clear": {
            placeholder: '-- Seleccione --',
            allowClear: true
        },
        "tags": {
            placeholder: 'Buscar',
            allowClear: true,
            tags: true
        },
        "icons": {
            placeholder: '-- Seleccione --',
            allowClear: true,
            templateResult: iconFormat,
            templateSelection: iconFormat,
            escapeMarkup: function (m) { return m; }
        }
    };

    // Format icon
    function iconFormat(icon) {
        var originalOption = icon.element;
        if (!icon.id) { return icon.text; }
        var valor = icon.text.split('::');
        var $icon = `<i class="${valor[0]}"></i><span>${valor[1]}</span>`;// '<i class="icon-home8"></i>' + icon.text;

        return $icon;
    }

    // Función para inicializar select2
    function initializeSelect2(selectElement, config, modal) {
        selectElement.select2({
            ...config,
            dropdownParent: modal || null
        });
    }

    // Inicialización por defecto
    $('.select').each(function () {
        initializeSelect2($(this), config.select);
    });

    // Inicialización por defecto multiple
    $('.select-multiple').each(function () {
        initializeSelect2($(this), config.multiple);
    });

    // Inicialización con búsqueda
    $('.select-search').each(function () {
        initializeSelect2($(this), config.search);
    });

    // Allow clear selection
    $('.select-clear').each(function () {
        initializeSelect2($(this), config.clear);
    });

    // Allow tags selection
    $('.select-tags').each(function () {
        initializeSelect2($(this), config.tags);
    });

    // Allow icons selection
    $('.select-icons').each(function () {
        initializeSelect2($(this), config.icons);
    });

    // Aplica select2 a todos los selects dentro de los modales al mostrarse
    window.formatSelect = function(mod) {
        $(`#${mod}`).on('shown.bs.modal', function () {
            const modal = $(`#${mod} .modal-content .modal-body`);
            modal.find('select').each(function () {
                if ($(this).hasClass('select')) {
                    initializeSelect2($(this), config.select, modal);
                }
                if ($(this).hasClass('select-multiple')) {
                    initializeSelect2($(this), config.multiple, modal);
                }
                if ($(this).hasClass('select-search')) {
                    initializeSelect2($(this), config.search, modal);
                }
                if ($(this).hasClass('select-clear')) {
                    initializeSelect2($(this), config.clear, modal);
                }
                if ($(this).hasClass('select-tags')) {
                    initializeSelect2($(this), config.tags, modal);
                }
                if ($(this).hasClass('select-icons')) {
                    initializeSelect2($(this), config.icons, modal);
                }
            });
        });
    };

    $('.select-clear, .select-search').on('select2:open', async function() {
        let observador = new MutationObserver((mutations, obs) => {
            let searchField = document.querySelector('input.select2-search__field');
            if (searchField) {
                searchField.focus();
            }
        });

        observador.observe(document.body, { childList: true, subtree: true });
        setTimeout(() => {
            observador.disconnect(); // Desconecta el observador
        }, 50);
    });
});