'use strict';
/**
 * @name SimpleRequest
 * @Description Axios wrapper
 * @version 1.0
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 * @date_created 02-11-2019
 */
class SimpleRequest {
    /**
     * Constructor
     * @param {Object} options
     */
    constructor(options = {}) {
        if(typeof axios === "undefined") throw new Error("axios is required by Request to run properly");
        this.request = null;
        this.settings = {
            // `url` is the server URL that will be used for the request
            url : null,
            // `method` is the request method to be used when making the request
            method : 'get', // default
            // `baseURL` will be prepended to `url` unless `url` is absolute.
            // It can be convenient to set `baseURL` for an instance of axios to pass relative URLs
            // to methods of that instance.
            baseURL: null,
            // `beforeSend` allows changes to the request data before it is sent to the server
            // This is only applicable for request methods 'PUT', 'POST', 'PATCH' and 'DELETE'
            // The last function in the array must return a string or an instance of Buffer, ArrayBuffer,
            // FormData or Stream
            // You may modify the headers object.
            beforeSend : false,
            // `beforeSuccess` allows changes to the response data to be made before
            // it is passed to then/catch
            beforeSuccess: false,
            // `headers` are custom headers to be sent
            headers: null,
            // `params` are the URL parameters to be sent with the request
            // Must be a plain object or a URLSearchParams object
            params: null,
            // `data` is the data to be sent as the request body
            // Only applicable for request methods 'PUT', 'POST', and 'PATCH'
            // When no `transformRequest` is set, must be of one of the following types:
            // - string, plain object, ArrayBuffer, ArrayBufferView, URLSearchParams, FormData, File, Blob
            // syntax alternative to send data into the body
            // method post
            // only the value is sent, not the key
            // 'Country=Brasil&City=Belo Horizonte'
            data : false,
            // `timeout` specifies the number of milliseconds before the request times out.
            // If the request takes longer than `timeout`, the request will be aborted.
            timeout: 0, // default is `0` (no timeout)
            // `withCredentials` indicates whether or not cross-site Access-Control requests
            // should be made using credentials
            withCredentials: false, // default
            // `auth` indicates that HTTP Basic auth should be used, and supplies credentials.
            // This will set an `Authorization` header, overwriting any existing
            // `Authorization` custom headers you have set using `headers`.
            // Please note that only HTTP Basic auth is configurable through this parameter.
            // For Bearer tokens and such, use `Authorization` custom headers instead.
            // auth: {
            //     username: 'janedoe',
            //     password: 's00pers3cret'
            // },
            auth: false,
            // `responseType` indicates the type of data that the server will respond with
            // options are: 'arraybuffer', 'document', 'json', 'text', 'stream', 'blob'
            dataType: 'json',
            // `responseEncoding` indicates encoding to use for decoding responses
            // Note: Ignored for `responseType` of 'stream' or client-side requests
            responseEncoding: 'utf8', // default
            // `xsrfCookieName` is the name of the cookie to use as a value for xsrf token
            xsrfCookieName: 'XSRF-TOKEN', // default
            // `xsrfHeaderName` is the name of the http header that carries the xsrf token value
            xsrfHeaderName: 'X-XSRF-TOKEN', // default
            // `onUploadProgress` allows handling of progress events for uploads
            onUploadProgress: false, // function (progressEvent) {}
            // `onDownloadProgress` allows handling of progress events for downloads
            onDownloadProgress: false, // function (progressEvent) {}
            // `validateStatus` defines whether to resolve or reject the promise for a given
            // HTTP response status code. If `validateStatus` returns `true` (or is set to `null`
            // or `undefined`), the promise will be resolved; otherwise, the promise will be
            // rejected.
            validateStatus: function (status) {
                return status >= 200 && status < 300; // default
            },
            // `maxRedirects` defines the maximum number of redirects to follow in node.js.
            // If set to 0, no redirects will be followed.
            maxRedirects: 5, // default
            // `socketPath` defines a UNIX Socket to be used in node.js.
            // e.g. '/var/run/docker.sock' to send requests to the docker daemon.
            // Only either `socketPath` or `proxy` can be specified.
            // If both are specified, `socketPath` is used.
            socketPath: null, // default
            // contain the form element to perform actions on it
            form: null,
            // if set true, reset the form at the end of the request
            resetForm: false,
            // what to do when the request is success
            error: function(error) { console.log(error); }, // function(error) {}
            // what to do when catching an error
            success: false, // function(response) {}
            // what to do after the request (always executed)
            complete: false // function () {}
        };

        if(typeof options === 'object') this.set(options);
    }

