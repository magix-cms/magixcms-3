class MagicForm {
    constructor(form) {
        this.form = form;
        this.init();
    }

    set(options) {
        let instance = this;
        for (var key in options) {
            if (options.hasOwnProperty(key)) instance[key] = options[key];
        }
        this.init();
    }

    init() {
        this.formElements = this.form.querySelectorAll('[name]');
    }

    get(get) {
        let instance = this,
            index = 0,
            data = [];

        instance.formElements.forEach(function(formElement) {
            let elem = {
                    id: formElement.getAttribute('id'),
                    type: formElement.getAttribute('type'),
                    name: formElement.getAttribute('name')
                },
                input = formElement.nodeName,
                checked = formElement.checked;
            if( (input === 'INPUT' && elem.type === 'checkbox' && !checked) || !elem.name ) return;

            switch (get) {
                case 'ids':
                    data[index] = formElement.getAttribute('id');
                    break;
                case 'keys':
                    data[index] = elem.name;
                    break;
                case 'values':
                    if(formElement.classList.contains('mceEditor')) {
                        data[index] = tinyMCE.get(elem.id).getContent();
                    }
                    else if(formElement.type === 'file') {
                        if(formElement.multiple) data[index] = formElement.files;
                        else data[index] = formElement.files[0];
                    }
                    else {
                        data[index] = formElement.value;
                    }
                    break;
            }

            index++;
        });

        return data;
    }

    keys() {
        return this.get('keys');
    }

    values() {
        return this.get('values');
    }

    parseKeyValue(k,v) {
        let key = '{"'+k;
        let osub = /\[/gi;
        let csub = /\]/gi;
        let lvl = 0;

        if(osub.test(key)) {
            key = key.replace(csub, '');

            do {
                key = key.replace('[', '":{"');
                lvl++;
            } while(osub.test(key));
        }

        //key += '":' + JSON.stringify(v ? v : null) + '}';
        key += '":""}';

        if(lvl) {
            for(var i=0;i < lvl;i++) {
                key += '}';
            }
        }

        //return JSON.parse(key);

        key = JSON.parse(key);

        function objectTreeBottom(obj,v){
            for (var name in obj) {
                if (obj.hasOwnProperty(name)) {
                    if (typeof obj[name] === 'object') obj[name] = objectTreeBottom(obj[name], v);
                    else obj[name] = v;
                }
            }
            return obj;
        }

        return objectTreeBottom(key,v);
    }

    static mergeObject(target, src) {
        for (var key in src) {
            if (src.hasOwnProperty(key)) {
                if (typeof target[key] !== "undefined" && typeof src[key] === 'object') target[key] = MagicForm.mergeObject(target[key],src[key]);
                else target[key] = src[key];
            }
        }
        return target;
    }

    getData() {
        let instance = this;
        let keys = instance.keys();
        let values = instance.values();

        let data = {};

        for(var i=0;i < keys.length;i++) {
            /*let key = instance.parseKeyValue(keys[i],values[i]);
            data = MagicForm.mergeObject(data, key);*/
            data[keys[i]] = values[i];
        }

        return data;
    }

    static getFormDataFromData(d,f) {
        let data = null;
        if(typeof f !== "undefined") {
            data = new FormData(f);
        }
        else {
            data = new FormData();
        }

        for(var key in d) {
            if(!data.has(key)) data.append(key, d[key]);
        }

        return data;
    }
}