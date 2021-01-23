/* 
 * @license http://opensource.org/licenses/MIT MIT
 */


class RdbCMSFLanguageSwitcher {


    /**
     * Listen on click link to switch language.
     * 
     * @returns {undefined}
     */
    listenClickSwitchLanguage() {
        document.addEventListener('click', function(event) {
            let target = event.currentTarget.activeElement;
            if (target.classList.contains('language-switch-link')) {
                event.preventDefault();

                let formData = new FormData();
                formData.append('currentUrl', LanguageSwitcherObject.currentUrl);
                formData.append('rundizbones-languages', target.dataset.rundizbonesLanguages);

                let XHR = new XMLHttpRequest();
                XHR.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Typical action to be performed when the document is ready:
                        let response = XHR.response;
                        if (typeof(response.redirectUrl) !== 'undefined') {
                            window.location = response.redirectUrl;
                        }
                    }
                };
                XHR.responseType = 'json';
                XHR.open(LanguageSwitcherObject.setLanguage_method, LanguageSwitcherObject.setLanguage_url);
                XHR.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                XHR.setRequestHeader('Accept', 'application/json');
                XHR.send(new URLSearchParams(formData));
            }
        });
    }// listenClickSwitchLanguage


}


document.addEventListener('DOMContentLoaded', function() {
    let LanguageSwitcherClass = new RdbCMSFLanguageSwitcher();
    LanguageSwitcherClass.listenClickSwitchLanguage();
});