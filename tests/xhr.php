<?php
require_once dirname(__FILE__) . '/../core/Core.php';
import('core.lib.ViewHelper');

header('Content-Type: text/html');
?><!DOCTYPE html>
<html>
<head>
    <title>XHR Test</title>
    <script type="text/javascript" src="<?php echo vh_link('page/approot_js'); ?>"></script>
    <script type="text/javascript" src="<?php echo vh_slink('js/madtodo.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo vh_slink('js/xhr.js'); ?>"></script> 
</head>
<body>
    <h1>XHR (Ajax) Test</h1>
    <pre id="output"></pre>

<script type="text/javascript">
(function($) {
    $.XHR.doReq({
        method: 'GET',
        url: $.AppRoot + 'hello/search_msg/the',
        callback: function(res) {
            var content = '';
            for (var i = 0; i < res.length; i++)
            {
                content +=
                    'ID:  ' + res[i]['id'] + '\n' +
                    'MSG: ' + res[i]['msg'] + '\n\n';
            }
            $id('output').html(content);
        }
    });
})(window);
</script>

</body>
</html>