(function($) {

    $.user = UserAPI.get(null, false); // Synchronously
    document.title = 'Profile - ' + user['name'];

    $.dialogs = new Array(
        $id('editProfileForm')
    );

    var userTasks = TaskAPI.getUserTasks(null, false);
    var doneUserTasks = [];
    var unfinishedUserTasks = [];

    for (var i = 0; i < userTasks.length; i++) {
        if (userTasks[i].status == 0) {
            unfinishedUserTasks.push(userTasks[i]);
        } else {
            doneUserTasks.push(userTasks[i]);
        }
    }

    $id('name').html(user['name']);
    $id('username').html('Username: <strong>' + user['username'] + '</strong>');
    $id('email').html('Email: <em>' + user['email'] + '</em>');
    $id('birthday').html('Birthday: <strong>' + user['birthday'] + '</strong>');
    $id('avaImg').src = '/static/uploads/avatar/' + user['avatar'];
    $id('bio').html('Bio: <p><em>' + (user['bio'] == '' ? 'This user has no bio' : user['bio']) + '</em></p>');

    for (var i = 0; i < doneUserTasks.length; i++) {
        var e = $e.create('li').html(doneUserTasks[i]['name']);
        $id('doneUserTasks').appendChild(e);
    }

    for (var i = 0; i < unfinishedUserTasks.length; i++) {
        var e = $e.create('li').html(unfinishedUserTasks[i]['name']);
        $id('unfinishedUserTasks').appendChild(e);
    }

    var inputIds = ['name', 'email', 'birthday', 'bio', 'avatar', 'password'];
    var editInputs = {};
    for (var i = 0; i < inputIds.length; i++) {
        editInputs[inputIds[i]] = $id('edit_' + inputIds[i]);
    }

    $id('avatarFile').onchange = function(e) {
        $.avatarFile = this.files[0];
        $id('edit_avatar').val(this.val());

        var reader = new FileReader();
        reader.onload = function(evt) {
            $.avatarImg = evt.target.result;
            $id('avatarImg').src = $.avatarImg;
        };

        $id('uploadAvatarDiv').style.height = '260px';
        $id('avatarImg').style.display = 'block';

        reader.readAsDataURL($.avatarFile);
    };

    // Validation
    var unlockEdit = function() {
        var unlock = true;

        for (key in editInputs) {
            var e = editInputs[key];

            if (e.attr('data-rule') && unlock) {
                unlock = UserHelper.testRule(e.val(), e.attr('data-rule'));
            } else {
                break;
            }
        }

        return unlock;
    };

    var addErrorMessage = function(e, message) {
        if (!e.hasClass('error')) {
            var errorDiv = $e.create('div')
                .addClass('errorMessage')
                .html(message);

            e.addClass('error')
                .parentNode.insertBefore(errorDiv, e);

            errorDiv.doTransition({ margin: '0 0 0 -40px', opacity: '1.0' }, 25);
        }
    };

    var removeErrorMessage = function(e) {
        if (e.hasClass('error')) {
            e.removeClass('error')
                .parentNode.removeChild(e.parentNode.children[1]);
        }
    };

    var checkEditInput = function(e) {
        if (unlockEdit()) {
            $id('editSubmitButton').removeAttr('disabled');
        } else {
            $id('editSubmitButton').attr('disabled', 'true');
        }

        if(!UserHelper.testRule(e.val(), e.attr('data-rule'))) {
            addErrorMessage(e, UserHelper.errorMessages[e.attr('data-rule')]);
            return false;
        } else {
            removeErrorMessage(e);
            return true;
        }
    };

    for (key in editInputs) {
        var e = editInputs[key];

        e.onkeyup = function() {
            if (this.attr('data-rule')) {
                if (this.attr('data-rule') == 'password' && this.val() == "") {

                }
                else {
                    checkEditInput(this);
                }
            }
        };

        if (e.attr('data-rule') && e.val() !== '') {
            checkEditInput(e);
        }
    }

    $.showEditProfileForm = function() {
        $id('editProfileForm').style.display = 'block';
        $id('pageBlurrer').style.display = 'block';

        $id('editProfileForm').doTransition({
            'opacity': '1.0'
        }, 25);
        $id('pageBlurrer').doTransition({
            'opacity': '0.4'
        }, 25);

        editInputs['name'].val(user['name']);
        editInputs['email'].val(user['email']);
        editInputs['birthday'].val(user['birthday']);
        editInputs['bio'].html(user['bio']);
    };

    // Form submit
    // Edit
    $id('editSubmitButton').onclick = function(e) {
        var error = false;

        for (var key in editInputs) {
            if (key == "password") {
                if (e.val() == "") {
                    continue;
                }
            }

            var e = editInputs[key];
            if (e.attr('data-rule') && !checkEditInput(e)) {
                error = true;
            }
        }

        if (error) {
            return;
        }

        $id('editSubmitButton').attr('disabled', '').html('Loading...');

        var user = {
            name:       editInputs['name'].val(),
            password:   editInputs['password'].val(),
            email:      editInputs['email'].val(),
            birthday:   editInputs['birthday'].val(),
            avatar:     editInputs['avatar'].val(),
            bio:        editInputs['bio'].val()
        };

        // Check for avatar and parse the extension
        var ext = /^.*\.(.*)$/.exec(user.avatar);
        if (!ext) {
            user.avatar = 'none';
        } else {
            user.avatar = user.username + '.' + ext[1];
        }

        console.log(user);
        UserAPI.save(user, function(res) {
            if (res['status'] !== 'success') {
                for (key in res) {
                    addErrorMessage(editInputs[key], res[key]);
                }
                $id('editSubmitButton').removeAttr('disabled').html('Edit');
                return;
            }

            if (user.avatar !== 'none') {
                UserAPI.uploadAvatar(user, $id('avatarFile').files[0], function(res) {
                    alert("Edit profile success!");
                    window.open(AppRoot + "page/profile", "_self");
                });
            } else {
                alert("Edit profile success!");
                window.open(AppRoot + "page/profile", "_self");
            }
        });
    };

})(window);
