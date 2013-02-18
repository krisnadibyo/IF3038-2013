(function($) {
    $.$id = function(id) {
        return document.getElementById(id);
    };

    $.supportLocalStorage = true;
    if (typeof(window.localStorage) === 'undefined') {
        $.supportLocalStorage = false;

        console.log('*** WARNING! localStorage is not supported on your browser! ***');
        window.localStorage = {};
    }

    $.$ls = window.localStorage;
})(window);
