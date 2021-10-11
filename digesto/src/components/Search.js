import {Component, createElement, errorAlert, loadStyle} from "../global/util.js";

loadStyle(`<style id="SearchJs">
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
        display: flex;
        flex-flow: wrap;
    }
    .Search .drop-down-box ul li {
        padding: 10px;
        font-size: 0.9rem;
        width: max-content;
        white-space: nowrap;
    }
    @media (max-width: 768px){
        .Search .drop-down-box {
            width: 100%;
            max-width: 100%;
            top: 45px;
        }
    }
</style>`);

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
                <button type="button" class="btn w-100 text-white">
                    <b class="me-2">Etiquetas</b><span>Todas</span><i class="ms-2 bi-chevron-down"></i>
                </button>
                <div class="drop-down-box p-3 shadow d-none" data-js="drop-box">
                    <ul class="list-unstyled m-0 p-0">
                        <li><input name="tags" type="checkbox" class="me-2">Etiqueta 1</li>
                        <li><input name="tags" type="checkbox" class="me-2">Etiqueta 2</li>
                        <li><input name="tags" type="checkbox" class="me-2">Etiqueta 3</li>
                        <li><input name="tags" type="checkbox" class="me-2">Etiqueta 4</li>
                        <li><input name="tags" type="checkbox" class="me-2">Etiqueta 5</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-auto text-center position-relative">
                <button type="button" class="btn w-100 text-white">
                    <b class="me-2">Año publicacion</b><span>Todos</span> <i class="ms-2 bi-chevron-down"></i>
                </button>
                <div class="drop-down-box p-3 shadow d-none" data-js="drop-box">
                    <ul class="list-unstyled m-0 p-0">
                        <li>Etiqueta1</li>
                        <li>Etiqueta2</li>
                        <li>Etiqueta3</li>
                        <li>Etiqueta4</li>
                        <li>Etiqueta5</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-auto text-center position-relative">
                <button type="button" class="btn w-100 text-white">
                    <b class="me-2">Emisor</b><span>Todos</span> <i class="ms-2 bi-chevron-down"></i>
                </button>
                <div class="drop-down-box p-3 shadow d-none" data-js="drop-box">
                    <ul class="list-unstyled m-0 p-0">
                        <li>Etiqueta1</li>
                        <li>Etiqueta2</li>
                        <li>Etiqueta3</li>
                        <li>Etiqueta4</li>
                        <li>Etiqueta5</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>`);
    const _searchForm = _this.root.querySelector('[data-js="form"]');
    const _buttons = _searchForm.querySelectorAll('button[type="button"]');
    let _currentOpenedDropBox = null;

    /**
     * Constructor
     */
    (function _constructor() {
        _searchForm.onsubmit = _submit;
        Array.from(_buttons).forEach(button => {
            button.onclick = _openBox;
        });
        document.body.addEventListener('click', function () {
            if (_currentOpenedDropBox) _currentOpenedDropBox.classList.add('d-none');
            _currentOpenedDropBox = null;
        });
    }())

    /**
     *
     * @param event
     * @private
     */
    function _openBox(event) {
        event.cancelBubble = true;

        const dropBox = this.parentElement.querySelector('[data-js="drop-box"]');
        dropBox.onclick = function (event) {event.cancelBubble = true;}

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
    function _submit(event) {
        try {
            errorAlert('Sin servicio');
        } catch (error) {
            errorAlert(error);
        }
        return false;
    }
}

Object.setPrototypeOf(Search.prototype, new Component());