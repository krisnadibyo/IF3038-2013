/** User **/
(function($) {
    /**
     * User class constructor
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
            password: /.{8,}$/,
            email: /^[A-Za-z][A-Za-z0-9_\.]*@[A-Za-z0-9\-\.]+\.[A-Za-z]{2,}$/,
            birthday: /(\d{4})-(\d{2})-(\d{2})/
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
            var bArray = UserHelper.rules.birthday.exec(birthdayString).splice(1,3);
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
            if (serializedTasks === undefined || serializedTasks === '') {
                return Array();
            }

            var dsz = JSON.parse(serializedUsers);
            var users = [];

            for (var i = 0; i < dsz.length; i++) {
                users.push(
                    dsz[i].name,
                    dsz[i].username,
                    dsz[i].password,
                    dsz[i].email,
                    dsz[i].birthday,
                    dsz[i].avatar
                );
            };

            return users;
        }
    }
})(window);
