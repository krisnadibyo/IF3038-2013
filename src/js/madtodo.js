(function($) {
    $.$id = function(id) {
        var e = document.getElementById(id);

        e.attr = function(attr, val) {
            if (val !== undefined) {
                this.setAttribute(attr, val);
            }

            return this.getAttribute(attr);
        }

        e.addClass = function(c) {
            if (this.className === '') {
                this.className += c;
            } else {
                this.className += ' ' + c;
            }
        }

        e.removeClass = function(c) {
            var classes = this.className.split(' ');
            var index = classes.indexOf(c)

            if (index !== -1) {
                classes.splice(index, 1);
                this.className = classes.join(' ');

                return true;
            }

            return false;
        }

        return e;
    };

    $.supportLocalStorage = true;
    if (typeof(window.localStorage) === 'undefined') {
        $.supportLocalStorage = false;

        console.log('*** WARNING! localStorage is not supported on your browser! ***');
        window.localStorage = {};
    }

    $.$ls = window.localStorage;
})(window);
