class CookieConsent {
    constructor() {
        this.banner = document.getElementById('rgpd-compliance');
        this.edit = document.getElementById('rgpd-param');
        this.modal = new Modal('#cookiesModal',{backdrop: 'static',keyboard: false});
        this.btns = {
            'refuse': document.querySelectorAll('.refuseRgpd'),
            'accept': document.querySelectorAll('.acceptRgpd'),
            'save': document.querySelectorAll('.saveRgpd')
        };
        this.cookies = {
            //essentialCookies: true,
            adsCookies: true,
            analyticCookies: true,
            ggWebfontCookies: true,
            adobeWebfontCookies: true,
            ggMapCookies: true,
            embedCookies: true,
            timeZoneOffset: false
        }
    }

    /**
     * Set cookie params
     * @param all boolean
     */
    saveCookies(all = null) {
        let CC = this;
        for (var name in CC.cookies) {
            if (CC.cookies.hasOwnProperty(name)) {
                if (all === null) {
                    let setting = document.querySelector('[name="'+name+'"]');
                    if(setting !== null) {
                        CC.cookies[name] = setting.checked;
                    }
                }
                else {
                    CC.cookies[name] = all;
                }
            }
        }
        CC.modal.hide();
        Cookie.deleteAllCookies();
        CC.createCookies();
    }

    createCookies() {
        Cookie.createCookie('consentAsked',true);
        Cookie.createCookie('consentedCookies',JSON.stringify(this.cookies));
        window.location.reload();
    }

    hideBox() {
        this.banner.classList.remove('in');
        this.banner.classList.add('hide');
    }

    showBox() {
        this.banner.classList.remove('in');
        this.banner.classList.add('hide');
    }

    init() {
        let CC = this;
        if (!Cookie.checkCookie('consentAsked')) {
            Cookie.deleteAllCookies();
            CC.banner.classList.remove('hide');
        }
        if (Cookie.checkCookie('consentAsked')) CC.edit.classList.remove('hide');
        CC.btns.refuse.forEach((btn) => btn.addEventListener('click',() => CC.saveCookies(false)));
        CC.btns.accept.forEach((btn) => btn.addEventListener('click',() => CC.saveCookies(true)));
        CC.btns.save.forEach((btn) => btn.addEventListener('click',() => CC.saveCookies()));
    }
}
window.addEventListener('load', () => {
    if(Cookie !== undefined) {
        let CC = new CookieConsent();
        CC.init();

        if(Cookie.checkCookie('embedCookies') === 'true') {
            // change iframe data-src to src
            document.querySelectorAll('iframe.ytb').forEach((iytb) => {
                iytb.src = iytb.dataset.src;
                iytb.removeAttribute('data-src');
            });
        }

        /*if(Cookie.checkCookie('timeZoneOffset') === 'true') {
            let now = new Date();
            let offset = now.getTimezoneOffset();
            if(Cookie.checkCookie('TimeZoneOffset') !== offset.toString()) {
                Cookie.createCookie('TimeZoneOffset',offset.toString());
                window.location.reload();
            }
        }*/
    }
});