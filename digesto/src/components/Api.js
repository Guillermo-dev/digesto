/**
 *
 * @param params
 * @returns {string}
 */
function urlParams(params) {
    if (params === undefined) return '';
    const url = new URLSearchParams();
    Object.keys(params).forEach(key => {
        url.append(key, params[key]);
    });
    if (url.toString().length > 0)
        return '?' + url.toString();
    return '';
}

/**
 *
 * @constructor
 */
export function DocumentosAPI() {
    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.getDocumentos = function(config = {}, success, error) {
        fetch('/api/documentos' + urlParams(config["params"])).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.getDocumento = function(config = {}, success, error) {
        fetch(`/api/documentos/${config.id}` + urlParams(config["params"])).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.createDocumento = function(config = {}, success, error) {
        fetch(`/api/documentos` + urlParams(config["params"]), {
            method: "POST",
            body: config.data
        }).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.updateDocumento = function(config = {}, success, error) {
        fetch(`/api/documentos/${config.id}` + urlParams(config["params"]), {
            method: "PUT",
            body: config.data
        }).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.deleteDocumento = function(config = {}, success, error) {
        fetch(`/api/documentos/${config.id}` + urlParams(config["params"]), {
            method: "DELETE"
        }).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }
}

/**
 *
 * @constructor
 */
export function TagsAPI() {
    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.getTags = function(config = {}, success, error) {
        fetch('/api/tags' + urlParams(config["params"])).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.getTag = function(config = {}, success, error) {
        fetch(`/api/tags/${config.id}` + urlParams(config["params"])).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.createTag = function(config = {}, success, error) {
        fetch(`/api/tags` + urlParams(config["params"]), {
            method: "POST",
            body: config.data
        }).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.updateTag = function(config = {}, success, error) {
        fetch(`/api/tags/${config.id}` + urlParams(config["params"]), {
            method: "PUT",
            body: config.data
        }).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.deleteTag = function(config = {}, success, error) {
        fetch(`/api/tags/${config.id}` + urlParams(config["params"]), {
            method: "DELETE"
        }).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }
}

/**
 *
 * @constructor
 */
export function EmisoresAPI() {
    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.getEmisores = function(config = {}, success, error) {
        fetch('/api/emisores' + urlParams(config["params"])).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.getEmisor = function(config = {}, success, error) {
        fetch(`/api/emisores/${config.id}` + urlParams(config["params"])).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.createEmisor = function(config = {}, success, error) {
        fetch(`/api/emisores` + urlParams(config["params"]), {
            method: "POST",
            body: config.data
        }).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.updateEmisor = function(config = {}, success, error) {
        fetch(`/api/emisores/${config.id}` + urlParams(config["params"]), {
            method: "PUT",
            body: config.data
        }).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.deleteEmisor = function(config = {}, success, error) {
        fetch(`/api/emisores/${config.id}` + urlParams(config["params"]), {
            method: "DELETE"
        }).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }
}

/**
 *
 * @constructor
 */
export function AuthAPI() {
    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.login = function(config = {}, success, error) {
        fetch('/api/auth/login' + urlParams(config["params"]), {
            method: 'POST'
        }).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }

    /**
     *
     * @param config
     * @param success
     * @param error
     */
    this.logout = function(config = {}, success, error) {
        fetch(`/api/auth/logout` + urlParams(config["params"]), {
            method: 'DELETE'
        }).then(response => {
            response.json().then(success).catch(error);
        }).catch(error);
    }
}