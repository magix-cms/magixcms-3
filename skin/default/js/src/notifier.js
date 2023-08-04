/**
 * @name Notifier
 * @Description Form Validator and success handler
 * @version 1.0
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 * @date_created 21-05-2019
 */
class Notifier {
    /**
     * Constructor
     * @param {object} options
     */
    constructor(options= {}) {
        this.display = true;
        this.refresh = false;
        this.redirectUrl = false;
        this.timeout = 2800;
        this.debug = false;
        this.cssClass = '.mc-message';
        if(typeof options === 'object') this.set(options);
    }

    /**
     * Override default property values
     * @param {object} options
     */
    set(options) {
        let instance = this;
        for (let key in options) {
            if (options.hasOwnProperty(key)) instance[key] = options[key];
        }
    }

    /**
     * Redirection function.
     * @param {string} loc - url where to redirect.
     * @param {int} [timeout=2800] - Time before redirection.
     */
    redirect(loc, timeout) {
        timeout = typeof timeout !== 'undefined' ? timeout : 2800;
        setTimeout(() => window.location.href = loc,timeout);
    }

    /**
     * Reload function.
     * @param {int} [timeout=2800] - Time before reload.
     */
    reload(timeout) {
        timeout = typeof timeout !== 'undefined' ? timeout : 2800;
        setTimeout(() => location.reload(),timeout);
    }

    /**
     * Display function
     * @param {string|html} response
     * @param {object} options
     */
    notify(response, options = {}) {
        if(typeof options === 'object') this.set(options);
        let notifier = this;

        if(notifier.display !== false
            && notifier.cssClass !== ''
            && notifier.cssClass !== null
            && notifier.cssClass !== undefined) {
            if(notifier.debug) console.log(notifier.cssClass);
            forEach(notifier.cssClass,(i) => i.innerHTML = response);
        }

        if(notifier.redirectUrl
            && typeof notifier.redirectUrl === "string"
            && notifier.redirectUrl !== "") notifier.redirect(notifier.redirectUrl,notifier.timeout);

        if(notifier.refresh) notifier.reload(notifier.timeout);
    }

    /**
     * Close function
     */
    close() {
        let notifier = this;

        if(notifier.display !== false
            && notifier.cssClass !== ''
            && notifier.cssClass !== null
            && notifier.cssClass !== undefined) {
            if(notifier.debug) console.log(notifier.cssClass);
            forEach(notifier.cssClass,(i) => i.innerHTML = '');
        }
    }
}