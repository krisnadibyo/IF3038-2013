/** MadToDo JS Lib **/
/* requires: A web browser */
(function($) {
    /**
     * Enrich the element object.
     * 
     * @param {Object} e
     */
    $.$e = function(e) {
        e.attr = function(attr, val) {
            if (val !== undefined) {
                this.setAttribute(attr, val);
                return e;
            }

            return this.getAttribute(attr);
        }

        e.removeAttr = function(attr) {
            this.removeAttribute(attr);

            return e;
        }

        e.addClass = function(c) {
            var classes = this.className.split(' ');
            if (classes.indexOf(c) !== -1) {
                return e;
            }

            if (this.className === '') {
                this.className += c;
            } else {
                this.className += ' ' + c;
            }
        
            return e;
        }

        e.removeClass = function(c) {
            var classes = this.className.split(' ');
            var index = classes.indexOf(c)

            if (index !== -1) {
                classes.splice(index, 1);
                this.className = classes.join(' ');
            }

            return e;
        }

        e.hasClass = function(c) {
            var classes = this.className.split(' ');
            return (classes.indexOf(c) !== -1);
        }

        e.html = function(h) {
            if (h !== undefined) {
                this.innerHTML = h;
                return e;
            }

            return this.innerHTML;
        }
        
        e.val = function(v) {
            if (this.val === undefined) {
                return e;
            }

            if (v !== undefined) {
                this.value = v;
                return e;
            }

            return this.value;
        }

        e.doTransition = function(styles, time, delay) {
            if (delay !== undefined) {
                setTimeout(function() { e.doTransition(styles, time); }, delay);
                return e;
            }

            for (key in styles) {
                this.style[key] = styles[key];
            }

            return e;            
        }

        return e;
    }

    $.$e.create = function(type) {
        return $.$e(document.createElement(type));
    }

    /**
     * Shortcut of document.getElementById() with enriched element object.
     * 
     * @param {String} id
     */
    $.$id = function(id) {
        var e = document.getElementById(id);
        return $.$e(e);
    };

    $.supportLocalStorage = true;
    if (typeof(window.localStorage) === 'undefined') {
        $.supportLocalStorage = false;

        console.log('*** WARNING! localStorage is not supported on your browser! ***');
        window.localStorage = {};
    }

    $.$ls = window.localStorage;
})(window);
