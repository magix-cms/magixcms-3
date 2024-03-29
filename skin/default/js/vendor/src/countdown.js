class Countdown {
    constructor(element,options) {
        this.element = element;
        this.display = {
            days: this.element.querySelector('.days'),
            hours: this.element.querySelector('.hours'),
            minutes: this.element.querySelector('.minutes'),
            seconds: this.element.querySelector('.seconds')
        };
        this.timer = {
            days: null,
            hours: null,
            minutes: null,
            seconds: null
        };
        this.interval = null;
        this.dateEnd = null;
        this.timeEnd = null;
        if(typeof options === 'object') this.set(options);
        this.event = {
            start: new Event('start', {
                bubbles: true,
                cancelable: true,
                composed: false
            }),
            end: new Event('end', {
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

    calculate(self) {
        if(self instanceof Countdown) {
            let dateStart = new Date();
            dateStart = new Date(dateStart.getUTCFullYear(),
                dateStart.getUTCMonth(),
                dateStart.getUTCDate(),
                dateStart.getUTCHours(),
                dateStart.getUTCMinutes(),
                dateStart.getUTCSeconds());
            let timeRemaining = parseInt((self.timeEnd - dateStart.getTime()) / 1000);

            if (timeRemaining >= 0) {
                self.timer.days = parseInt(timeRemaining / 86400);
                timeRemaining = (timeRemaining % 86400);
                self.timer.hours = parseInt(timeRemaining / 3600);
                timeRemaining = (timeRemaining % 3600);
                self.timer.minutes = parseInt(timeRemaining / 60);
                timeRemaining = (timeRemaining % 60);
                self.timer.seconds = parseInt(timeRemaining);

                if(self.display.days !== null) {
                    if(!self.timer.days) self.display.days.classList.add('hide');
                    else self.display.days.innerHTML = parseInt(self.timer.days, 10) + self.display.days.dataset.label;
                }
                if(self.display.hours !== null) {
                    if(!self.timer.days && !self.timer.hours) self.display.hours.classList.add('hide');
                    else self.display.hours.innerHTML = ("0" + self.timer.hours).slice(-2) + self.display.hours.dataset.label;
                }
                if(self.display.minutes !== null) {
                    if(!self.timer.days && !self.timer.hours && !self.timer.minutes) self.display.minutes.classList.add('hide');
                    else self.display.minutes.innerHTML = ("0" + self.timer.minutes).slice(-2) + self.display.minutes.dataset.label;
                }
                if(self.display.seconds !== null) {
                    if(!self.timer.days && !self.timer.hours && !self.timer.minutes && !self.timer.seconds) self.display.seconds.classList.add('hide');
                    else self.display.seconds.innerHTML = ("0" + self.timer.seconds).slice(-2) + self.display.seconds.dataset.label;
                }

                if(!self.timer.days) {
                    self.element.classList.remove('endsoveraday');

                    if(self.timer.hours > 12) self.element.classList.add('endsinhalfday');
                    else {
                        self.element.classList.remove('endsinhalfday');
                        self.element.classList.add('endssoon');

                        if(!self.timer.hours && !self.timer.minutes && !self.timer.seconds) this.element.dispatchEvent(this.event.end);
                    }
                }
                else {
                    self.element.classList.add('endsoveraday');
                }
            }
        }
    }

    init() {
        let self = this;
        this.dateEnd = this.element.dataset.dateEnd;

        if(this.dateEnd !== undefined) {
            this.dateEnd = new Date(this.dateEnd);
            this.timeEnd = this.dateEnd.getTime();

            if ( isNaN(this.timeEnd) ) return;

            this.interval = setInterval(() => {
                self.calculate(self);
            }, 1000);
        }
    }
}

window.addEventListener('load',() => {
    let countdowns = document.querySelectorAll('.countdown');
    countdowns.forEach((cd) => {
        cd.countdown = new Countdown(cd);
    });
})