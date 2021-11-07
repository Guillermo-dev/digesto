import {createElement, createStyle} from "../global/js/util.js";
import {Component} from "./Component.js";

// language=CSS
createStyle()._content(`
    .Documentos:not(.css-loaded) .css-loaded,
    .Documentos:not(.css-loading) .css-loading,
    .Documentos:not(.css-no-entries) .css-no-entries,
    .Documentos:not(.css-error) .css-error,
    .Documentos:not(.css-more-btn) .css-more-btn {
        display: none !important;
    }

    .Documentos {
        padding: 30px 0;
        height: 100%;
    }

    .Documentos .document {
        box-shadow: 0 4px 4px 1px #18363d33;
    }

    .Documentos .document.private {
        opacity: 0.5;
    }

    .Documentos .more-btn {
        background-color: #f6f6f6;
        cursor: pointer;
        transition: background-color .5s ease-in-out;
    }

    .Documentos .more-btn:hover {
        background-color: #e6e8ea;
    }
`);

/**
 *
 * @constructor
 */
export default function Documentos() {
    const _this = this;
    this.root = createElement('div')._class('Documentos')._html(`
        <!--Loaded-->
        <div class="container css-loaded">
            <div class="" data-js="content"><!----></div>
            <div class="container p-3 text-center more-btn css-more-btn text-secondary" data-js="more-btn">
                <i class="bi-plus-lg me-3"></i>Mostrar mas resultados
            </div>
        </div>
        <!--No entries-->
        <div class="css-no-entries p-3 text-center h-100 d-flex justify-content-center align-items-center flex-column">
            <img src="/src/global/images/empty-state.svg" class="mb-3" width="150" alt="Sin resultados">
            <p class="text-muted text-center">No se encontraron resultados</p>
        </div>
        <!--Error-->
        <div class="css-error p-3 text-center h-100 d-flex justify-content-center align-items-center flex-column">
            <img src="/src/global/images/500.png" class="mb-3" width="400" alt="Sin resultados">
        </div>
        <!--Loading-->
        <div class="css-loading p-3 text-center h-100 d-flex justify-content-center align-items-center flex-column">
            <span class="spinner-border"></span>
        </div>
    `);
    const _content = _this.root.querySelector('[data-js="content"]');
    const _moreBtn = _this.root.querySelector('[data-js="more-btn"]');
    const _paginator = {
        window: 5,
        length: 0,
        i: 0,
    };

    /**
     * Constructor
     */
    function _constructor() {
        _moreBtn.onclick = _paginate;
    }

    /**
     *
     * @private
     */
    function _paginate() {
        let from = _paginator.i;

        let to = from + _paginator.window
        if (to >= _paginator.length)
            to = _paginator.length;

        while (from < to)
            _content.children[from++].classList.remove('d-none');

        _paginator.i = from;

        if (_paginator.i < _paginator.length)
            _this.setClassState('css-more-btn', 1);
        else _this.removeClassState(1);
    }

    /**
     *
     */
    this.setLoading = function() {
        _this.setClassState('css-loading');
    };

    /**
     *
     */
    this.setError = function() {
        _this.setClassState('css-error');
    };

    /**
     *
     */
    this.processDocumentos = function(documentos) {
        _content.innerHTML = "";
        if (documentos.length > 0) {
            documentos.forEach(documento => {
                _content.append(new DocumentoEntry(documento).root);
            });
            _this.setClassState('css-loaded');

            _paginator.length = documentos.length;
            _paginator.i = 0;
            _paginate();
        } else _this.setClassState('css-no-entries');
    };

    /**
     *
     * @param documento
     * @constructor
     */
    function DocumentoEntry(documento) {
        const _this = this;
        this.root = createElement('div')._class('DocumentoEntry')._html(`
            <div class="p-4 mb-3 border document ${documento['publico'] ? '' : 'private'}">
                <div class="row g-2 mb-2">
                    <div class="col">
                        <p class="mb-0 fw-bold">${documento["publico"] ? documento["titulo"] : documento["titulo"] + " [Privado]"}</p>
                        <p class="text-muted mb-0">${documento["numeroExpediente"]}</p>
                    </div>
                    <div class="col-auto small"><i class="bi-calendar3 me-2"></i>${documento["fechaEmision"]}</div>
                </div>
                <p class="mb-0">Descripcion</p>
                <p class="mb-0 text-muted">${documento["descripcion"]}</p>
                <div class="text-end">
                    <button type="button" data-js="documentBtn" class="btn btn-primary btn-sm">
                        <i class="bi-search me-2"></i><span>Ver documento</span>
                    </button>
                </div>
            </div>
        `);
        const buttons = _this.root.querySelectorAll('[data-js="documentBtn"]');

        /**
         * Constructor
         */
        (function _constructor() {
            _this.root.classList.add('d-none');
            buttons[0].onclick = function() {
                location.href = `/documentos/${documento.id}`;
            }
        })()
    }

    //Invoke
    _constructor();
}

Object.setPrototypeOf(Documentos.prototype, new Component());