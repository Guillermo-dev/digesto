import {createElement, createStyle, errorAlert} from "../global/js/util.js";
import {DocumentosAPI, EmisoresAPI, TagsAPI} from "./Api.js";
import {Component} from "./Component.js";

const documentosAPI = new DocumentosAPI();
const emisoresAPI = new EmisoresAPI();
const tagsAPI = new TagsAPI();

createStyle('SearchJs')._content(`
    .Search button, .Search input, .Search .input-group-text {
        font-size: 0.9rem !important;
    }
    
    .Search .drop-down-box {
        position: absolute;
        top: 60px;
        left: 0;
        right: 0;
        background-color: #f6f6f6;
        color: black;
        z-index: 5;
        width: 100vw;
        max-width: 500px;
    }
    
    .Search .drop-down-box ul {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(50px,1fr));
    }
    
    .Search .drop-down-box ul li {
        padding: 10px;
        font-size: 0.9rem;
        overflow: hidden;
        white-space: nowrap;
        text-align: left;
    }
    
    @media (max-width: 768px){
        .Search .drop-down-box {
            width: 100%;
            max-width: 100%;
            top: 45px;
        }
    }
`);

/**
 *
 * @constructor
 */
export default function Search() {
    const _this = this;
    this.name = "Search";
    this.root = createElement('div')._class('Search')._html(`<div class="container p-3 position-relative">
    <form data-js="form" class="position-relative">
        <div class="row g-2">
            <div class="col-md-auto">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Buscar resoluciones..." name="search" required>
                    <button type="submit" class="btn btn-secondary"><i class="bi-search"></i></button>
                </div>
            </div>
            <div class="col-md-auto text-center position-relative">
                <button type="button" name="tagBtn" class="btn w-100 text-white">
                    <b class="me-2">Etiquetas</b>
                    <span data-js="option-display">Todas</span>
                    <i class="ms-2 bi-chevron-down"></i>
                </button>
                <div class="drop-down-box p-3 shadow d-none" data-js="drop-box">
                    <ul class="list-unstyled m-0 p-0">
                        <!--dynamic loading-->
                    </ul>
                </div>
            </div>
            <div class="col-md-auto text-center position-relative">
                <button type="button" name="yearBtn" class="btn w-100 text-white">
                    <b class="me-2">Año publicacion</b>
                    <span data-js="option-display">Todos</span>
                    <i class="ms-2 bi-chevron-down"></i>
                </button>
                <div class="drop-down-box p-3 shadow d-none" data-js="drop-box">
                    <ul class="list-unstyled m-0 p-0">
                        <!--dynamic loading-->
                    </ul>
                </div>
            </div>
            <div class="col-md-auto text-center position-relative">
                <button type="button" name="emitterBtn" class="btn w-100 text-white">
                    <b class="me-2">Emisor</b>
                    <span data-js="option-display">Todos</span>
                    <i class="ms-2 bi-chevron-down"></i>
                </button>
                <div class="drop-down-box p-3 shadow d-none" data-js="drop-box">
                    <ul class="list-unstyled m-0 p-0">
                        <!--dynamic loading-->
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>`);
    const _lists = _this.root.querySelectorAll('[data-js="drop-box"] ul');
    const _searchForm = _this.root.querySelector('[data-js="form"]');
    const _buttons = _searchForm.querySelectorAll('button[type="button"]');
    const _selectedText = _searchForm.querySelectorAll('[data-js="option-display"]');
    let _currentOpenedDropBox = null;
    let _documentList = null;
    let _hash = null;

    /**
     * Constructor
     */
    function _constructor() {
        _searchForm.onsubmit = _submit;
        Array.from(_buttons).forEach(button => {
            button.onclick = _openDropBox;
        });
        document.body.addEventListener('click', function() {
            if (_currentOpenedDropBox) _currentOpenedDropBox.classList.add('d-none');
            _currentOpenedDropBox = null;
        });
        _fetchData();
    }

    /**
     *
     * @param event
     * @private
     */
    function _openDropBox(event) {
        event.cancelBubble = true;

        const dropBox = this.parentElement.querySelector('[data-js="drop-box"]');
        dropBox.onclick = function(event) {
            event.cancelBubble = true;
        }

        if (dropBox.classList.contains('d-none')) {
            dropBox.classList.remove('d-none');
            if (_currentOpenedDropBox && _currentOpenedDropBox !== dropBox)
                _currentOpenedDropBox.classList.add('d-none');
            _currentOpenedDropBox = dropBox;
        } else {
            dropBox.classList.add('d-none');
            _currentOpenedDropBox = null;
        }
    }

    /**
     *
     * @param event
     * @private
     */
    function _selectOption(event) {

    }

    /**
     *
     * @private
     */
    function _fetchData() {
        tagsAPI.getTags({}, response => {
            if (response.status === 'success') {
                _processData(response.data);
            } else {
                errorAlert(response.error.message);
            }
        }, error => {
            errorAlert(error);
        });
        emisoresAPI.getEmisores({}, response => {
            if (response.status === 'success') {
                _processData(response.data);
            } else {
                errorAlert(response.error.message);
            }
        }, error => {
            errorAlert(error);
        });
    }

    /**
     *
     * @param data
     * @private
     */
    function _processData(data) {
        if (data["tags"] !== undefined)
            data["tags"].forEach(tag => {
                const tagEntry = createElement('li')._html(`
                <input name="tag" type="checkbox" class="me-2" value="${tag["nombre"]}"> ${tag["nombre"]}
            `);
                tagEntry.firstElementChild.onclick = _selectOption;
                _lists[0].append(tagEntry);
            });

        if (data["years"] !== undefined)
            data["years"].forEach(year => {
                const yearEntry = createElement('li')._html(`
                <input name="year" type="checkbox" class="me-2" value="${year}"> ${year}
            `);
                yearEntry.firstElementChild.onclick = _selectOption;
                _lists[1].append(yearEntry);
            });

        if (data["emisores"] !== undefined)
            data["emisores"].forEach(emisor => {
                const emitterEntry = createElement('li')._html(`
                <input name="emitter" type="checkbox" class="me-2" value="${emisor["nombre"]}"> ${emisor["nombre"]}
            `);
                emitterEntry.firstElementChild.onchange = _selectOption;
                _lists[2].append(emitterEntry);
            });
    }

    /**
     *
     * @private
     */
    function _fetchDocumentos() {
        _documentList.setLoading();
        documentosAPI.getDocumentos({}, response => {
            if (response.status === 'success') {
                _documentList.processDocumentos(response.data["documentos"]);
            } else {
                _documentList.setError();
            }
        }, error => {
            _documentList.setError();
        });
    }

    /**
     *
     * @param event
     * @private
     */
    function _submit(event) {
        try {
            const url = new URLSearchParams();

            const tags = [];
            const years = [];
            const emitters = [];

            _searchForm['tag'].forEach(option => {
                if (option.checked) {
                    tags.push(option.value);
                }
            });
            _searchForm['year'].forEach(option => {
                if (option.checked) {
                    years.push(option.value);
                }
            });
            _searchForm['emitter'].forEach(option => {
                if (option.checked) {
                    emitters.push(option.value);
                }
            });

            if (_searchForm['search'].value.length > 0)
                url.append('search', _searchForm['search'].value);
            else return false;

            if (tags.length > 0)
                url.append('tags', tags.join(';'));
            if (years.length > 0)
                url.append('years', years.join(';'));
            if (emitters.length > 0)
                url.append('emisor', emitters.join(';'));

            if (btoa(url.toString()) === _hash) return false;

            _hash = btoa(url.toString());

            history.pushState(null, '', '/?' + url.toString());
        } catch (error) {
            errorAlert(error);
        }
        return false;
    }

    /**
     *
     * @param documentList
     */
    this.setDocumentList = function(documentList) {
        _documentList = documentList;
        _fetchDocumentos();
    };

    _constructor();
}

Object.setPrototypeOf(Search.prototype, new Component());