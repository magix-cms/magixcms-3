/**
 * @version    2.0
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 */
class NiceForms {
    /**
     * Constructor
     * @param selector
     * @param exclude
     */
    constructor(selector, exclude) {
        this.selector = typeof selector !== 'string' ? '.nice-form' : selector;
        this.exclude = typeof exclude !== 'string' ? '.not-nice' : exclude;
    }

    /**
     * Check if empty
     * @param {Element} elem
     * @returns {boolean}
     */
    static isEmpty(elem) {
        let val = elem.value;
        return ((typeof val === 'string' && val.length === 0) || (typeof val === 'object' && val == null));
    }

    /**
     * Update lable class
     * @param {Element} elem
     */
    updateLabel(elem) {
        document.querySelector('[for="'+elem.id+'"]').classList.toggle('is_empty', NiceForms.isEmpty(elem));
    }

    /**
     * Reset
     */
    reset() {
        let NF = this;
        document.querySelectorAll(NF.selector).forEach((nf) => {
            let nicefields = nf.querySelectorAll('input:not('+NF.exclude+'):not([type="submit"]):not([type="hidden"]),textarea:not('+NF.exclude+'),select:not('+NF.exclude+')');
            nicefields.forEach((f) => NF.updateLabel(f));
        });
    }

    /**
     *
     */
    init() {
        let NF = this;
        document.querySelectorAll(NF.selector).forEach((form) => {
            form.NF = NF;
            let inputs = form.querySelectorAll('input:not('+NF.exclude+'):not([type="submit"]):not([type="hidden"])');
            let txtareas = form.querySelectorAll('textarea:not('+NF.exclude+')');
            let selects = form.querySelectorAll('select:not('+NF.exclude+')');

            inputs.forEach((i) => {
                if(i.getAttribute('type') !== 'hidden') {
                    NF.updateLabel(i);
                    i.addEventListener('change',() => NF.updateLabel(i));
                }
            });
            txtareas.forEach((t) => {
                NF.updateLabel(t);
                t.addEventListener('change',() => NF.updateLabel(t));
            });
            selects.forEach((s) => {
                NF.updateLabel(s);
                s.addEventListener('change',() => NF.updateLabel(s));
            });
        });
    }
}
const niceForms = new NiceForms();

window.addEventListener('load',() => niceForms.init());