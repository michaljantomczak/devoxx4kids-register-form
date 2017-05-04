(function (win) {
    var buttons = win.document.getElementsByClassName("action-duplicate");

    if (win.Element && !Element.prototype.closest) {
        Element.prototype.closest =
            function(s) {
                var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                    i,
                    el = this;
                do {
                    i = matches.length;
                    while (--i >= 0 && matches.item(i) !== el) {};
                } while ((i < 0) && (el = el.parentElement));
                return el;
            };
    }

    function attachAddEventListeners(elements) {
        Object.keys(elements).forEach(function(i) {
            elements[i].addEventListener('click', addFieldset, false);
        });
    }

    function addFieldset(event) {
        event.preventDefault();

        var fieldset = this.closest('fieldset'),
            fieldsetCopy = fieldset.cloneNode(true),
            newAddButtons = fieldsetCopy.getElementsByClassName("action-duplicate");

        attachAddEventListeners(newAddButtons);
        fieldset.parentNode.insertBefore(fieldsetCopy, fieldset);
    }

    attachAddEventListeners(buttons);
})(window);