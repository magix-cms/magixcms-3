/**
 * @category imgDropZone
 * @copyright MAGIX CMS Copyright (c) 2009 - 2021 Gerits Aurelien,
 * http://www.magix-cms.com, http://www.magix-cjquery.com, http://www.magix-dev.be
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 2.0
 * @author Salvatore Di Salvo
 * imgDropZone
 */
class dropZone {
    constructor(element,options) {
        this.element = element;
        this.input = element.querySelector('input[type="file"]');
        this.defaults = {
            preview: false,
            previewSelector: '.preview-img',
            reset: false,
            resetSelector: '.reset',
            multi: false
        };
        this.preview = typeof element.dataset.preview !== "undefined" ? element.dataset.preview : this.defaults.preview;
        this.previewSelector = this.defaults.previewSelector;
        this.reset = typeof element.dataset.reset !== "undefined" ? element.dataset.reset : this.defaults.reset;
        this.resetSelector = this.defaults.resetSelector;
        this.multi = typeof element.dataset.multi !== "undefined" ? element.dataset.multi : this.defaults.multi;
        if(typeof options === 'object') this.set(options);
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

        if(instance.reset) {
            var reset = instance.element.querySelector(instance.resetSelector);
            reset.addEventListener('click',(e) => {
                e.preventDefault();
                instance.input.value = '';
                reset.classList.add('hide');
                preview.setAttribute('src','');
                preview.classList.add('no-img');
                preview.classList.remove('preview');
                instance.element.classList.add('no-img');
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

            preview.classList.remove('no-img');
            preview.classList.add('preview');
            instance.element.classList.remove('no-img');
            if(instance.reset) {
                reset.classList.remove('hide');
            }
        }
    }

    inputFollow(event, limits, ibPosition) {
        let instance = this;
        let iWidth = instance.input.offsetWidth;
        let iHeight = instance.input.offsetHeight;
        let x = event.pageX;
        let y = event.pageY;
        if (x.between(limits.left,limits.right,true) && y.between(limits.top,limits.bottom,true)) {
            let top = y - ibPosition.top - (iHeight/2);
            let left = x - ibPosition.left - (iWidth/2);
            instance.input.style.top = top+'px';
            instance.input.style.left = left+'px';
        }
        else {
            instance.input.style.top = '0';
            instance.input.style.left = '0';
        }
    }

    init() {
        let instance = this;
        // Firefox bug fix
        instance.input.addEventListener('focus',() => instance.input.classList.add('has-focus'));
        instance.input.addEventListener('blur',() => instance.input.classList.remove('has-focus'));

        if(instance.preview) {
            instance.input.addEventListener('change',() => {
                instance.initPreview(instance.input);
            });
        }

        let inputButton = instance.element.querySelector('label');
        let submitbutton = instance.element.querySelector('button[type="submit"]');

        let dzPosition = getPosition(instance.element);
        let ibPosition = getPosition(inputButton);

        instance.element.addEventListener('dragover',(e) => {
            e.preventDefault();
            e.stopPropagation();
            instance.element.classList.add('mouse-over');
            instance.inputFollow(e,dzPosition,ibPosition);
        });

        inputButton.addEventListener('mousemove',(e) => {
            instance.inputFollow(e,ibPosition,ibPosition);
        });

        if(submitbutton !== null) {
            instance.input.addEventListener('change',() => {
                let val = instance.input.value;
                submitbutton.disabled = (val === '');
            });
        }

        instance.element.addEventListener('drop', (e) => {
            instance.element.classList.remove('mouse-over');
        }, true);
    }
}
class imgDropZone {
    constructor(options) {
        this.defaults = {
            dropZoneSelector: '.dropzone'
        };
        this.dropZoneSelector = this.defaults.dropZoneSelector;
        if(typeof options === 'object') this.set(options);
        this.run();
    }

    set(options) {
        let instance = this;
        for (var key in options) {
            if (options.hasOwnProperty(key)) instance[key] = options[key];
        }
    }

    run() {
        let instance = this;
        document.querySelectorAll(instance.dropZoneSelector).forEach((dz) => {
            let DropZone = new dropZone(dz);
            DropZone.init();
        });
    }
}
window.addEventListener('load',function(){
    const imageDropZone = new imgDropZone();
    imageDropZone.run();
});