'use strict';
//import validate from 'validate.min.js';
/**
 * @name Validator
 * @Description Validate.js validator
 * @version 1.0
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 * @date_created 02-11-2019
 */
/**
 * Validator class
 * @property {Element|Node} form form to validate
 * @property {object} constraints validation rules for the current form
 * @property {array} ignore list of selectors to ignore during validation
 * @property {Element|Node} event event to listen
 * @property {function(form,event): void} eventHandler callback to call on event
 * @property {function(error): void} error callback to call on error
 *
 * @method addRule
 * @method removeRule
 * @method listen
 */
class Validator {
    /**
     * Constructor
     * @param {Element|Node} element
     * @param {object} options
     */
    constructor(element,options) {
        if(typeof validate === "undefined") throw new Error("Validate.js is required by Validator to run properly");
        if(!isElement(element) || element.nodeName !== 'FORM') throw new Error("Validator can only work on form element");
        this.form = element;
        this.constraints = {};
        this.ignore = ['[type="hidden"]','.hidden','.ignore'];
        this.event = 'submit';
        this.eventHandler = (form,e) => {};
        this.error = (error) => console.log(error);
        if(typeof options === 'object') this.set(options);
        this.parse();
        this.form.validator = this;
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
     * add rules to constraints
     * @param {string} field name of the field to add rules
     * @param {object} rules object with the rules to add
     */
    addRule(field = '', rules = {}) {
        let self = this;
        if (typeof field === 'string' && field !== '' && typeof rules === 'object') {
            self.constraints[field] = rules;
        }
    }

    /**
     * remove rules from constraints
     * @param {string} field name of the field to remove rules
     * @param {object} rules object with the rules to remove or empty to remove all rules on this field
     */
    removeRule(field = '',rules = {}) {
        let self = this;
        if (rules === {}) {
            delete self.constraints[field];
        }
        else {
            for (let key in rules) {
                if (rules.hasOwnProperty(key) && self.constraints[field].hasOwnProperty(key)) delete self.constraints[field][key];
            }
        }
    }

    /**
     * Parse the form element to create the constraints
     */
    parse() {
        let self = this;

        forEach('input,select,textarea',(e) => {
            let valid = true, i = 0;
            while(valid && i < self.ignore.length) {
                valid = !e.matches(self.ignore[i]) && e.hasAttribute('name') && e.getAttribute('name') !== "";
                i++
            }

            if(valid) {
                let required = e.hasAttribute('required') || e.classList.contains('required');
                let name = e.getAttribute('name');
                let min = e.getAttribute('min');
                let max = e.getAttribute('max');
                let size = e.getAttribute('size');
                let cs = {};
                if(required) cs['presence'] = true ;

                switch (e.nodeName) {
                    case 'input':
                        let type = e.getAttribute('type');
                        let equalTo = e.hasAttribute('equalTo') ? e.getAttribute('equalTo') : "";
                        if(equalTo !== "") cs['equality'] = equalTo;

                        switch (type) {
                            case 'text':
                            case 'password':
                            case 'email':
                            case 'tel':
                                if(min && (max || size)) cs['length'] = { minimum : min, maximum: (size ? size : max) };
                                if(min) cs['length'] = { minimum : min };
                                if(max || size) cs['length'] = { maximum: (size ? size : max)};
                                if(type === 'email') cs['email'] = true ;
                                break;
                            case 'number':
                            case 'range':
                                cs['numericality'] = true;
                                if(min && (max || size)) cs['numericality'] = { greaterThan : min, lessThanOrEqualTo: (size ? size : max) };
                                if(min) cs['numericality'] = { greaterThan : min };
                                if(max || size) cs['numericality'] = { lessThanOrEqualTo: (size ? size : max)};
                                break;
                        }
                        break;
                    case 'select': break;
                    case 'textarea':
                        if(min && (max || size)) cs['length'] = { minimum : min, maximum: (size ? size : max) };
                        if(min) cs['length'] = { minimum : min };
                        if(max || size) cs['length'] = { maximum: (size ? size : max)};
                        break;
                }

                if(!isEmpty(cs)) self.constraints[name] = cs;
            }
        },self.form);
    }

    /**
     * Recusively finds the closest parent that has the specified class
     * @param {Element} child
     * @param {string} className
     * @returns {*|null}
     */
    closestParent(child, className) {
        let instance = this;

        if (!child || child === document) {
            return null;
        }
        if (child.classList.contains(className)) {
            return child;
        } else {
            return instance.closestParent(child.parentNode, className);
        }
    }

    /**
     * Shows the errors for a specific input
     * @param {Element} field
     * @param {Object} errors
     */
    showErrorsForInput(field, errors = {}) {
        let instance = this;

        field.classList.remove('invalid');
        field.removeAttribute('aria-describedby');

        let formGroup = instance.closestParent(field.parentNode, 'form-group');
        if(formGroup === null) return;

        // Remove the success and error classes
        formGroup.classList.remove('has-error','has-feedback');
        let errorsBlock = formGroup.querySelector('.error');
        if(errorsBlock !== null) errorsBlock.remove();

        // If we have errors
        if (errors.length) {
            if(field.nodeName === 'input' && field.type === 'hidden') return;
            // we first mark the group has having errors
            let id= field.name+"_error";
            formGroup.classList.add("has-error");
            field.setAttribute('aria-describedby',id);

            let errorBlock = document.createElement('span');
            errorBlock.classList.add('help-block','error');
            errorBlock.setAttribute('id',id);
            errorBlock.setAttribute('role','alert');
            errorBlock.setAttribute('aria-live','polite');
            errors.forEach((error) => {
                let errorSpan = document.createElement("span");
                errorSpan.textContent = error;
                errorBlock.appendChild(errorSpan);
            });
            formGroup.appendChild(errorBlock);
        }
    }

    /**
     * Updates the inputs with the validation errors
     * @param {Element} form
     * @param {Object} errors
     */
    showErrors(form, errors) {
        let instance = this;

        // We loop through all the inputs and show the errors for that input
        forEach("input[name], select[name], textarea[name]",(field) => {
            //if (errors.hasOwnProperty(field.name)) {
                instance.showErrorsForInput(field, errors.hasOwnProperty(field.name) ? errors[field.name] : {});
            //}
        },form);
    }

    /**
     * Form Validator
     * @param options
     */
    listen(options = {}) {
        let constraints = Object.assign({},this.constraints,options),
            instance = this;

        instance.form.addEventListener(instance.event,(event) => {
            event.preventDefault();
            let errors = validate(instance.form, constraints, {fullMessages: false});

            instance.showErrors(instance.form, errors || {});
            if (!errors)
                instance.eventHandler(instance.form,event);

            /*validate(form, constraints)
                .then(instance.eventHandler(form,event), instance.error);*/

            return false;
        });
    }
}