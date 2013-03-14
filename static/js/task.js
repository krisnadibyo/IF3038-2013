/** Task **/
/* requires: madtodo.js */
(function($) {
    /**
     * Task object constructor.
     * 
     * @param {String} name
     * @param {String} attachment
     * @param {String} deadline (in date format %Y-%m-%d)
     * @param {String} assignee
     * @param {Array} tags (array of tags, can be set using `setTags`)
     */
    $.Task = function(owner, category, name, attachment, deadline, assignee, tags, status) {
        if (category === undefined || category === '') {
            category = 'Uncategorized';
        }
        this.owner = owner; // username
        this.category = category;
        this.name = name; // max 25 chars, [A-Za-z0-9 ]
        this.attachment = attachment;
        this.deadline = deadline; // Date %Y-%m-%d
        this.assignee = assignee; // username, whatever
        this.tags = tags; // array
        this.status = status; // done or not done yet
    }

    /**
     * Set tags from tags string. Tags will be stored in array (set).
     * 
     * @param {String} tagsString
     */
    $.Task.prototype.setTags = function(tagsString) {
        if (tagsString === undefined || tagsString === '') {
            this.tags = Array();
            return;
        }

        var tStr = tagsString.replace(/,\s+/g, ',');
        var tArr = Array();

        tStr.split(',').forEach(function(tag) {
            if (tArr.indexOf(tag) === -1 && tag !== '') {
                tArr.push(tag);
            }
        });

        this.tags = tArr;
    }

    /**
     * Get formatted tags string from tags array.
     */
    $.Task.prototype.getTags = function() {
        var t = '';
        var len = this.tags.length

        for (var i = 0; i < len; i++) {
            t += this.tags[i];
            if (i < len - 1) {
                t += ', ';
            }
        }

        return t;
    }

    /**
     * TaskHelper, deadline, assignee, tags) {
        this.name = name;
     */
    $.TaskHelper = {
        rules: {
            owner: /^.+$/,
            category: /^[A-Za-z0-9 ]{0,25}$/,
            name: /^[A-Za-z0-9 ]{1,25}$/,
            date: /^(\d{4})-(\d{2})-(\d{2})$/
        },

        errorMessages: {
            owner: "Required",
            category: "Not Required. Max. 25 chars, only alphabetic, numeric, and space are allowed",
            name: "Required. Max. 25 chars, only alphabetic, numeric, and space are allowed",
            date: "Required. Incorrect date format, must be in YYYY-MM-DD"
        },

        testRule: function(str, rule) {
            return $.TaskHelper.rules[rule].test(str);
        }
    };

    /**
     * Tasks - Handle array of tasks
     */
    $.Tasks = {
        /**
         * Serialize an array of task objects into JSON string format.
         * 
         * @param {Array} tasks
         */
        serialize: function(tasks) {
            return JSON.stringify(tasks);
        },

        /**
         * Deserialize a serialized array of JSON string formatted tasks into array of
         * task objects.
         * 
         * @param {String} serializedTasks
         */
        deserialize: function(serializedTasks) {
            if (serializedTasks === undefined || serializedTasks === '') {
                return Array();
            }

            var dsz = JSON.parse(serializedTasks);
            var tasks = [];

            for (var i = 0; i < dsz.length; i++) {
                tasks.push(new Task(
                    dsz[i].owner,
                    dsz[i].category,
                    dsz[i].name,
                    dsz[i].attachment,
                    dsz[i].deadline,
                    dsz[i].assignee,
                    dsz[i].tags,
                    dsz[i].status)
                );
            }

            return tasks;
        },

        getByCategory: function(tasks, category) {
            var sTasks = [];

            for (var i = 0; i < tasks.length; i++) {
                if (tasks[i].category == category) {
                    sTasks.push(tasks[i]);
                }
            }

            return sTasks;
        },

        /* Search/Filter functions */
        getOwnerTasks: function(tasks, owner) {
            var ownerTasks = [];

            for (var i = 0; i < tasks.length; i++) {
                if (tasks[i].owner == owner) {
                    ownerTasks.push(tasks[i]);
                }
            }

            return ownerTasks;
        },

        getDoneTasks: function(tasks) {
            var doneTasks = [];

            for (var i = 0; i < tasks.length; i++) {
                if (tasks[i].status == 'done') {
                    doneTasks.push(tasks[i]);
                }
            }

            return doneTasks;
        },

        /* Local Storage functions */
        load: function() {
            return $.Tasks.deserialize($ls['tasks']);
        },

        save: function(tasks) {
            $ls['tasks'] = $.Tasks.serialize(tasks);
        },

        clear: function() {
            $ls.removeItem('tasks');
            return Array();
        }
    };
})(window);