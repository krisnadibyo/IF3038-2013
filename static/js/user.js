/** User **/
/* requires: madtodo.js */
(function($) {
    /**
     * Helper for User object.
     */
    $.UserHelper = {
        rules: {
            name: /^[A-Za-z]+ [A-Za-z\. ]+$/,
            username: /^[A-Za-z0-9]{5,}$/,
            password: /^.{8,}$/,
            email: /^[A-Za-z][A-Za-z0-9_\.]*@[A-Za-z0-9\-\.]+\.[A-Za-z]{2,}$/,
            birthday: /^(\d{4})-(\d{2})-(\d{2})$/
        },

        errorMessages: {
            name: 'Name must contain at least first name and last name, no symbols',
            username: 'Min. 5 chars, only alphabetic and numeric are allowed, no symbols',
            password: 'Min. 8 chars',
            email: 'Standard email format',
            birthday: 'Incorrect date format',
            equalUPE: 'Username and/or email must be different with password'
        },

        testRule: function(str, rule) {
            return $.UserHelper.rules[rule].test(str);
        },

        /* Check username, password, and email */
        checkUPE: function(username, password, email) {
            return (username !== password && email !== password)
        },

        /**
         * Create birthday object from date string.
         *
         * @param {String} birthdayString
         * @deprecated
         */
        parseBirthday: function(birthdayString) {
            var bArray = $.UserHelper.rules.birthday.exec(birthdayString).splice(1,3);
            return {
                year: bArray[0],
                month: bArray[1],
                day: bArray[2]
            }
        },
    };

    /* User API */
    $.UserAPI = {
        get: function(callbackfunc, async) {
            return XHR.qGet('user/get', callbackfunc, async)
        },

        save: function(user, callbackfunc, async) {
            var data = {
                name: user['name'],
                email: user['email'],
                birthday: user['birthday'],
                avatar: user['avatar'],
                bio: user['bio']
            };

            return XHR.qPost('user/edit', data, callbackfunc, async);
        },

        register: function(user, callbackfunc, async) {
            var data = {
                name: user['name'],
                username: user['username'],
                password: user['password'],
                email: user['email'],
                birthday: user['birthday'],
                avatar: user['avatar'],
                bio: user['bio']
            };

            return XHR.qPost('user/register', data, callbackfunc, async);
        },

        uploadAvatar: function(user, fileobj, callbackfunc) {
            if (!user || !fileobj) {
                return;
            }

            var username = user['username'];
            XHR.doUpload({
                url: $.AppRoot + 'upload/avatar/' + username,
                fileobj: fileobj,
                callback: function(res) {
                    if (callbackfunc !== undefined) {
                        callbackfunc(res);
                    }
                    console.log(res);
                }
            });
        },

        // Misc getters
        hint: function(username, callbackfunc, async) {
            return XHR.qGet('user/hint/' + username, callbackfunc, async);
        },
        getUserId: function(username, callbackfunc, async) {
            return XHR.qGet('user/getid/' + username, callbackfunc, async);
        }
    }

})(window);
