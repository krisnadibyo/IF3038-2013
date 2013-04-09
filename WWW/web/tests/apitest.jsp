<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>API Test</title>
    <script type="text/javascript" src="/static/js/madtodo.js"></script>
    <script type="text/javascript" src="/static/js/xhr.js"></script>
    <style type="text/css">@import url("/static/css/maincss.css");</style>
    <style>
        body {
            margin: 10px;
        }
        #apiTestForm {
            width: 420px;
            float: left;
        }
        #responseDiv {
            margin: 4px;
            float: left;
        }
        #responseBox, #apiList > pre {
            border: 2px solid #444;
            width: 400px;
            height: 500px;
            padding: 10px;
            background: #fff;
            overflow: auto;
        }
        #apiList {
            float: left;
            margin: 4px;
        }
    </style>
</head>
<body>
    <form id="apiTestForm" action="javascript:;">
        <label>Method: </label>
        <select id="method">
            <option value="POST" selected>POST</option>
            <option value="GET">GET</option>
            <option value="POST-X">POST (UPLOAD)</option>
        </select><br />
        <label>REST URL:</label>
        <input id="url" type="text" placeholder="e.g., /auth/login"  />
        <label>Data: (POST only, in JSON)</label>
        <textarea id="data">{"username": "valjean", "password": "valjean123"}</textarea><br />
        <input style="display: none" type="file" id="fileobj" name="fileobj" /><br />
        <button id="sendButton">Send</button>
    </form>
    <div id="responseDiv">
        Response:
        <pre id="responseBox"></pre>
    </div>
    <div id="apiList">
        API List:
        <pre><%  %></pre>
    </div>
    <div class="clear"></div>

<script type="text/javascript">
(function($) {

    $id('method').onchange = function(e) {
        if ($id('method').val() == 'POST-X') {
            $id('fileobj').style.display = 'block';
            $id('data').style.display = 'none';
        } else {
            $id('fileobj').style.display = 'none';
            $id('data').style.display = 'inline';
        }
    }

    $id('apiTestForm').onsubmit = function(e) {
        var method = $id('method').val();
        var url = $id('url').val();
        var data = JSON.parse($id('data').val());

        if (method == 'GET') {
            $.XHR.doReq({
               method: 'GET',
               url: url,
               callback: function(res) {
                   $id('responseBox').html(JSON.stringify(res, null, '  '));
               }
            });
        }
        else if (method == 'POST') {
            $.XHR.doReq({
                method: 'POST',
                url: url,
                jsonData: true,
                data: data,
                callback: function(res) {
                    $id('responseBox').html(JSON.stringify(res, null, '  '));
                }
            })
        }
        else if (method == 'POST-X') {
            $.XHR.doUpload({
                url: url,
                fileobj: $id('fileobj').files[0],
                callback: function(res) {
                    $id('responseBox').html(JSON.stringify(res, null, '  '));
                }
            })
        }
        console.log(data);
    }

})(window);
</script>

</body>
</html>
