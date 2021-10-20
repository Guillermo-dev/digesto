import {Component, createElement, errorAlert, loadStyle} from "../global/js/util.js";

loadStyle(`<style id="DocumentListJs">
    .DocumentList:not(.css-loaded) .css-loaded,
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
</style>`);

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
    `);
    const _content = _this.root.querySelector('[data-js="content"]');

    /**
     * Constructor
     */
    (function _constructor() {
        _fetchDocuments();
    }());

    /**
     *
     * @private
     */
    function _fetchDocuments() {
        _this.root.classList.add('css-loaded');
        _processDocuments({
            documents: [{
                title: 'Rendición de subsidios de la Universidad Nacional de La Plata',
                number: '296/18',
                description: 'A los efectos de la presente ordenanza se entenderá por subsidio cualquier tipo de asistencia pública o privada, Nacional o Internacional, basada en una ayuda o beneficio de tipo económico con una afectación específica, relacionada con un proyecto a desarrollarse en un plazo determinado y en el ámbito de la UNLP, con un plan de trabajo previsto en el mismo, aprobado por el organismo otorgante y sujeto a rendición.',
                date: '2020/05/15'
            }, {
                title: 'Rendición de subsidios de la Universidad Nacional de La Plata',
                number: '296/18',
                description: 'A los efectos de la presente ordenanza se entenderá por subsidio cualquier tipo de asistencia pública o privada, Nacional o Internacional, basada en una ayuda o beneficio de tipo económico con una afectación específica, relacionada con un proyecto a desarrollarse en un plazo determinado y en el ámbito de la UNLP, con un plan de trabajo previsto en el mismo, aprobado por el organismo otorgante y sujeto a rendición.',
                date: '2020/05/15'
            }, {
                title: 'Rendición de subsidios de la Universidad Nacional de La Plata',
                number: '296/18',
                description: 'A los efectos de la presente ordenanza se entenderá por subsidio cualquier tipo de asistencia pública o privada, Nacional o Internacional, basada en una ayuda o beneficio de tipo económico con una afectación específica, relacionada con un proyecto a desarrollarse en un plazo determinado y en el ámbito de la UNLP, con un plan de trabajo previsto en el mismo, aprobado por el organismo otorgante y sujeto a rendición.',
                date: '2020/05/15'
            }]
        });
    }

    /**
     *
     * @param data
     * @private
     */
    function _processDocuments(data) {
        if (data.documents.length > 0) data.documents.forEach(document => {
            _appendEntry(document);
        });
        else _this.root.classList.replace('css-loaded', 'css-no-entries');
    }

    /**
     *
     * @param document
     * @private
     */
    function _appendEntry(document) {
        const documentEntry = createElement('div')._html(`
            <div class="p-4 mb-3 border document">
                <div class="row g-2 mb-2">
                    <div class="col">
                        <p class="mb-0 fw-bold">${document.title}</p>
                        <p class="text-muted mb-0">${document.number}</p>
                    </div>
                    <div class="col-auto small"><i class="bi-calendar3 me-2"></i>${document.date}</div>
                </div>
                <p class="mb-0">Descripcion</p>
                <p class="mb-0 text-muted">${document.description}</p>
                <div class="text-end">
                    <button type="button" data-js="documentBtn" class="btn btn-primary btn-sm">
                        <i class="bi-search me-2"></i><span>Ver documento</span>
                    </button>
                </div>
            </div>
        `);
        const buttons = documentEntry.querySelectorAll('[data-js="documentBtn"]');
        buttons[0].onclick = function () {
            errorAlert('Sin servicio');
        }
        _content.append(documentEntry);
    }
}

Object.setPrototypeOf(DocumentList.prototype, new Component());