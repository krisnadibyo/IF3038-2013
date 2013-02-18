(function($) {
    $.$id = function(id) {
        var e = document.getElementById(id);
        e.attr = function(attr) {
            return this.getAttribute(attr);
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
