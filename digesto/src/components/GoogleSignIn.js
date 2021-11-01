import {createElement, createStyle, errorAlert} from "../global/js/util.js";
import {Component} from "./Component.js";
import {AuthAPI} from "./Api.js";

/**
 *
 * @type {AuthAPI}
 */
const authAPI = new AuthAPI();

// language=CSS
createStyle('')._content(`
    .GoogleSignIn {
        margin: auto;
        max-width: max-content;
    }
`);

/**
 *
 * @constructor
 */
export default function GoogleSignIn() {
    const _this = this;
    this.name = 'GoogleSignIn';
    this.root = createElement('div')._class('GoogleSignIn')._html(`
        <div data-js="googleSignInButton"></div>
    `);
    const _googleSignInButton = _this.root.querySelector('[data-js="googleSignInButton"]');

    /**
     *
     */
    function _constructor() {
        if (window.hasOwnProperty("google")) _initGoogleAccess();
        else window.addEventListener('load', _initGoogleAccess);
    }

    /**
     *
     * @private
     */
    function _initGoogleAccess() {
        google.accounts.id.initialize({
            client_id: "595959595959595959595959",
            callback: _handleGoogleCredentialResponse,
            cancel_on_tap_outside: false
        });
        google.accounts.id.renderButton(_googleSignInButton, {size: 'large', theme: "outline"});
        google.accounts.id.prompt();
    }

    /**
     *
     * @param response
     * @private
     */
    function _handleGoogleCredentialResponse(response) {
        authAPI.login({
            data: {
                gToken: response.credential
            }
        }, response => {
            if (response.code === 200) {
                location.href = '/admin/profile';
            } else {
                errorAlert(response.error.message);
            }
        }, reason => {
            errorAlert(reason);
        });
    }

    //Invoke
    _constructor();
}

Object.setPrototypeOf(GoogleSignIn.prototype, new Component());