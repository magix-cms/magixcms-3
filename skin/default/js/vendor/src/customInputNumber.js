class CustomInputNumber {
    constructor(element,options) {
        this.element = element;
        this.min = parseInt(element.getAttribute('min'));
        this.step = parseInt(element.dataset.step);
        this.startValue = parseInt(element.value);
        this.forceStep = true;
        this.forceType = 'closest';
        if(typeof options === 'object') this.set(options);
        this.event = {
            stepUp: new Event('stepUp', {
                bubbles: true,
                cancelable: true,
                composed: false
            }),
            stepDown: new Event('stepUp', {
                bubbles: true,
                cancelable: true,
                composed: false
            })
        };
        this.init();
        return this;
    }

    set(options) {
        let instance = this;
        for (var key in options) {
            if (options.hasOwnProperty(key)) instance[key] = options[key];
        }
    }

    changeStep(step) {
        this.set({step : parseInt(step)});
    }

    decreaseNumber(step) {
        step = step === undefined ? this.step : step;
        this.element.stepDown(step);
        this.element.dispatchEvent(this.event.stepDown);
    }

    increaseNumber(step) {
        step = step === undefined ? this.step : step;
        this.element.stepUp(step);
        this.element.dispatchEvent(this.event.stepUp);
    }

    validNumber() {
        let diff = (parseInt(this.element.value) - this.min) % this.step;
        if(diff) {
            let min = parseInt(this.element.value) - diff,
                max = parseInt(this.element.value) + (this.step - diff);

            switch (this.forceType) {
                case 'greater':
                    this.element.value = max;
                    break;
                case 'least':
                    this.element.value = min;
                    break;
                case 'closest':
                default:
                    this.element.value = (diff >= this.step/2) ? max : min;
            }
        }
    }

    init() {
        let self = this,
            input = this.element;

        input.addEventListener('change',(e) => {
            let diff = e.target.value - self.startValue;
            if(Math.abs(diff) < self.step) {
                if(diff > 0) self.increaseNumber();
                if(diff < 0) self.decreaseNumber();
            }
            if(self.forceStep) self.validNumber();
            self.startValue = parseInt(e.target.value);
        });
    }
}

window.addEventListener('load',() => {
    let customInputNumber = document.querySelectorAll('.custom-input-number');
    customInputNumber.forEach((cin) => {
        cin.customInputNumber = new CustomInputNumber(cin);
    });
})