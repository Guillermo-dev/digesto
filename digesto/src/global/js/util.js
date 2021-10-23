import "../../../node_modules/sweetalert2/dist/sweetalert2.min.js";

/**
 *
 * @param tagName
 * @returns {*}
 */
export function createElement(tagName) {
    /**
     *
     * @type {*}
     */
    const element = document.createElement(tagName);
    element._id = function (id) {
        element.id = id;
        return element;
    }
    element._class = function () {
        Array.from(arguments).forEach(argument => {
            element.classList.add(argument);
        });
        return element;
    }
    element._html = function (html) {
        element.innerHTML = html;
        return element;
    }
    return element;
}

/**
 *
 * @param style
 */
export function loadStyle(style) {
    document.head.append(createElement('head')._html(style).firstElementChild);
}

/**
 *
 * @param id
 * @returns {*}
 */
export function createStyle(id) {
    const style = document.createElement('style');
    style.id = id;
    style._content = function (content) {
        style.innerHTML = content;
        document.head.append(style);
    };
    return style;
}

/**
 *
 * @param message
 */
export function successAlert(message) {
    Sweetalert2.fire({text: message, icon: 'success'});
}

/**
 *
 * @param message
 */
export function errorAlert(message) {
    Sweetalert2.fire({text: message, icon: 'error'});
}

/**
 *
 * @constructor
 */
export function Component() {
    this.name = 'Component';
    this.root = null;
    this.classStates = [];
}

/**
 *
 * @param id
 */
Component.prototype.append = function (id) {
    const parent = document.getElementById(id);
    if (parent && this.root) parent.append(this.root);
    else console.error(`Cannot append component [${this.name}] in parent [${id}]`);
}

/**
 *
 * @param state
 * @param id
 * @returns {boolean}
 */
Component.prototype.setClassState = function (state, id = 0) {
    if (!this.root) return false;
    if (this.classStates[id] !== undefined && this.classStates[id].length > 0)
        this.root.classList.replace(this.classStates[id], state);
    else this.root.classList.add(state);
    this.classStates[id] = state;
    return true;
}

/**
 *
 * @param id
 * @returns {boolean}
 */
Component.prototype.removeClassState = function (id = 0) {
    if (!this.root) return false;
    if (this.classStates[id] === undefined || this.classStates[id].length === 0) return false;
    this.root.classList.remove(this.classStates[id]);
    delete this.classStates[id];
    return true;
}