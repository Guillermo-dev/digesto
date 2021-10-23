import {Component, createElement, createStyle, errorAlert} from "../global/js/util.js";

createStyle('DocumentListJs')._content(`
    .DocumentList:not(.css-loaded) .css-loaded,
    .DocumentList:not(.css-loading) .css-loading,
    .DocumentList:not(.css-no-entries) .css-no-entries {
        display: none !important;
    }
    
    .DocumentList {
        padding: 30px 0;
        height: 100%;
    }
    
    .DocumentList .document {
        box-shadow: 0 4px 4px 1px #18363d33;
    }
`);

/**
 *
 * @constructor
 */
export default function DocumentList() {
    const _this = this;
    this.name = "DocumentList";
    this.root = createElement('div')._class('DocumentList')._html(`
        <div class="container css-loaded" data-js="content">
            <!---->
        </div>
        <div class="css-no-entries p-3 text-center h-100 d-flex justify-content-center align-items-center flex-column">
            <img src="/src/global/images/empty-state.svg" class="mb-3" width="150" alt="Sin resultados">
            <p class="text-muted text-center">No se encontraron resultados</p>
        </div>
        <div class="css-loading p-3 text-center h-100 d-flex justify-content-center align-items-center flex-column">
            <span class="spinner-border"></span>
        </div>
    `);

    const _content = _this.root.querySelector('[data-js="content"]');

    /**
     *
     * @param documento
     * @private
     */
    function _appendEntry(documento) {
        const documentoEntry = createElement('div')._html(`
            <div class="p-4 mb-3 border document">
                <div class="row g-2 mb-2">
                    <div class="col">
                        <p class="mb-0 fw-bold">${documento["titulo"]}</p>
                        <p class="text-muted mb-0">${documento["numero"]}</p>
                    </div>
                    <div class="col-auto small"><i class="bi-calendar3 me-2"></i>${documento["fecha"]}</div>
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

        const buttons = documentoEntry.querySelectorAll('[data-js="documentBtn"]');
        buttons[0].onclick = function () {
            location.href = `/documentos/${documento.id}`;
        }

        _content.append(documentoEntry);
    }

    /**
     *
     */
    this.setLoading = function () {
        _this.setClassState('css-loading');
    };

    /**
     *
     */
    this.processDocumentos = function (documentos) {
        if (documentos.length > 0) {
            documentos.forEach(documento => {
                _appendEntry(documento);
            });
            _this.setClassState('css-loaded');
        } else _this.setClassState('css-no-entries');
    };

    /**
     * Constructor
     */
    (function _constructor() {
        _this.setLoading();
    }());
}

Object.setPrototypeOf(DocumentList.prototype, new Component());