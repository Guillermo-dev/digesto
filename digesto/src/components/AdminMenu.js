import {createElement, createStyle} from "../global/js/util.js";
import {Component} from "./Component.js";

// language=CSS
createStyle()._content(`
    .AdminMenu {

    }
`);

/**
 *
 * @constructor
 */
export function AdminMenu() {
    this.root = createElement('div')._class('AdminMenu')._html(`
        <a class="btn btn-primary bi-people-fill" href="/admin/usuarios"><span class="ms-2">Usuarios</span></a>
    `);
}

Object.setPrototypeOf(AdminMenu.prototype, new Component());