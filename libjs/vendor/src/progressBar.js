/* ========================================================================
 * Bootstrap Select: progressBar.js v1.0.0
 * ========================================================================
 * Copyright 2016, Salvatore Di Salvo (disalvo-infographiste[dot]be)
 * ======================================================================== */
function ProgressBar(element, settings) {
    this.element = $(element)
    this.settings = $.extend(true, {}, this.defaults(), settings)
    this.structure = $.extend({}, this.parts())
    this.animation = null
}

ProgressBar.VERSION  = '1.0.0'

ProgressBar.DEFAULTS = {
    progressBar: this,
    barClass: '',
    progress: 0,
    state: '',
    displayLoader: false,
    loader: {
        type: 'fa',
        icon: 'spinner',
        anim: 'pulse'
    }
}

ProgressBar.prototype.parts = function() {
    return {
        bar: this.element.find('.progress-bar'),
        display: this.element.find('.progress-bar-state'),
        text: this.element.find('.state'),
        loader: null
    }
}

ProgressBar.prototype.defaults = function() {
    return {
        progressBar: ProgressBar.DEFAULTS.progressBar,
        state: ProgressBar.DEFAULTS.state,
        loader: ProgressBar.DEFAULTS.loader
    }
}

ProgressBar.prototype.init = function(update) {
    this.settings.state = $(this.structure.text[Object.keys(this.structure.text)[0]]).text();
    this.settings.barClass = this.structure.bar.attr('class');
    if (this.settings.displayLoader)
        this.renderLoader();

    var $this = this;
    this.element.show(400, function(){
        $this.structure.display.width($this.element.width());
        if(typeof update === "object")
            $this.update(update);
    });
}

ProgressBar.prototype.removeLoader = function() {
    this.structure.text.next('.loader').remove();
    this.structure.loader = null;
}

ProgressBar.prototype.renderLoader = function(options) {
    var loaderSet = this.settings.loader;
    if(typeof options === "object")
        options = $.extend(true, {}, loaderSet, options);
    var loaderClass = 'loader';
    if (options.type === 'fa')
        loaderClass += ' fa fa-' + options.icon + ' fa-' + options.anim + ' fa-fw';
    else
        loaderClass += ' etc';
    var loader = ' <span class="'+loaderClass+'"></span>';
    if (this.structure.loader !== null)
        this.removeLoader();
    this.structure.text.after(loader);
    this.structure.loader = this.element.find('.loader');
    if (options.type === 'text' && options.icon === 'etc')
        this.animLoader(true);
}

ProgressBar.prototype.animLoader = function(run) {
    var $this = this,
        text = '.',
        interation = 0;
    if (run) {
        clearInterval(this.animation);
        this.animation = setInterval(function () {
            if (!(interation % 3))
                text = '.';
            else
                text += '.';
            $this.structure.loader.text(text);
            interation++;
        }, 333)
    }
    else {
        clearInterval(this.animation);
    }
}

ProgressBar.prototype.updateState = function(state) {
    this.structure.bar.attr('class',this.settings.barClass);
    this.structure.bar.addClass('progress-bar-'+state);
}

ProgressBar.prototype.update = function(options) {
    if (typeof options != "object")
        return false;

    if (options.progress !== false && options.progress >= 0) {
        this.settings.progress = options.progress;
        this.structure.bar.width(options.progress + '%');
    }
    if (options.state) {
        this.structure.text.html(options.state);
    }

    if(options.loader === false) {
        this.animLoader(false);
        this.removeLoader();
    }
    else if (typeof options.loader === "object") {
        if (options.loader === null) {
            this.animLoader(false);
            this.removeLoader();
            this.renderLoader(this.settings.loader);
        }
        else {
            this.animLoader(false);
            this.removeLoader();
            this.renderLoader(options.loader);
        }
    }
}

ProgressBar.prototype.reset = function() {
    this.structure.bar.attr('class', this.settings.barClass);
    this.update({state: this.settings.state, progress: ProgressBar.DEFAULTS.progress, loader: this.settings.loader});
}

ProgressBar.prototype.initHide = function() {
    var $this = this;
    window.setTimeout(function () {
        $this.element.hide();
        $this.reset();
    }, 4000);
}