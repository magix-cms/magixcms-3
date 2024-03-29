/**
 * ProgressBar
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @author Salvatore Di Salvo <www.disalvo-infographiste.be>
 */
class ProgressBar {
    constructor(options) {
        this.defaults = {
            element: null,
            animation: null,
            selector: '.progressBar',
            barClass: '',
            state: '',
            displayLoader: false,
            progress: 0,
            loader: {
                type: 'icon',
                icon: 'spinner',
                class: 'fa fa-spinner fa-pulse fa-fw'
            }
        }
        this.loader = this.defaults.loader;
        this.element = this.defaults.element;
        this.animation = this.defaults.animation;
        this.selector = this.defaults.selector;
        this.barClass = this.defaults.barClass;
        this.state = this.defaults.state;
        this.displayLoader = this.defaults.displayLoader;
        this.progress = this.defaults.progress;

        if(typeof options === 'object') this.set(options);
        this.build();
    }

    set(options) {
        let instance = this;
        for (var key in options) {
            if (options.hasOwnProperty(key)) instance[key] = options[key];
        }
    }

    build() {
        let instance = this,
            element = document.querySelector(instance.selector);
        this.element = element;
        instance.structure = {
            bar: element.querySelector('.progress-bar'),
            display: element.querySelector('.progress-bar-state'),
            state: element.querySelector('.state'),
            loader: null
        }
    }

    init(update) {
        let instance = this;

        instance.state = instance.structure.state.innerText;
        instance.barClass = instance.structure.bar.className;

        if(instance.displayLoader) instance.renderLoader();

        instance.element.classList.remove('hide');
        instance.element.classList.add('waiting');
        instance.structure.display.style.width = instance.element.style.width;
        if(typeof update === 'object') instance.update(update);
    }

    renderLoader(options) {
        let instance = this;
        let loaderOpts = instance.loader;
        if(typeof options === "object") loaderOpts = Object.assign(loaderOpts, options);

        let loader = document.createElement('span');
        loader.className = 'loader '+loaderOpts.class;

        if (instance.structure.loader !== null) instance.removeLoader();

        instance.structure.display.appendChild(loader);
        instance.structure.loader = instance.element.querySelector('.loader');
        if (loaderOpts.type === 'text' && loaderOpts.icon === 'etc') instance.animLoader(true);
    }

    removeLoader() {
        let instance = this;
        let loader = instance.structure.state.nextSibling;
        if(loader !== null) {
            loader.remove();
            instance.structure.loader = null;
        }
    }

    animLoader(run) {
        let instance = this,
            text = '.',
            iteration = 0;

        if (run) {
            clearInterval(instance.animation);
            instance.animation = setInterval(function () {
                if (!(iteration % 3))
                    text = '.';
                else
                    text += '.';
                instance.structure.loader.innerText = text;
                iteration++;
            }, 333)
        }
        else {
            clearInterval(instance.animation);
        }
    }

    update(options) {
        let instance = this;
        if (typeof options !== "object") return false;

        if (options.progress !== false && options.progress >= 0) {
            instance.element.classList.remove('waiting');
            instance.progress = options.progress;
            instance.structure.bar.style.width = options.progress + '%';
        }
        if (options.state !== false && options.state !== undefined) instance.structure.state.innerHTML = options.state;

        if(options.loader === false || options.loader === null) {
            instance.animLoader(false);
            instance.removeLoader();
        }
        else if (typeof options.loader === "object") {
            instance.animLoader(false);
            instance.removeLoader();
            instance.renderLoader(options.loader === null ? instance.loader : options.loader);
        }
    }

    updateState(state) {
        let instance = this;
        instance.structure.bar.setAttribute('class',instance.barClass);
        instance.structure.bar.classList.add('progress-bar-'+state);
    }

    reset() {
        let instance = this;
        instance.structure.bar.setAttribute('class', instance.barClass);
        instance.update({state: instance.state, progress: instance.defaults.progress, loader: instance.loader});
    }

    initHide() {
        let instance = this;

        window.setTimeout(function () {
            instance.element.classList.add('hide');
            instance.reset();
        }, 4000);
    }
}