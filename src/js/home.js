(function($) {
    /** SIGNUP **/
    $.avatarFile = undefined;
    $.avatarImg = undefined;

    var inputIds = ['name', 'username', 'password', 'email', 'birthday', 'avatar', 'bio'];

    var signupInputs = {};
    for (var i = 0; i < inputIds.length; i++) {
        signupInputs[inputIds[i]] = $id('signup_' + inputIds[i]);
    }

    /* Birthday */
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

    /* Check Rules */
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

    var checkSignupInput = function(e) {
        console.log(unlockSignup());
        if (unlockSignup()) {
            $id('signUpButton').removeAttr('disabled');
        } else {
            $id('signUpButton').attr('disabled', 'true');
        }

        if(!UserHelper.testRule(e.val(), e.attr('rule'))) {
            if (!e.hasClass('error')) {
                var errorDiv = $e.create('div')
                    .addClass('errorMessage')
                    .html(UserHelper.errorMessages[e.attr('rule')]);

                e.addClass('error')
                    .parentNode.insertBefore(errorDiv, e);

                errorDiv.doTransition({ margin: '0 0 0 -40px', opacity: '1.0' }, 25);
            }
            return false;
        } else {
            if (e.hasClass('error')) {
                e.removeClass('error')
                    .parentNode.removeChild(e.parentNode.firstChild);
            }
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

    }

    $id('signUpPlaceholder').onclick = function(e) {
        this.style.display = 'none';

        $id('signUpForm').style.display = 'block';
        $id('signUpForm').doTransition({
            opacity: '1.0',
        }, 25);
    }

    $id('avatarFile').onchange = function(e) {
        $.avatarFile = this.files[0];
        $id('signup_avatar').val('Avatar_' + this.val());

        var reader = new FileReader();
        reader.onload = function(evt) {
            $.avatarImg = evt.target.result;
            $id('avatarImg').src = $.avatarImg;
        }

        $id('uploadAvatarDiv').style.height = '260px';
        $id('avatarImg').style.display = 'block';

        reader.readAsDataURL($.avatarFile);
    }
})(window);
