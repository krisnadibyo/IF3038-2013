/** User **/
/* requires: madtodo.js */
(function($) {
    /**
     * User object constructor
     * 
     * @param {String} name
     * @param {String} username
     * @param {String} password
     * @param {String} email
     * @param {Object} birthday
     * @param {String} avatar
     */
    $.User = function(name, username, password, email, birthday, avatar) {
        this.name = name;
        this.username = username;
        this.password = password;
        this.email = email;
        this.birthday = birthday;
        this.avatar = avatar;
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
         */
        parseBirthday: function(birthdayString) {
            var bArray = $.UserHelper.rules.birthday.exec(birthdayString).splice(1,3);
            return {
                year: bArray[0],
                month: bArray[1],
                day: bArray[2]
            }
        }
    };

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
                    dsz[i].avatar)
                );
            };

            return users;
        },

        /* Local Storage functions */
        load: function() {
            return $.Users.deserialize($ls['users']);
        },

        save: function(users) {
            $ls['users'] = $.Users.serialize(users);
        },

        clear: function() {
            $ls.removeItem('users');
            return Array();
        }
    }

    /* Session */
    $.Session = {
        login: function(username, password) {
            var users = $.Users.load();

            var user = undefined;
            for (var i = 0; i < users.length; i++) {
                if (users[i]['username'] === username) {
                    user = users[i];
                    break;
                }
            }

            if (user == undefined) {
                console.log('SESSION Error: User not found');
                return false;
            }

            if (user['password'] == password) {
                $ls['logged_in'] = 'true';
                $ls['logged_user'] = JSON.stringify([user]);

                return true;
            }

            console.log('SESSION Error: Incorrect password');
            return false;
        },

        logout: function() {
            if ($ls['logged_in'] == undefined ||
                $ls['logged_user'] == undefined) {
                return false;
            }

            $ls.removeItem('logged_in');
            $ls.removeItem('logged_user');

            return true;
        },

        getLoggedUser: function() {
            return $.Users.deserialize($ls['logged_user'])[0];
        }
    };
})(window);
