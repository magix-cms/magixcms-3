/*!
 * Add-on for including Flmngr into your TinyMCE
 * Developer: Flmngr
 * Website: https://flmngr.com/
 * License: GPL v3
 */

//
//   HOW TO INSTALL THIS ADD-ON
//
//   1. Copy the plugin as "tinymce/plugins/file-manager/plugin.js"
//   2. Add "file-manager" into "plugins" config option
//   3. See configuration samples on our GitHub page:
//      https://github.com/edsdk/flmngr-tinymce
//

var apiKey;
var version;
var n1edPrefix;
var n1edHttps;
var n1edPrefixApp;
var n1edHttpsApp;
var urlCache;
window.n1edPluginVersion=202308001;
if (tinymce.majorVersion == 6) {
    var getOption = function(name, type) {
        var options = tinymce.get()[0].options;
        var defaultValue = false;
        if (type === "string")
            defaultValue = "";
        else if (type === "object")
            defaultValue = {};
        options.register(name, {processor: type, default: defaultValue});
        if (options.isSet(name))
            return options.get(name);
        else
            return null;
    }
    var flmngrOpts = getOption("Flmngr", "object");
    if (!!flmngrOpts)
        apiKey = flmngrOpts["apiKey"];
    if (!apiKey)
        apiKey = getOption("apiKey", "string");
    version = getOption("version", "string");
    n1edPrefix = getOption("n1edPrefix", "string");
    n1edHttps = getOption("n1edHttps", "boolean");
    n1edPrefixApp = getOption("n1edPrefixApp", "string");
    n1edHttpsApp = getOption("n1edHttpsApp", "boolean");
    urlCache = getOption("urlCache", "string");
} else {
    if (!!tinymce.settings.Flmngr)
        apiKey = tinymce.settings.Flmngr.apiKey;
    if (!!apiKey)
        apiKey = tinymce.settings.apiKey;
    version = tinymce.settings.version;
    n1edPrefix = tinymce.settings.n1edPrefix;
    n1edHttps = tinymce.settings.n1edHttps;
    n1edPrefixApp = tinymce.settings.n1edPrefixApp;
    n1edHttpsApp = tinymce.settings.n1edHttpsApp;
    urlCache = tinymce.settings.urlCache;
}

// Cookies may contain data for development purposes (which version to load, from where, etc.).
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2)
        return parts.pop().split(';').shift();
    else
        return null;
}


window.TINYMCE_OVERRIDE_API_KEY_PARAM = "OVERRIDE_API_KEY";
apiKey = getCookie("N1ED_APIKEY") || apiKey || window.OVERRIDE_API_KEY || "FLMNFLMN";
n1edHttps = !(getCookie("N1ED_HTTPS") === "false" || n1edHttps === false);
n1edPrefix = getCookie("N1ED_PREFIX") || n1edPrefix || null;
n1edHttpsApp = !(getCookie("N1ED_HTTPS_APP") === "false" || n1edHttpsApp === false);
n1edPrefixApp = getCookie("N1ED_PREFIX_APP") || n1edPrefixApp || null;
var protocol = n1edHttps ? "https" : "http";
var host = (n1edPrefix ? (n1edPrefix + ".") : "") + "cloud.n1ed.com";
version = getCookie("N1ED_VERSION") || version || "latest";

if (!urlCache) {
    window.N1ED_PREFIX = n1edPrefix;
    window.N1ED_HTTPS = n1edHttps;
}
window.N1ED_PREFIX_APP = n1edPrefixApp;
window.N1ED_HTTPS_APP = n1edHttpsApp;

var urlPlugin = (
    urlCache ? (urlCache + apiKey + "/" + version) : (protocol + "://" + host + "/cdn/" + apiKey + "/" + version)
) + "/tinymce/plugins/N1EDEco/plugin.js";

// Load Ecosystem plugin manually due to
// TinyMCE will not accept external_plugins option on the fly
tinymce.PluginManager.load('N1EDEco',  urlPlugin);

tinymce.PluginManager.add(
    "file-manager",
    function(ed, url) {
        // TinyMCE 6 does not initialize dependency plugins automatically
        if (tinymce.majorVersion == 6)
            tinymce.PluginManager.get("N1EDEco")(ed, url);
    },
    ["N1EDEco"] // We can not move N1EDEco in this file due to we need to dynamically
    // embed configuration from your Dashboard into it.
    // So N1EDEco add-on can be loaded only from CDN
);
