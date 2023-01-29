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
        let trueValues = ['true', '1', 'yes'];
        this.element = element;
        this.input = element.querySelector('input[type="file"]');
        this.defaults = {
            preview: false,
            previewSelector: '.preview-img',
            reset: false,
            resetSelector: '.reset',
            multi: false
        };
        this.isInside = false;
        this.event = {
            mouseIn: new Event('mouseIn', {
                bubbles: true,
                cancelable: true,
                composed: false
            }),
            mouseOut: new Event('mouseOut', {
                bubbles: true,
                cancelable: true,
                composed: false
            })
        };
        this.preview = typeof element.dataset.preview !== "undefined" ? (trueValues.indexOf(element.dataset.preview) !== -1) : this.defaults.preview;
        this.previewSelector = this.defaults.previewSelector;
        this.reset = typeof element.dataset.reset !== "undefined" ? (trueValues.indexOf(element.dataset.reset) !== -1) : this.defaults.reset;
        this.resetSelector = this.defaults.resetSelector;
        this.multi = typeof element.dataset.multi !== "undefined" ? (trueValues.indexOf(element.dataset.multi) !== -1) : this.defaults.multi;
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
        let previewBox = instance.element.querySelector(instance.previewSelector);
        let previewImgTag = previewBox.querySelector('img');

        if(instance.reset) {
            var reset = instance.element.querySelector(instance.resetSelector);
            reset.addEventListener('click',(e) => {
                e.preventDefault();
                instance.input.value = '';
                reset.classList.add('hide');
                previewImgTag.setAttribute('src','');
                previewImgTag.classList.add('no-img');
                previewImgTag.classList.remove('preview');
                instance.element.classList.add('no-img');
                return false;
            });
        }

        if(input.files && input.files[0]) {
            let reader = new FileReader();

            reader.onload = (e) => {
                let result = e.target.result;
                previewImgTag.setAttribute('src', result);
                let img = new Image();

                img.onload = () => {
                    input.dataset.imageWidth = this.width;
                    input.dataset.imageHeight = this.height;
                };

                img.setAttribute('src', result);
            };

            reader.readAsDataURL(input.files[0]);

            previewImgTag.classList.remove('no-img');
            previewImgTag.classList.add('preview');
            instance.element.classList.remove('no-img');
            if(instance.reset) {
                reset.classList.remove('hide');
            }
        }
    }

    /**
     * @Deprecated
     * @param event
     * @param limits
     * @param ibPosition
     */
    inputFollow(event, limits, ibPosition) {
        let instance = this;
        //let iWidth = instance.input.offsetWidth;
        //let iHeight = instance.input.offsetHeight;
        let x = event.pageX;
        let y = event.pageY;
        //console.log(event);
        //console.log(limits);
        if (x.between(limits.left,limits.right,true) && y.between(limits.top,limits.bottom,true)) {
            if(!this.isInside) {
                this.element.dispatchEvent(this.event.mouseIn);
                this.isInside = true;
            }
            //let top = y - limits.top - (limits.height/2) - (iHeight/2);
            //let top = event.offsetY - (iHeight/2);
            //let top = event.offsetY - (limits.height/2);
            let top = event.offsetY;
            //let left = x - limits.left - (limits.width/2) - (iWidth/2);
            //let left = event.offsetX - (iWidth/2);
            //let left = event.offsetX - (limits.width/2);
            let left = event.offsetX;
            instance.input.style.top = top+'px';
            instance.input.style.left = left+'px';
        }
        else {
            if(this.isInside) {
                this.element.dispatchEvent(this.event.mouseOut);
                this.isInside = false;
            }
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

        //let inputButton = instance.element.querySelector('label');

        //let dzPosition = getPosition(instance.element);
        //let ibPosition = getPosition(inputButton);

        instance.element.addEventListener('dragover',(e) => {
            e.preventDefault();
            e.stopPropagation();
            instance.element.classList.add('drag-over');
            //instance.inputFollow(e,dzPosition,null);
        });

        /*inputButton.addEventListener('mousemove',(e) => {
            instance.inputFollow(e,ibPosition,ibPosition);
        });*/

        let submitbutton = instance.element.querySelector('button[type="submit"]');
        if(submitbutton !== null) {
            instance.input.addEventListener('change',() => {
                let val = instance.input.value;
                console.log(val);
                submitbutton.disabled = (val === '');
            });
        }

        instance.element.addEventListener('drop', (e) => {
            instance.element.classList.remove('drag-over');
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
        });
    }
}
window.addEventListener('load',() => {
    const imageDropZone = new imgDropZone();

    $('a[data-toggle="tab"]').on('shown.bs.tab', (e) => {
        let tabID = e.target.getAttribute('href');
        if(['#image', '#images', '#gallery', '#logo', '#favicon'].indexOf(tabID) !== -1) {
            let id = tabID.substring(1);
            document.getElementById(id).querySelectorAll('.dropzone').forEach((dz) => {
                let DropZone = new dropZone(dz);
            });
        }
    });
});