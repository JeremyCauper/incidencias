$(document).ready(function () {
    const config = {
        "select": {
            minimumResultsForSearch: Infinity
        },
        "search": {},
        "clear": {
            placeholder: '-- Seleccione --',
            allowClear: true
        },
        "tags": {
            tags: true
        }
    };

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

    // Aplica select2 a todos los selects dentro de los modales al mostrarse
    $('.modal').on('shown.bs.modal', function () {
        const modal = $('.modal .modal-content .modal-body');
        modal.find('select').each(function () {
            if ($(this).hasClass('select')) {
                initializeSelect2($(this), config.select, modal);
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
        });
    });
});
