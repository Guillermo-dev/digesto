import {createElement, createStyle} from "../global/js/util.js";
import {Component} from "./Component.js";

// language=CSS
createStyle()._content(`
    html.no-scroll {
        overflow: hidden !important;
    }

    .ModalBox {
        position: fixed;
        width: 100vw;
        height: 100vh;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.84);
        padding: 10px;
        display: flex;
        align-content: center;
        justify-content: center;
        z-index: 999;
        opacity: 0;
        transform: scale(0);
        transition: opacity .3s ease-in-out, transform 0s step-end .3s;
        overflow-y: auto;
    }

    .ModalBox .content {
        border-radius: 5px;
        margin: auto;
        width: 100%;
        max-width: 600px;
        min-height: 100px;
        display: flex;
        flex-flow: column;
        transform: translateY(-50px);
        transition: transform .3s ease-in-out;
    }

    .ModalBox.open {
        opacity: 1;
        transform: scale(1);
        transition: opacity .3s ease-in-out, transform 0s step-end 0s;
    }

    .ModalBox.open .content {
        transform: translateY(0);
    }
`);

/**
 *
 * @param config
 * @constructor
 */
function ModalBox(config = {
    closeOnClick: true
}) {
    let _this = this;
    this.name = 'ModalBox';
    this.root = createElement('div')._class('ModalBox')._html(`
        <div class="content">
            <div class="component" data-js="component">
                <!---->
            </div>
        </div>
    `);
    const _content = _this.root.querySelector('[data-js="component"]');
    let _component = null;

    /**
     * Constructor
     */
    function _constructor() {
        _this.root.onclick = function(e) {
            if (e.target === _this.root && (config.closeOnClick ?? true))
                _close();
        }
        document.body.append(_this.root);
    }

    /**
     *
     * @param callback
     * @private
     */
    function _open(callback = null) {
        _this.root.ontransitionend = function(e) {
            if (e.target !== _this.root) return;
            _this.root.ontransitionend = null;
            if (callback)
                callback();
        }
        document.documentElement.classList.add('no-scroll');
        _this.root.classList.add('open');
    }

    /**
     *
     * @param callback
     * @private
     */
    function _close(callback = null) {
        _this.root.ontransitionend = function(e) {
            if (e.target !== _this.root) return;
            _this.root.ontransitionend = null;
            if (callback)
                callback();
        }
        _this.root.classList.remove('open');
        document.documentElement.classList.remove('no-scroll');
    }

    /**
     *
     * @param component
     * @private
     */
    function _setComponent(component) {
        _content.innerHTML = "";
        _content.append(component.root);
        _component = component;
    }

    /**
     *
     */
    this.open = function(callback = null) {
        _open(callback);
    };

    /**
     *
     */
    this.close = function(callback = null) {
        _close(callback);
    }

    /**
     *
     */
    this.setComponent = function(component) {
        _setComponent(component);
    }

    /**
     *
     */
    this.getComponent = function() {
        return _component;
    }

    /**
     *
     * @param component
     */
    this.swapComponent = function(component) {
        _this.close(() => {
            _setComponent(component);
            _this.open();
        });
    };

    //Invoke constructor
    _constructor();
}

Object.setPrototypeOf(ModalBox.prototype, new Component());

/**
 *
 * @type {ModalBox}
 */
export const modalBox = new ModalBox();