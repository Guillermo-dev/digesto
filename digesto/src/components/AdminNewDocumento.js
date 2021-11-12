import {createElement, createStyle} from "../global/js/util.js";
import {Component} from "./Component.js";

// language=CSS
createStyle()._content(`
    .AdminNewDocumento:not(.css-loaded) .css-loaded,
    .AdminNewDocumento:not(.css-loading) .css-loading,
    .AdminNewDocumento:not(.css-error) .css-error {
        display: none !important;
    }

    .cuerpoDoc {
        padding: 20px 0 0 0;
        width: auto;
        margin: auto;
        overflow: hidden;
    }

    .encabezadoDoc {
        background-color: rgb(204, 202, 202);
        color: black;
        width: auto;
        height: 40px;
        vertical-align: middle;
        padding: 5px 0 5px 10px;
    }

    .informacionDoc {
        width: auto;
        padding: 20px 10px 10px 20px;
        background-color: rgb(240, 240, 240);
    }

    .pContenedor {
        display: flex;
    }

    .pContenedor p:first-of-type {
        width: 120px;
    }

    .vistaDoc {
        background-color: rgb(240, 240, 240);
        padding: 10px 10px 10px 10px;
    }

`);

export default function AdminNewDocumento() {
    const _this = this;
    this.name = "AdminNewDocumento";
    this.root = createElement("div")._class("AdminNewDocumento")._html(` 
    <!--Loaded-->
    <div class="container css-loaded">
        <!--[PAO] [PAO] [PAO] [PAO] ACA VA EL PINCHE ACHE-TE-EME-ELE (HTML) [PAO] [PAO] [PAO] [PAO]-->
        <h1 class="display-1">PAO, ACA VA EL CODIGO, POR FAVORE!</h1>
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

    /**
     * Constructor
     * @private
     */
    function _constructor() {
        _this.setClassState('css-loaded');
    }

    //Invoke
    _constructor();
}

Object.setPrototypeOf(AdminNewDocumento.prototype, new Component());
