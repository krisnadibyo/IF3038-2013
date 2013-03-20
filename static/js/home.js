(function($) {
    // Initialize
    $.avatarFile = undefined;
    $.avatarImg = undefined;

    var inputIds = ['name', 'username', 'password', 'email', 'birthday', 'avatar', 'bio'];
    var signupInputs = {};
    for (var i = 0; i < inputIds.length; i++) {
        signupInputs[inputIds[i]] = $id('signup_' + inputIds[i]);
    }

    // Birthday
    var bday = {
        Y: $id('bdYear'),
        M: $id('bdMonth'),
        D: $id('bdDay')
    };

    var populateOptions = function(selectNode, from, to, selectedIndex) {
        for (var i = from; i <= to; i++) {
            var val = (i < 10) ? '0' + (i).toString() : i;

            var e = $e.create('option').val(val).html(val);
            if (i == selectedIndex) {
                e.attr('selected', true);
            }

            selectNode.appendChild(e);
        }
    }

    populateOptions(bday.Y, 1955, 2055, 1990);
    populateOptions(bday.M, 1, 12, 1);
    populateOptions(bday.D, 1, 31, 1);
    for (key in bday) {
        bday[key].onchange = function(e) {
            $id('signup_birthday').val(bday.Y.val() + '-' + bday.M.val() + '-' + bday.D.val());
        }
    }
    $id('signup_birthday').val(bday.Y.val() + '-' + bday.M.val() + '-' + bday.D.val());

    // Validation
    var unlockSignup = function() {
        var unlock = true;

        for (key in signupInputs) {
            var e = signupInputs[key];

            if (e.attr('rule') && unlock) {
                unlock = UserHelper.testRule(e.val(), e.attr('rule'));
            } else {
                break;
            }
        }

        return unlock;
    }

    var addErrorMessage = function(e, message) {
        if (!e.hasClass('error')) {
            var errorDiv = $e.create('div')
                .addClass('errorMessage')
                .html(message);

            e.addClass('error')
                .parentNode.insertBefore(errorDiv, e);

            errorDiv.doTransition({ margin: '0 0 0 -40px', opacity: '1.0' }, 25);
        }
    }

    var removeErrorMessage = function(e) {
        if (e.hasClass('error')) {
            e.removeClass('error')
                .parentNode.removeChild(e.parentNode.firstChild);
        }
    }

    var checkSignupInput = function(e) {
        if (unlockSignup()) {
            $id('signUpButton').removeAttr('disabled');
        } else {
            $id('signUpButton').attr('disabled', 'true');
        }

        if(!UserHelper.testRule(e.val(), e.attr('rule'))) {
            addErrorMessage(e, UserHelper.errorMessages[e.attr('rule')]);
            return false;
        } else {
            removeErrorMessage(e);
            return true;
        }
    }

    for (key in signupInputs) {
        var e = signupInputs[key];

        e.onkeyup = function() {
            if (this.attr('rule')) {
                checkSignupInput(this);
            }
        }

        if (e.attr('rule') && e.val() !== '') {
            checkSignupInput(e);
        }
    }

    // Form open
    var openForm = function(placeholderEl, formEl) {
        placeholderEl.onclick = function(e) {
            placeholderEl.style.display = 'none';

            formEl.style.display = 'block';
            formEl.doTransition({
                opacity: '1.0',
            }, 25);
        }
    }

    openForm($id('signUpPlaceholder'), $id('signUpForm'));
    openForm($id('signInPlaceholder'), $id('signInForm'));

    $id('avatarFile').onchange = function(e) {
        $.avatarFile = this.files[0];
        $id('signup_avatar').val(this.val());

        var reader = new FileReader();
        reader.onload = function(evt) {
            $.avatarImg = evt.target.result;
            $id('avatarImg').src = $.avatarImg;
        }

        $id('uploadAvatarDiv').style.height = '260px';
        $id('avatarImg').style.display = 'block';

        reader.readAsDataURL($.avatarFile);
    }

    // Form submit
    // SIGN UP
    $id('signUpButton').onclick = function(e) {
        var error = false;

        for (var key in signupInputs) {
            var e = signupInputs[key];
            if (e.attr('rule') && !checkSignupInput(e)) {
                error = true;
            }
        }

        if (error) {
            return;
        }

        $ls[signupInputs['avatar'].val()] = $.avatarImg;
        var user = new User(
            0, // id
            signupInputs['name'].val(),
            signupInputs['username'].val(),
            signupInputs['password'].val(),
            signupInputs['email'].val(),
            signupInputs['birthday'].val(),
            signupInputs['avatar'].val(),
            signupInputs['bio'].val()
        );

        // Check for avatar and parse the extension
        var ext = /^.*\.(.*)$/.exec(user.avatar);
        if (!ext) {
            user.avatar = 'none';
        } else {
            user.avatar = user.username + '.' + ext[1];
        }

        console.log(user);
        UserAPI.register(user, function(res) {
            if (res['status'] !== 'success') {
                for (key in res) {
                    addErrorMessage(signupInputs[key], res[key]);
                }
                return;
            }

            if (user.avatar != 'none') {
                UserAPI.uploadAvatar(user, $id('avatarFile').files[0], function(res) {
                    doSignIn(user.username, user.password);
                });
            } else {
                doSignIn(user.username, user.password);
            }
        });
    }

    // SIGN IN
    $id('signInButton').onclick = function(e) {
        var username = $id('signin_username').val();
        var password = $id('signin_password').val();

        doSignIn(username, password, function(res) {
            if (res == null) {
                addErrorMessage($id('signin_username'), "Invalid username or password");
                $id('signin_password').addClass('error');
            } else {
                $.open($.AppRoot + 'page/dashboard', '_self');
            }
        });

    }

    // functions
    var doSignIn = function(username, password, callbackfunc) {
        var data = {
            username: username,
            password: password
        }

        $.XHR.doReq({
            method: 'POST',
            url: $.AppRoot + 'auth/login',
            jsonData: true,
            data: data,
            callback: function(res) {
                if (callbackfunc !== undefined) {
                    callbackfunc(res);
                } else {
                    if (res['status'] === 'success') {
                        $.open($.AppRoot + 'page/dashboard', '_self');
                    }
                }
            }
        });
    }
})(window);
