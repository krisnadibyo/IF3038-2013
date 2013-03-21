/** Task **/
/* requires: madtodo.js */
(function($) {
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
        },

        getTagsStr: function(task) {
            if (!task['tags']) {
                return 'None';
            }

            var tagsStr = '';
            var tags = task['tags'];

            for (var i = 0; i < tags.length; i++) {
                tagsStr += tags[i]['name'] + ', ';
            }
            tagsStr = tagsStr.replace(/, $/, '');

            return tagsStr;
        }
    };

    $.TaskAPI = {
        // GETTERS
        getCategories: function(callbackfunc, async) {
            return XHR.qGet('category/user', callbackfunc, async);
        },
        getUserTasks: function(callbackfunc, async) {
            return XHR.qGet('task/user', callbackfunc, async);
        },
        getAssignedTasks: function(callbackfunc, async) {
            return XHR.qGet('task/assignee', callbackfunc, async);
        },
        getByCategory: function(category, callbackfunc, async) {
            return XHR.qGet('task/category/' + category + '/complete', callbackfunc, async);
        },
        getByTag: function(tag, callbackfunc, async) {
            return XHR.qGet('task/tag/' + tag + '/complete', callbackfunc, async);
        },
        getById: function(id, callbackfunc, async) {
            return XHR.qGet('task/get/' + id + '/complete', callbackfunc, async);
        },
        searchTasks: function(name, callbackfunc, async) {
            return XHR.qGet('task/search_name/' + name + '/complete', callbackfunc, async);
        },
        hint: function(name, callbackfunc, async) {
            return XHR.qGet('task/hint/' + name, callbackfunc, async);
        },

        // SETTERS
        createCategory: function(name, callbackfunc, async) {
            return XHR.qPost('category/create/' + name, null, callbackfunc, async);
        },
        deleteCategory: function(name, callbackfunc, async) {
            return XHR.qPost('category/delete/' + name, null, callbackfunc, async);
        }
        // TODO
    }
})(window);
