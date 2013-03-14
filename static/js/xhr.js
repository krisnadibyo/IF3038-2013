(function($) {

    $.XHR =  {
        xhrInit: function(doneCallback, isTextResponse) {
            var _xhr = new XMLHttpRequest();
            _xhr.onreadystatechange = function() {
                if (_xhr.readyState === 4) {
                    var response = _xhr.responseText;
                    if (!isTextResponse) {
                        response = JSON.parse(response);
                    }
                    doneCallback(response, _xhr);
                }
            };
            return _xhr;
        },

        /* oargs: method, url, callback, [data, [jsonData]], [textResponse] */
        doReq: function(oargs) {
            var _xhr;
            if (!oargs['textResponse']) {
                _xhr = XHR.xhrInit(oargs['callback'], false);
            } else {
                _xhr = XHR.xhrInit(oargs['callback'], true);
            }

            _xhr.open(oargs['method'], oargs['url'], true);

            if (!oargs['data']) {
                oargs['data'] = null;
            } else {
                _xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                if (oargs['jsonData']) {
                   oargs['data'] = 'data=' + encodeURIComponent(
                       JSON.stringify(oargs['data'])
                   );
                }
            }

            _xhr.send(oargs['data']);
        },
    };

})(window);
