/** User **/
/* requires: madtodo.js */
(function($) {
    /**
     * User object constructor
     *
     * @param {Integer} id
     * @param {String} name
     * @param {String} username
     * @param {String} password
     * @param {String} email
     * @param {String} birthday
     * @param {String} avatar
     * @param {String} bio
     */
    $.User = function(id, name, username, password, email, birthday, avatar, bio) {
        if (!id) {
            id = 0;
        }

        this.id = id;
        this.name = name;
        this.username = username;
        this.password = password;
        this.email = email;
        this.birthday = birthday;
        this.avatar = avatar;
        this.bio = bio;
    }

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
        get: function(callbackfunc) {
            $.XHR.doReq({
                method: 'GET',
                url: $.AppRoot + 'user/get',
                callback: function(res) {
                    if (callbackfunc !== undefined) {
                        callbackfunc(res);
                    }
                    console.log(res);
                }
            });
        },

        save: function(user, callbackfunc) {
            var data = {
                name: user.name,
                email: user.email,
                birthday: user.birthday,
                avatar: user.avatar,
                bio: user.bio
            };

            $.XHR.doReq({
                method: 'POST',
                url: $.AppRoot + 'user/edit',
                jsonData: true,
                data: data,
                callback: function(res) {
                    if (callbackfunc !== undefined) {
                        callbackfunc(res);
                    }
                    console.log(res);
                }
            });
        },

        register: function(user, callbackfunc) {
            var data = {
                name: user.name,
                username: user.username,
                password: user.password,
                email: user.email,
                birthday: user.birthday,
                avatar: user.avatar,
                bio: user.bio
            };

            $.XHR.doReq({
                method: 'POST',
                url: $.AppRoot + 'user/register',
                jsonData: true,
                data: data,
                callback: function(res) {
                    if (callbackfunc !== undefined) {
                        callbackfunc(res);
                    }
                    console.log(res);
                }
            });
        },

        uploadAvatar: function(user, fileobj, callbackfunc) {
            if (!user || !fileobj) {
                return;
            }

            var username = user.username;
            $.XHR.doUpload({
                url: $.AppRoot + 'upload/avatar/' + username,
                fileobj: fileobj,
                callback: function(res) {
                    if (callbackfunc !== undefined) {
                        callbackfunc(res);
                    }
                    console.log(res);
                }
            });
        }
    }

    /**
     * Users - Handle array of users
     */
    $.Users = {
        /**
         * Serialize an array of user objects into JSON string format.
         *
         * @param {Array} users
         */
        serialize: function(users) {
            return JSON.stringify(users);
        },

        /**
         * Deserialize a serialized array of JSON string formatted users into
         * array of user objects.
         *
         * @param {String} serializedUsers
         */
        deserialize: function(serializedUsers) {
            if (serializedUsers === undefined || serializedUsers === '') {
                return Array();
            }

            var dsz = JSON.parse(serializedUsers);
            var users = [];

            for (var i = 0; i < dsz.length; i++) {
                users.push(new User(
                    dsz[i].name,
                    dsz[i].username,
                    dsz[i].password,
                    dsz[i].email,
                    dsz[i].birthday,
                    dsz[i].avatar,
                    dsz[i].bio)
                );
            };

            return users;
        },

        /* API */
        loadAll: function() {
            $.XHR.doReq({
                method: 'GET',
                url: $.AppRoot + 'user/all/samantha',
                textResponse: true,
                callback: function(res) {
                    $.users = $.Users.deserialize(res);
                    alert("Users loaded!");
                }
            });
        },
    }

})(window);
