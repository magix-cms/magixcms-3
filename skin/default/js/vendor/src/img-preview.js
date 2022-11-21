/**
 * @category imgDropZone
 * @copyright MAGIX CMS Copyright (c) 2009 - 2021 Gerits Aurelien,
 * http://www.magix-cms.com, http://www.magix-cjquery.com, http://www.magix-dev.be
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 2.0
 * @author Salvatore Di Salvo
 * imgDropZone
 */
class imagePreview {
    constructor(element,options) {
        this.element = element;
        this.input = element.querySelector('input[type="file"]');
        this.defaults = {
            previewSelector: '.preview img',
            reset: false,
            resetSelector: '.reset'
        };
        this.previewSelector = this.defaults.previewSelector;
        this.reset = typeof element.dataset.reset !== "undefined" ? element.dataset.reset : this.defaults.reset;
        this.resetSelector = this.defaults.resetSelector;
        if(typeof options === 'object') this.set(options);
        this.previewSrc = '';
        this.init();
    }

    set(options) {
        let instance = this;
        for (var key in options) {
            if (options.hasOwnProperty(key)) instance[key] = options[key];
        }
    }

    initPreview(input) {
        let instance = this;
        let preview = instance.element.querySelector(instance.previewSelector);
        if(instance.previewSrc === '') instance.previewSrc = preview.getAttribute('src');
        let submitbutton = instance.element.querySelector('button[type="submit"]');
        let label = instance.element.querySelector('label');

        if(instance.reset) {
            var reset = instance.element.querySelector(instance.resetSelector);
            reset.addEventListener('click',(e) => {
                e.preventDefault();
                instance.input.value = '';
                if(submitbutton !== null) {
                    submitbutton.disabled = true;
                    submitbutton.classList.add('hide');
                }
                reset.disabled = true;
                reset.classList.add('hide');
                label.classList.remove('hide');
                preview.setAttribute('src',instance.previewSrc);
                preview.classList.add('hide');
                return false;
            });
        }

        if(input.files && input.files[0]) {
            let reader = new FileReader();

            reader.onload = (e) => {
                let result = e.target.result;
                preview.setAttribute('src', result);
                let img = new Image();

                img.onload = () => {
                    let imageWidth = this.width,
                        imageHeight = this.height;
                    input.dataset.imageWidth = imageWidth;
                    input.dataset.imageHeight = imageHeight;
                };

                img.setAttribute('src', result);
            };

            reader.readAsDataURL(input.files[0]);

            preview.classList.remove('hide');
            label.classList.add('hide');
            if(submitbutton !== null) {
                submitbutton.disabled = false;
                submitbutton.classList.remove('hide');
            }
            if(instance.reset) {
                reset.disabled = false;
                reset.classList.remove('hide');
            }
        }
    }

    init() {
        let instance = this;

        // Firefox bug fix
        instance.input.addEventListener('focus',() => instance.input.classList.add('has-focus'));
        instance.input.addEventListener('blur',() => instance.input.classList.remove('has-focus'));
        instance.input.addEventListener('change',() => { instance.initPreview(instance.input); });
        instance.input.addEventListener('input',() => { instance.initPreview(instance.input); });
    }
}
window.addEventListener('load',function(){
    document.querySelectorAll('.preview-img').forEach((pi) => {
        new imagePreview(pi, {reset: true});
    });
});