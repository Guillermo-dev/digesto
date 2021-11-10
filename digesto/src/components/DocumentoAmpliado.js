import { createElement, createStyle } from "../global/js/util.js";
import { Component } from "./Component.js";

createStyle("DocumentoAmpliadoEstilos")._content(`

.cuerpoDoc {
    padding: 60px 0;
    width: 90%;
    max-width: 1000px;
    margin: auto;
    overflow: hidden;
}

.encabezadoDoc {
    background-color: grey;
    color: whitesmoke;
    width: auto;
    height: 40px;
    padding: 10px;
}

.informacionDoc {
    background-color: bisque;
    width: auto;
    padding: 10px 20px 10px 20px;
}

.pContenedor {
    display: flex;
}

.pContenedor p:first-of-type {
    width: 120px;
}

.vistaDoc {
    background-color: bisque;
    padding: 10px 20px 10px 20px;
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
    this.root = createElement('div')._class('DocumentoAmpliado')._html(` 
    <div class="cuerpoDoc" id="cuerpoDoc">
        <div class="encabezadoDoc" id="encabezadoDoc">
            <h3> Detalles del Documento </h3>
        </div>
        <div class="informacionDoc" id="informacionDoc">
            <div class="pContenedor">
                <p>Número:</p>
                <p data-js="infoBD">Aca viene de la base de datos</p>
            </div>
            <br>
            <div class="pContenedor">
                <p>Título:</p>
                <p data-js="infoBD">Aca viene de la base de datos</p>
            </div>
            <br>
            <div class="pContenedor">
                <p>Fecha:</p>
                <p data-js="infoBD">Aca viene de la base de datos</p>
            </div>
            <br>
            <div class="pContenedor">
                <p>Descripción:</p>
                <p data-js="infoBD">Aca viene de la base de datos</p>
            </div>
            <br>
            <div class="pContenedor">
                <p>Emisor: </p>
                <p data-js="infoBD">Aca viene de la base de datos</p>
            </div>
            <br>
            <div class="pContenedor">
                <p>Etiquetas:</p>
                <p data-js="infoBD">Aca viene de la base de datos</p>
            </div>
        </div>
    <div class="vistaDoc " id="vistaDoc ">
        <iframe src="../../global/images/AleBombones.pdf " class="frame " frameborder="5 " width="100% " height="680px "> </iframe>
    </div>
`);

    //armar el diseño de las tags
    // armar el js acá

    /* declaro una variable : const parrafos = _this.root.querySelector('[data-js="infoBD"]'); 
    (en la variable parrafos guarda el querySelector busca las etiquetas data-js = infoBD)
    
    FALTA EL COMO TRAIGO DE LA BASE DE DATOS (SIMILAR AL SIGUIENTE CODIGO)
    
     function _fetchData() {
        documentosAPI.getDocumento({}, response => {
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
    
    */

    /**
     * Constructor
     */
    (function _constructor() {})();
}

Object.setPrototypeOf(DocumentoAmpliado.prototype, new Component());
