(function($) {

    var user = UserAPI.get(null, false); // Synchronously
    document.title = 'Dashboard - ' + user['name'];

    // Resize body and dialog boxes
    $.dialogs = new Array(
        $id('newCategoryForm'),
        $id('newTaskForm'),
        $id('viewEditTaskForm'),
        $id('deleteCategoryForm')
    );

    // Populate tasks and categories (Synchronously)
    var userTasks = TaskAPI.getByCategory('Uncategorized', null, false);
    var categories = TaskAPI.getCategories(null, false);

    var activeCategory = {
        id: categories[0]['id'],
        name: categories[0]['name']
    };
    var activeCategoryTasks = undefined;
    var activeCategoryLi = undefined;

    ///////////////////////////////////////////////////////////////////////////
    // Show categories and active category tasks
    ///////////////////////////////////////////////////////////////////////////
    var showCategories = function() {
        $id('categoryList').html('');

        for (var i = 0; i < categories.length; i++) {
            var li = $e.create('li')
                .html(categories[i]['name'])
                .attr('cat', categories[i]['name'])
                .attr('catId', categories[i]['id']);

            if (categories[i]['name'] == activeCategory['name']) {
                li.addClass('active');
                activeCategoryLi = li;
            }

            li.onclick = function(e) {
                activeCategoryLi.removeClass('active');
                this.addClass('active');

                activeCategory['name'] = this.attr('cat');
                activeCategory['id'] = parseInt(this.attr('catId'));
                activeCategoryLi = this;

                showActiveCategoryTasks();
            };

            $id('categoryList').appendChild(li);
        }
    };
    showCategories();

    $.showActiveCategoryTasks = function() {
        activeCategoryTasks = TaskAPI.getByCategory(activeCategory['name'], null, false);
        if (activeCategoryTasks == null) {
            activeCategoryTasks = new Array();
        }

        $id('taskList').html('');
        if (activeCategoryTasks.length == 0) {
            var li = $e.create('li');
            li.html("<h2>You don't have any task in this category</h2>");
            $id('taskList').appendChild(li);
        } else {
            for (var i = 0; i < activeCategoryTasks.length; i++) {
                var task = activeCategoryTasks[i];
                var li = $e.create('li').attr('acTaskNumber', i);

                var html =
                '<ul class="task">' +
                    '<li taskId="' + task['id'] + '" taskNumber="' + i + '" class="taskName" onclick="viewTask(this)"><strong>' + (i + 1) + '. ' + task['name'] + '</strong></li>' +
                    '<li>Deadline: <strong>' + task['deadline'] + '</strong></li>' +
                    '<li>Assignee: ' + (task['assignee'] == undefined ? 'None' :  task['assignee']) + '</li>' +
                    '<li>Tags: ' + TaskHelper.getTagsStr(task) + '</li>' +
                    '<li>Status: ' + (task['status'] == '0' ? 'Unfinished' : 'Done') + '</li>' +
                    '<li>Attachment: ' + (task['attachment'] == 'none' ? 'None' :  task['attachment']) + '</li>' +
                    '<li>' +
                        '<button onclick="deleteTask(' + task['id'] + ')">Delete Task</button>' +
                        ((task['status'] == '0')
                        ? '<button onclick="doneTask(' + task['id'] + ')">Mark as Done</button></li>'
                        : '<button onclick="undoneTask(' + task['id'] + ')">Mark as Unfinished</button></li>') +
                    '</li>' +
                '</ul>';

                li.html(html);
                $id('taskList').appendChild(li);
            }
        }
    };
    showActiveCategoryTasks();

    ////////////////////////////////////////////////////////////////////////////
    // Show assigned tasks and categories
    ////////////////////////////////////////////////////////////////////////////
    var assignedTasks = undefined;
    var assignedCategories = undefined;

    var getAssignedTasks = function() {
        assignedTasks = TaskAPI.getAssignedTasks(null, false);
    };
    getAssignedTasks();

    var showAssignedTasks = function() {
        activeCategoryTasks = TaskAPI.getAssignedTasks(null, false);
        if (activeCategoryTasks == null) {
            activeCategoryTasks = new Array();
        }

        $id('taskList').html('');
        if (activeCategoryTasks.length == 0) {
            var li = $e.create('li');
            li.html("<h2>You don't have any task in this category</h2>");
            $id('taskList').appendChild(li);
        } else {
            for (var i = 0; i < activeCategoryTasks.length; i++) {
                var task = activeCategoryTasks[i];
                var li = $e.create('li').attr('acTaskNumber', i);

                var html =
                '<ul class="task">' +
                    '<li taskId="' + task['id'] + '" taskNumber="' + i + '" class="taskName" onclick="viewTask(this)"><strong>' + (i + 1) + '. ' + task['name'] + '</strong></li>' +
                    '<li>Deadline: <strong>' + task['deadline'] + '</strong></li>' +
                    '<li>User: ' + task['user'] + '</li>' +
                    '<li>Assignee: ' + (task['assignee'] == undefined ? 'None' :  task['assignee']) + '</li>' +
                    '<li>Tags: ' + TaskHelper.getTagsStr(task) + '</li>' +
                    '<li>Category: <strong>' + task['category'] + '</strong></li>' +
                    '<li>Status: ' + (task['status'] == '0' ? 'Unfinished' : 'Done') + '</li>' +
                    '<li>Attachment: ' + (task['attachment'] == 'none' ? 'None' :  task['attachment']) + '</li>' +
                '</ul>';

                li.html(html);
                $id('taskList').appendChild(li);
            }
        }
    };

    var showAssignedCategory = function() {
        getAssignedTasks();

        if (assignedTasks && assignedTasks.length > 0) {
            var li = $e.create('li')
                .html('Assigned')
                .attr('cat', 'Assigned')
                .attr('catId', '-1');

            if ('Assigned' == activeCategory['name']) {
                li.addClass('active');
                activeCategoryLi = li;
            }

            li.onclick = function(e) {
                activeCategoryLi.removeClass('active');
                this.addClass('active');

                activeCategory['name'] = 'Assigned';
                activeCategory['id'] = '-1';
                activeCategoryLi = this;

                showAssignedTasks();
            };

            $id('categoryList').appendChild(li);
        }
    };
    showAssignedCategory();

    ////////////////////////////////////////////////////////////////////////////
    // Task Functions
    ////////////////////////////////////////////////////////////////////////////
    $.doneTask = function(taskId) {
        TaskAPI.doneTask(taskId, function(res) {
            showActiveCategoryTasks();
        }, true);
    };

    $.undoneTask = function(taskId) {
        TaskAPI.undoneTask(taskId, function(res) {
            showActiveCategoryTasks();
        }, true);
    };

    $.deleteTask = function(taskId) {
        var ok = window.confirm("Are you sure do you want to delete this task?");
        if (ok) {
            TaskAPI.deleteTask(taskId, function(res) {
                showActiveCategoryTasks();
            }, true);
        }
    };

    ////////////////////////////////////////////////////////////////////////////
    // New Task
    ////////////////////////////////////////////////////////////////////////////
    var checkTaskInput = function(e) {
        if(!TaskHelper.testRule(e.val(), e.attr('data-rule'))) {
            if (!e.hasClass('error')) {
                var errorDiv = $e.create('div')
                    .addClass('errorMessage')
                    .html(TaskHelper.errorMessages[e.attr('data-rule')]);

                e.addClass('error')
                    .parentNode.insertBefore(errorDiv, e);

                errorDiv.doTransition({ margin: '0 0 0 -20px', opacity: '1.0' }, 25);
            }
            return false;
        } else {
            if (e.hasClass('error')) {
                e.removeClass('error')
                    .parentNode.removeChild(e.parentNode.firstChild);
            }
            return true;
        }
    };

    var inputs = ['owner', 'user_id', 'category', 'name', 'attachment', 'deadline', 'assignee', 'assignee_id', 'tags'];
    var taskInputs = {};

    for (var i = 0; i < inputs.length; i++) {
        taskInputs[inputs[i]] = $id('ntask_' + inputs[i]);
    }

    $.newEditTask = function(e, edit) {
        e = $e(e);
        $id('newTaskForm').style.display = 'block';
        $id('pageBlurrer').style.display = 'block';

        $id('newTaskForm').doTransition({
            'opacity': '1.0'
        }, 25);
        $id('pageBlurrer').doTransition({
            'opacity': '0.4'
        }, 25);

        taskInputs['owner'].val(user['username']);
        taskInputs['user_id'].val(user['id']);

        // Populate category
        taskInputs['category'].html('');
        for (var i = 0; i < categories.length; i++) {
            var option = $e.create('option').val(categories[i]['id']).html(categories[i]['name']);
            if (categories[i]['id'] == activeCategory['id']) {
                option.attr('selected', '');
            }
            taskInputs['category'].appendChild(option);
        }

        for (key in taskInputs) {
            var e = taskInputs[key];

            if (e.attr('data-rule')) {
                e.onkeyup = function() {
                    if (this.attr('data-rule')) {
                        checkTaskInput(this);
                    }
                };
            }

            if (e.attr('data-rule') && e.val() !== '') {
                checkTaskInput(e);
            }
        }
    };

    $id('attachmentFile').onchange = function(e) {
        taskInputs['attachment'].val(this.val());
    };

    taskInputs['assignee'].onkeyup = function(e) {
        if (taskInputs['assignee'].val() == '') {
            return;
        }

        $id('assigneeLoadingBox').html('Loading...').style.display = 'block';

        clearTimeout($.asuggest);
        $.asuggest = setTimeout(function() {
            UserAPI.hint(taskInputs['assignee'].val(), function(res) {
                if (res.length > 0) {
                    taskInputs['assignee'].val(res[0]);
                    $id('assigneeLoadingBox').style.display = 'none';
                    UserAPI.getUserId(res[0], function(res) {
                        taskInputs['assignee_id'].val(res);
                    }, true);
                } else {
                    $id('assigneeLoadingBox').html('Assignee not found!');
                }
            }, true);
        }, 1000);
    };

    taskInputs['assignee'].onblur = function(e) {
        $id('assigneeLoadingBox').style.display = 'none';
    };

    $.assignAssignee = function(e) {
        var val = $e(e).html();
        alert(val);
        taskInputs['assignee'].val(val);
        $id('assigneeSuggestion').style.display = 'none';
    };

    $id('taskSubmitButton').onclick = function(evt) {
        var error = false;

        for (var key in taskInputs) {
            var e = taskInputs[key];
            if (e.attr('data-rule') && !checkTaskInput(e)) {
                error = true;
            }
        }

        if (error) {
            return;
        }

        var attachmentFilename = taskInputs['attachment'].val() !== '' ? taskInputs['attachment'].val() : 'none';
        var task = {
            name: taskInputs['name'].val(),
            user_id: taskInputs['user_id'].val(),
            attachment: attachmentFilename,
            category_id: taskInputs['category'].val(),
            assignee_id: taskInputs['assignee_id'].val(),
            deadline: taskInputs['deadline'].val(),
        };

        TaskAPI.createTask(task, function(res) {
            if (res['status'] == 'success') {
                var id = res['id'];
                if (taskInputs['tags'] != '') {
                    TaskAPI.setTags(id, taskInputs['tags'].val(), function(res) {
                        console.log('set tags success');
                        console.log(res);
                    }, true);
                }
                if (taskInputs['attachment'] != '') {
                    TaskAPI.uploadAttachment($id('attachmentFile').files[0], function(res) {
                        console.log('upload attachment success');
                        console.log(res);
                    });
                }
                showActiveCategoryTasks();
            }
        }, true);

        closeDialogEx($id('newTaskForm'));
    };

    ////////////////////////////////////////////////////////////////////////////
    // New Category
    ////////////////////////////////////////////////////////////////////////////
    $.newCategory = function(e) {
        $id('newCategoryForm').style.display = 'block';
        $id('pageBlurrer').style.display = 'block';

        $id('newCategoryForm').doTransition({
            'opacity': '1.0'
        }, 25);
        $id('pageBlurrer').doTransition({
            'opacity': '0.4'
        }, 25);
    };

    $.newCategorySubmitted = function(e) {
        TaskAPI.createCategory($id('newCategoryName').val(), function(res) {
            console.log(res);
            categories = TaskAPI.getCategories(null, false);
            showCategories();
            showAssignedCategory();

            $.closeDialog(e);
        }, true);
    };

    ////////////////////////////////////////////////////////////////////////////
    // Delete Category
    ////////////////////////////////////////////////////////////////////////////
    $.deleteCategory = function(e) {
        $id('deleteCategoryForm').style.display = 'block';
        $id('pageBlurrer').style.display = 'block';

        $id('deleteCategoryName').html('');
        for (var i = 0; i < categories.length; i++) {
            var option = $e.create('option').attr('value', categories[i]['name']);
            option.html(categories[i]['name']);
            $id('deleteCategoryName').appendChild(option);
        }

        $id('deleteCategoryForm').doTransition({
            'opacity': '1.0'
        }, 25);
        $id('pageBlurrer').doTransition({
            'opacity': '0.4'
        }, 25);
    };

    $.deleteCategorySubmitted = function(e) {
        TaskAPI.deleteCategory($id('deleteCategoryName').val(), function(res) {
            console.log(res);
            categories = TaskAPI.getCategories(null, false);
            showCategories();
            showAssignedCategory();
            showActiveCategoryTasks();

            $.closeDialog(e);
        }, true);
    };

    ////////////////////////////////////////////////////////////////////////////
    // View/Edit Task
    ////////////////////////////////////////////////////////////////////////////
    $.viewTask = function(e) {
        e = $e(e);
        $id('viewEditTaskForm').style.display = 'block';
        $id('pageBlurrer').style.display = 'block';

        $id('viewEditTaskForm').doTransition({
            'opacity': '1.0'
        }, 25);
        $id('pageBlurrer').doTransition({
            'opacity': '0.4'
        }, 25);

        $id('taskEditSubmitButton').attr('disabled', 'true');

        var tNum = e.attr('taskNumber');
        var task = activeCategoryTasks[tNum];

        $id('ve_name').html(task['name']);
        $id('ve_attachment').html('Attachment: ' + (task['attachment'] == 'none' ? 'None' :
            '<a href="/static/uploads/attachment/' + task['attachment'] + '" target="_blank">' + task['attachment'] + '</a>'));

        $id('ve_deadline').html('Deadline: <strong>' + task['deadline'] + '</strong>');
        $id('ve_assignee').html('Assignee: <strong>' + (task['assignee'] == undefined ? 'None' :  task['assignee']) +  '</strong>');
        $id('ve_tags').html('Tags: <strong>' + TaskHelper.getTagsStr(task) + '</strong>');
        $id('ve_status').html('Status: <strong>' + (task['status'] == '' ? 'Unfinished' : 'Done') + '</strong>');

        $id('taskEditButton').onclick = function(evt) {
            $id('ve_deadline').html('<label>Deadline:</label><input id="ve_deadlineInput" type="date" value="' + task['deadline'] + '" />');
            $id('ve_assignee').html('<label>Assignee:</label><input id="ve_assigneeInput" type="text" value="' + (task['assignee'] == undefined ? '' :  task['assignee']) + '" />');
            $id('ve_tags').html('<label>Tags:</label><input id="ve_tagsInput" type="text" value="' + TaskHelper.getTagsStr(task) + '" />');
            $id('taskEditSubmitButton').removeAttr('disabled');
        };

        $id('taskEditSubmitButton').onclick = function(evt) {
            alert('Not implemented yet.');
            $.closeDialog($e.create('div').attr('dialogId', 'viewEditTaskForm'));
        };
    };

})(window);