    /**
     * Override default property values
     * @param {Object} options
     */
    set(options) {
        let instance = this.settings;
        for (var key in options) {
            if (options.hasOwnProperty(key)) instance[key] = options[key];
        }
    }

    //ToDo add cancelation options
    /**
     * Preapre and return the request parameters
     * @returns object
     */
    prepare() {
        let params = {
            url: this.settings.url,
            method: this.settings.method,
            timeout: this.settings.timeout,
            withCredentials: this.settings.withCredentials,
            responseType: this.settings.dataType,
            responseEncoding: this.settings.responseEncoding,
            xsrfCookieName: this.settings.xsrfCookieName,
            xsrfHeaderName: this.settings.xsrfHeaderName,
            onUploadProgress: this.settings.onUploadProgress,
            onDownloadProgress: this.settings.onDownloadProgress,
            validateStatus: this.settings.validateStatus,
            maxRedirects: this.settings.maxRedirects,
            socketPath: this.settings.socketPath
        };

        if(this.settings.baseURL !== null && typeof this.settings.baseURL === 'string') params['baseURL'] = this.settings.baseURL;
        if(this.settings.beforeSend !== false
            && typeof this.settings.beforeSend === 'object'
            && (params.method === 'post' || params.method === 'put' || params.method === 'delete')) params['transformRequest'] = this.settings.beforeSend;
        if(this.settings.beforeSuccess !== false && typeof this.settings.beforeSuccess === 'object') params['transformResponse'] = this.settings.beforeSuccess;
        if(this.settings.headers !== null && typeof this.settings.headers === 'object') params['headers'] = this.settings.headers;
        if(this.settings.params !== null && typeof this.settings.params === 'object') params['params'] = this.settings.params;
        if(this.settings.data !== false
            && typeof this.settings.data === 'object'
            && (params.method === 'post' || params.method === 'put' || params.method === 'delete')) params['data'] = this.settings.data;
        if(this.settings.auth !== null && typeof this.settings.auth === 'object') params['auth'] = this.settings.auth;

        return params;
    }

    /**
     * Prepare an instance of axios to be send later
     * @param {Object} options
     */
    create(options) {
        let SR = this;
        if(typeof options === 'object') SR.set(options);
        SR.request = axios.create(SR.prepare());
    }

    /**
     * Execute a get request
     * @param {Object} options
     */
    exec(options) {
        let SR = this;
        if(SR.request === null) SR.create(options);
        else if(Object.keys(data).length !== 0 && options.constructor === Object) SR.set(options);

        if(typeof SR.settings.url !== 'string' || SR.settings.url === '') throw new Error("The target url is not a string or empty");

        console.log(SR.settings);
        SR.request.request(SR.settings)
            .then(function(response) {
                if(SR.settings.success !== false) SR.settings.success(response);
            })
            .catch(function(error) {
                if(SR.settings.error !== false) SR.settings.error(error);
            })
            .finally(function() {
                if(SR.settings.complete !== false) SR.settings.complete();
                if(isElement(SR.settings.form) && SR.settings.form.nodeName === 'FORM' && SR.settings.resetForm) SR.settings.form.reset();
            });
    }

