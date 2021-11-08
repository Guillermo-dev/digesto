import "../../../node_modules/sweetalert2/dist/sweetalert2.min.js";
import "../../../node_modules/izitoast/dist/js/iziToast.min.js";

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
    element._id = function(id) {
        element.id = id;
        return element;
    }
    element._class = function() {
        Array.from(arguments).forEach(argument => {
            element.classList.add(argument);
        });
        return element;
    }
    element._html = function(html) {
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
 * @returns {*}
 */
export function createStyle() {
    const style = document.createElement('style');
    style._content = function(content) {
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
    window.iziToast.success({message: message.toString()});
}

/**
 *
 * @param message
 */
export function warningAlert(message) {
    window.iziToast.warning({message: message.toString()});
}


/**
 *
 * @param message
 */
export function errorAlert(message) {
    window.iziToast.error({message: message.toString()});
}
