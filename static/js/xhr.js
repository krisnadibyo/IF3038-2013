(function($) {

    $.XHR =  {
        xhrInit: function(doneCallback, isTextResponse, async) {
            var _xhr = new XMLHttpRequest();
            if (async) {
                _xhr.onreadystatechange = function() {
                    if (_xhr.readyState === 4) {
                        var response = _xhr.responseText;
                        if (!isTextResponse) {
                            response = JSON.parse(response);
                        }
                        doneCallback(response, _xhr);
                    }
                };
            }
            return _xhr;
        },

        // oargs: method, url, callback, [data, [jsonData]], [textResponse]
        doReq: function(oargs) {
            var _xhr;

            if (oargs['async'] === false) {
                async = false;
            } else {
                async = true;
            }

            if (!oargs['textResponse']) {
                _xhr = XHR.xhrInit(oargs['callback'], false, async);
            } else {
                _xhr = XHR.xhrInit(oargs['callback'], true, async);
            }

            _xhr.open(oargs['method'], oargs['url'], async);

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
            if (!async) {
                if (!oargs['textResponse']) {
                    return JSON.parse(_xhr.responseText);
                } else {
                    return _xhr.responseText;
                }
            }
        },

        // oargs: url, callback, fileobj
        doUpload: function(oargs) {
            var _xhr;
            var fd = new FormData();

            if (!oargs['textResponse']) {
                _xhr = XHR.xhrInit(oargs['callback'], false);
            } else {
                _xhr = XHR.xhrInit(oargs['callback'], true);
            }

            _xhr.open('POST', oargs['url'], true);
            //_xhr.setRequestHeader('Content-Type', 'multipart/form-data');
            fd.append('fileobj', oargs['fileobj']);
            _xhr.send(fd);
        },

        // Quick GET
        qGet: function(url, callbackfunc, async) {
            if (async !== false) {
                async = true;
            }

            return XHR.doReq({
                async: async,
                method: 'GET',
                url: AppRoot + url,
                callback: function(res) {
                    if (callbackfunc !== null && callbackfunc !== undefined) {
                        callbackfunc(res);
                    }
                }
            });
        },

        // Quic POST
        qPost: function(url, data, callbackfunc, async) {
            if (async !== false) {
                async = true;
            }

            return XHR.doReq({
                async: async,
                method: 'POST',
                url: AppRoot + url,
                jsonData: true,
                data: data,
                callback: function(res) {
                    if (callbackfunc !== null && callbackfunc !== undefined) {
                        callbackfunc(res);
                    }
                }
            });
        }
    };

})(window);