    /**
     * Execute a get request
     * @param {String} url
     * @param {Object} options
     */
    get(url, options) {
        let SR = this;
        if(typeof url !== 'string' || url === '') throw new Error("The target url is not a string or empty");
        if(SR.request === null) SR.create(options);

        SR.request.get(url)
            .then(function(response) {
                if(SR.settings.success !== false) SR.settings.success(response);
            })
            .catch(function(error) {
                if(SR.settings.error !== false) SR.settings.error(error);
            })
            .finally(function() {
                if(SR.settings.complete !== false) SR.settings.complete();
                if(isElement(SR.settings.form) && SR.settings.form.nodeName === 'FORM' && SR.settings.resetForm) SR.settings.form.reset();
            });
    }

    /**
     * Execute a post request
     * @param {String} url
     * @param {String|Object} data
     * @param {Object} options
     */
    post(url, data, options) {
        let SR = this;
        if(typeof url !== 'string' || url === '') throw new Error("The target url is not a string or empty");
        if(Object.keys(data).length === 0 && data.constructor === Object || (data.constructor !== FormData && data.constructor !== Object)) data = {};
        if(SR.request === null) SR.create(options);

        SR.request.post(url, data)
            .then(function(response) {
                if(SR.settings.success !== false) SR.settings.success(response);
            })
            .catch(function(error) {
                if(SR.settings.error !== false) SR.settings.error(error);
            })
            .finally(function() {
                if(isElement(SR.settings.form) && SR.settings.form.nodeName === 'FORM' && SR.settings.resetForm) SR.settings.form.reset();
                if(SR.settings.complete !== false) SR.settings.complete();
            });
    }

    /**
     * Execute a put request
     * @param {String} url
     * @param {String|Object} data
     * @param {Object} options
     */
    put(url, data, options) {
        let SR = this;
        if(typeof url !== 'string' || url === '') throw new Error("The target url is not a string or empty");
        if(Object.keys(data).length === 0 && data.constructor === Object || (data.constructor !== FormData && data.constructor !== Object)) data = {};
        if(SR.request === null) SR.create(options);

        SR.request.put(url, data)
            .then(function(response) {
                if(SR.settings.success !== false) SR.settings.success(response);
            })
            .catch(function(error) {
                if(SR.settings.error !== false) SR.settings.error(error);
            })
            .finally(function() {
                if(SR.settings.complete !== false) SR.settings.complete();
                if(isElement(SR.settings.form) && SR.settings.form.nodeName === 'FORM' && SR.settings.resetForm) SR.settings.form.reset();
            });
    }

    /**
     * Execute a delete request
     * @param {String} url
     * @param {Object} options
     */
    delete(url, options) {
        let SR = this;
        if(typeof url !== 'string' || url === '') throw new Error("The target url is not a string or empty");
        if(SR.request === null) SR.create(options);

        SR.request.delete(url)
            .then(function(response) {
                if(SR.settings.success !== false) SR.settings.success(response);
            })
            .catch(function(error) {
                if(SR.settings.error !== false) SR.settings.error(error);
            })
            .finally(function() {
                if(SR.settings.complete !== false) SR.settings.complete();
                if(isElement(SR.settings.form) && SR.settings.form.nodeName === 'FORM' && SR.settings.resetForm) SR.settings.form.reset();
            });
    }

    /**
     * Intercept a request before sending it or before parsing the response
     * @param {String} on - request || response
     * @param {function} beforeSuccess
     * @param {function} error
     */
    intercept(on, beforeSuccess, error) {
        if(this.request !== null) {
            if(on === 'request') {
                this.request.interceptors.request.use(function (config) {
                    // Do something before request is sent
                    beforeSuccess(config);
                    return config;
                }, function (error) {
                    // Do something with request error
                    error(error);
                    return Promise.reject(error);
                });
            }
            if(on === 'response') {
                this.request.interceptors.response.use(function (response) {
                    // Any status code that lie within the range of 2xx cause this function to trigger
                    // Do something with response data
                    beforeSuccess(response);
                    return response;
                }, function (error) {
                    // Any status codes that falls outside the range of 2xx cause this function to trigger
                    // Do something with response error
                    error(error);
                    return Promise.reject(error);
                });
            }
        }
    }
}