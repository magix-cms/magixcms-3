class rangeSlider {
    constructor(element,options) {
        this.element = element;
        this.fromSlider = element.querySelector('.fromSlider');
        this.toSlider = element.querySelector('.toSlider');
        this.fromInput = element.querySelector('.fromInput');
        this.toInput = element.querySelector('.toInput');
        this.from = 0;
        this.to = 0;
        if(typeof options === 'object') this.set(options);
        this.init();
        this.rangeChanged = new Event('rangeChanged', {
            bubbles: true,
            cancelable: true,
            composed: false
        });
        return this;
    }

    set(options) {
        let instance = this;
        for (var key in options) {
            if (options.hasOwnProperty(key)) instance[key] = options[key];
        }
    }

    getParsed(currentFrom, currentTo) {
        this.from = parseInt(currentFrom.value, 10);
        this.to = parseInt(currentTo.value, 10);
    }

    fillSlider() {
        let self = this;
        let rangeDistance = this.toSlider.max-this.toSlider.min;
        let fromPosition = this.fromSlider.value - this.toSlider.min;
        let toPosition = this.toSlider.value - this.toSlider.min;
        let color = self.element.data;
        self.toSlider.style.background = `linear-gradient(
      to right,
      rgba(0,0,0,0) 0%,
      rgba(0,0,0,0) ${(fromPosition)/(rangeDistance)*100}%,
      var(--range-color) ${((fromPosition)/(rangeDistance))*100}%,
      var(--range-color) ${(toPosition)/(rangeDistance)*100}%, 
      rgba(0,0,0,0) ${(toPosition)/(rangeDistance)*100}%, 
      rgba(0,0,0,0) 100%)`;
    }

    setToggleAccessible(currentTarget) {
        let self = this;
        let toSlider = self.element.querySelector('.toSlider');
        toSlider.style.zIndex = Number(currentTarget.value) <= 0  ? '2' : '0';
    }

    controlInput() {
        this.getParsed(this.fromInput, this.toInput);
        this.fillSlider();
        this.setToggleAccessible(this.toInput);
    }
    controlSlider() {
        this.getParsed(this.fromSlider, this.toSlider);
        this.fillSlider();
        this.setToggleAccessible(this.toSlider);
    }
    controlFrom() {
        if (this.from > this.to) {
            this.fromSlider.value = this.to;
            this.fromInput.value = this.to;
        }
        else {
            this.fromSlider.value = this.from;
            this.fromInput.value = this.from;
        }
    }
    controlTo() {
        if (this.from <= this.to) {
            this.toSlider.value = this.to;
            this.toInput.value = this.to;
        }
        else {
            this.toSlider.value = this.from;
            this.toInput.value = this.from;
        }
    }

    controlFromSlider() {
        this.controlSlider();
        this.controlFrom();
        this.fromSlider.dispatchEvent(this.rangeChanged);
    }
    controlToSlider() {
        this.controlSlider();
        this.controlTo();
        this.toSlider.dispatchEvent(this.rangeChanged);
    }
    controlFromInput() {
        this.controlInput();
        this.controlFrom();
        this.fromInput.dispatchEvent(this.rangeChanged);
    }
    controlToInput() {
        this.controlInput();
        this.controlTo();
        this.toInput.dispatchEvent(this.rangeChanged);
    }

    init() {
        let self = this;
        self.getParsed(self.fromSlider, self.toSlider);
        self.fillSlider();
        self.setToggleAccessible(self.toSlider);

        self.fromSlider.addEventListener('input',() => { self.controlFromSlider(); });
        self.fromSlider.addEventListener('change',() => { self.controlFromSlider(); });
        self.toSlider.addEventListener('input',() => { self.controlToSlider(); });
        self.toSlider.addEventListener('change',() => { self.controlToSlider(); });
        self.fromInput.addEventListener('input',() => { self.controlFromInput(); });
        self.fromInput.addEventListener('change',() => { self.controlFromInput(); });
        self.toInput.addEventListener('input',() => { self.controlToInput(); });
        self.toInput.addEventListener('change',() => { self.controlToInput(); });
    }
}

window.addEventListener('load',() => {
    let rangeSliders = document.querySelectorAll('.range_slider');
    rangeSliders.forEach((rs) => {
        rs.rangeSlider = new rangeSlider(rs);
    });
})