import {createElement, createStyle } from "../global/js/util.js";
import {Component} from "./Component.js";

createStyle("DocumentoAmpliadoEstilos")._content(`

    }
    
    .DocumentoAmpliado {
        padding: 30px 0;
        height: 100%;
    }
    
    .DocumentoAmpliado .document {
        box-shadow: 0 4px 4px 1px #18363d33;
    }
    
    .DocumentoAmpliado .more-btn {
        background-color: #f6f6f6;
        cursor: pointer;
        transition: background-color .5s ease-in-out;
    }
    
    .DocumentoAmpliado .more-btn:hover {
        background-color: #e6e8ea;
    }
`);

/**
 *
 * @constructor
 */
//se hace siempre, es el constructor de un componente, en el ROOT VA A IR TODO EL HTML.
export default function DocumentoAmpliado() {
    const _this = this;
    this.name = "DocumentoAmpliado";
    this.root = createElement("div")._class("DocumentoAmpliado")
        ._html(`<div class="cuerpoDoc" id="cuerpoDoc">
    <div class="encabezadoDoc" id="encabezadoDoc">
        <h3> Detalles del Documento </h3>
    </div>
    <div class="informacionDoc" id="informacionDoc">
        <ul>
            <li>Número: </li>
            <li>Título: </li>
            <li>Fecha: </li>
            <li>Descripción: </li>
            <li>Emisor: </li>
            <li>Etiquetas: </li>
        </ul>
    </div>
    <div class="vistaDoc" id="vistaDoc">
    </div>
</div>
`);

    // armar el html acá

    /**
     * Constructor
     */
    (function _constructor() {})();
}

Object.setPrototypeOf(DocumentoAmpliado.prototype, new Component());
