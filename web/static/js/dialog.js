(function($) {
    var resizeDialogs = function() {
        var cWidth = document.body.clientWidth;
        // var cHeight = document.body.clientHeight;

        document.body.style.height = window.screen.availHeight + 'px';
        for (var i = 0; i < $.dialogs.length; i++) {
            $.dialogs[i].style.left = (cWidth / 2) - (486 / 2) + 'px';
        }
    };

    resizeDialogs();

    $.onresize = function() {
        resizeDialogs();
    };

    $.closeDialogEx = function(dlg) {
        var dialog = dlg;

        $id('pageBlurrer').doTransition({
            opacity: '0'
        }, 25);
        dialog.doTransition({
            opacity: '0'
        }, 25);

        setTimeout(function() {
            $id('pageBlurrer').style.display = 'none';
            dialog.style.display = 'none';
        }, 250);
    };

    $.closeDialog = function(e) {
        e = $e(e);
        var dialog = $id(e.attr('data-dialogId'));

        $id('pageBlurrer').doTransition({
            opacity: '0'
        }, 25);
        dialog.doTransition({
            opacity: '0'
        }, 25);

        setTimeout(function() {
            $id('pageBlurrer').style.display = 'none';
            dialog.style.display = 'none';
        }, 250);
    };
})(window);
