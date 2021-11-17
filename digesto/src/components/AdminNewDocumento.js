import { createElement, createStyle } from "../global/js/util.js";
import { Component } from "./Component.js";

// language=CSS
createStyle()._content(`
    .AdminNewDocumento:not(.css-loaded) .css-loaded,
    .AdminNewDocumento:not(.css-loading) .css-loading,
    .AdminNewDocumento:not(.css-error) .css-error {
        display: none !important;
    }

    .cuerpoDoc {
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

    .contenedor {
        padding: 10px 10px 10px 10px;
        overflow: hidden;
        box-shadow: 0 4px 4px 1px #18363d33;
        width: auto;
        height: auto;
    }

    .cargaDocumento{
        display: flex;
    }

`);

/**
 *
 * @constructor
 */
export default function AdminNewDocumento() {
    const _this = this;
    this.name = "AdminNewDocumento";
    this.root = createElement("div")._class("AdminNewDocumento")._html(` 
    <!--Loaded-->
    <div class="container css-loaded">
        <div class="cuerpoDoc mt-4 border shadow mb-5 bg-white  rounded-top" id="cuerpoDoc">
            <div class="encabezadoDoc " id="encabezadoDoc">
                <h3> Detalles del Documento</h3>
            </div>          
            <div class="contenedor">
                <div class="row">
                    <div class="col-sm>
                        <form>
                            <div class="form-group">
                                <label class="fw-bold mt-2" for="titulo">Título*</label>
                                <input type="text " class="form-control " id="titulo " placeholder="">
                            </div>
                            <div class="form-group">
                                <label class="fw-bold mt-2" for="descripcion">Descripción</label>
                                <textarea  class="form-control " id="descripcion " placeholder=""> </textarea>
                            </div>
                            <div class="form-group ">
                                <label class="fw-bold mt-2" for="numero">Número*</label>
                                <input type="text " class="form-control " id="numero " placeholder="">
                            </div>
                            <div class="form-group ">
                                <label class="fw-bold mt-2" for="campaña">Fecha*</label>
                                <input type="fate" class="form-control " id="fecha " placeholder="">
                            </div> 
                            <div class="form-group ">
                                <label class="fw-bold mt-2" for="campaña">Tipo*</label>
                                <input type="text" class="form-control " id="tipo " placeholder="">
                            </div> 
                            <div class="form-group  ">
                                <label class="fw-bold mt-2" for="campaña">Emisor*</p></label>
                                <select class="form-control form-control-sm p-2">
                                    <option>Rector</option>
                                    <option>Consejo Superior</option>
                                    <option>Otros</option>
                                </select>
                            <div class="form-group ">
                                <label class="fw-bold mt-2" for="campaña">Etiquetas*</label>
                                <input type="text " class="form-control " id="etiquetas " placeholder="">
                                <p>Se recomienda agregar entre 5 y 8 etiquetas por documento. Usar palabras representativas.</p>
                            </div> 

                            <div class="row d-flex">
                                <div class="col-ms">
                                    <div class="form-check form-check-inline">
                                        <label class="fw-bold mt-2" for="privacidad">Privacidad*</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="publico" id="publico" value="publico" checked>
                                        <label class="form-check-label" for="publico"> Público </label>                  
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="Privado" id="Privado" value="Privado">
                                        <label class="form-check-label" for="Privado"> Privado </label>                   
                                    </div>
                                </div>
                               
                                <div class="col-ms">
                                    <div class="form-check form-check-inline">
                                        <label class="fw-bold mt-2" for="Estado">Estado*</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="Descargable" id="Descargable" value="Descargable" checked>
                                            <label class="form-check-label" for="Descargable">Descargable </label>               
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="No descargable" id="No descargable" value="No descargable">
                                            <label class="form-check-label" for="No descargable">No descargable</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cargaDocumento d-grid">
                                <div class="card text-center mt-3">
                                    <div class="card-header fw-bold  bg-secondary bg-opacity-25 ">
                                        Cargar Documento*
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">Seleccione el archivo que desea subir</p>
                                        <input type= "file" name="Upload" >
                                    </div>
                                </div>   
                            </div>
                            <div class="text-end mt-2 ">
                                <button type="submit " class="btn btn-primary">Cancelar</button>
                                <button type="submit " class="btn btn-primary">Guardar</button> 
                            </div>
                        </form>
                    </div>                           
                </div>
            </div>
        </div>
    </div>

   

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