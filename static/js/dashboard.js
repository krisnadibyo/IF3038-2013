(function($) {
    UserAPI.get(function(res) {
        console.log("User loaded");
        userLoaded(res);
    });

    var userLoaded = function(user) {
    // [USER LOADED]

    document.title = 'Dashboard - ' + user['name'];

    // Resize dialog boxes
    var resizeDialogs = function(firstTime) {
        var multiplier = firstTime ? 4 : 1.6;
        var cWidth = document.body.clientWidth;
        var cHeight = document.body.clientHeight;

        $id('newCategoryForm').style.left = (cWidth / 2) - (446 / 2) + 'px';
        $id('newTaskForm').style.left = (cWidth / 2) - (486 / 2) + 'px';
        $id('viewEditTaskForm').style.left = (cWidth / 2) - (486 / 2) + 'px';
    }
    resizeDialogs(true);

    $.onresize = function() {
        resizeDialogs();
    }

    // Populate tasks
    var userTasks = TaskAPI.getByCategory('Uncategorized', null, false);
    // var tasks = Tasks.load();
    // var userTasks = Tasks.getOwnerTasks(tasks, user['username']);
    // var userDoneTasks = Tasks.getDoneTasks(userTasks);

    // Collect categories
    var categories = TaskAPI.getCategories(null, false);

    var activeCategory = {
        id: categories[0]['id'],
        name: categories[0]['name']
    };
    var activeCategoryTasks = undefined;
    var activeCategoryLi = undefined;

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
            }

            $id('categoryList').appendChild(li);
        }
    }
    showCategories();

    var showActiveCategoryTasks = function() {
        activeCategoryTasks = TaskAPI.getByCategory(activeCategory['name'], null, false);
        console.log(activeCategoryTasks);
        //Tasks.getByCategory(userTasks, activeCategory);

        $id('taskList').html('');600
        for (var i = 0; i < activeCategoryTasks.length; i++) {
            var task = activeCategoryTasks[i];
            var li = $e.create('li').attr('acTaskId', i);

            var html =
            '<ul class="task">' +
                '<li taskId="' + i + '" class="taskName" onclick="viewTask(this)"><strong>' + (i + 1) + '. ' + task['name'] + '</strong></li>' +
                '<li>Deadline: <strong>' + task['deadline'] + '</strong></li>' +
                '<li>Assignee: ' + (task['assignee'] == undefined ? 'None' :  task['assignee']) + '</li>' +
                '<li>Tags: ' + TaskHelper.getTagsStr(task) + '</li>' +
                '<li>Status: ' + (task['status'] == '0' ? 'Not Done' : 'Done!') + '</li>' +
                '<li>Attachment: ' + (task['attachment'] == 'none' ? 'None' :  task['attachment']) + '</li>' +
            '</ul>';

            li.html(html);
            $id('taskList').appendChild(li);
        }
    }
    showActiveCategoryTasks();

    $.deleteTask = function(e) {
        e = $e(e);
        var tId = e.attr('taskId');
        var task = activeCategoryTasks[tId];

        for (var i = 0; i < tasks.length; i++) {
            if (tasks[i]['name'] == task['name']) {
                tasks.splice(i, 1);
                Tasks.save(tasks);

                tasks = Tasks.load();
                userTasks = Tasks.getOwnerTasks(tasks, user['username']);
                showActiveCategoryTasks();

                alert('Task "' + task['name'] + '" has been deleted!');

                break;
            }
        }
    }


    // New Task
    var checkTaskInput = function(e) {
        if(!TaskHelper.testRule(e.val(), e.attr('rule'))) {
            if (!e.hasClass('error')) {
                var errorDiv = $e.create('div')
                    .addClass('errorMessage')
                    .html(TaskHelper.errorMessages[e.attr('rule')]);

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
    }

    var inputs = ['owner', 'category', 'name', 'attachment', 'deadline', 'assignee', 'tags'];
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
        taskInputs['category'].val(activeCategory['name']);

        for (key in taskInputs) {
            var e = taskInputs[key];

            e.onkeyup = function() {
                if (this.attr('rule')) {
                    checkTaskInput(this);
                }
            }

            if (e.attr('rule') && e.val() !== '') {
                checkTaskInput(e);
            }
        }
    }

    $id('taskSubmitButton').onclick = function(evt) {
        var error = false;

        for (var key in taskInputs) {
            var e = taskInputs[key];
            if (e.attr('rule') && !checkTaskInput(e)) {
                error = true;
            }
        }

        if (error) {
            return;
        }

        var t = new Task();
        t.setTags(taskInputs['tags'].value);
        t.status = '';

        for (var key in taskInputs) {
            if (key !== 'tags') {
                t[key] = taskInputs[key].value;
            }
            if (key !== 'owner') {
                taskInputs[key].value = '';
            }
        }

        if (t.category === '') {
            t.category = 'Uncategorized';
        }

        tasks.push(t);
        Tasks.save(tasks);
        $.open($.AppRoot + 'page/dashboard', '_self');
    }

    // Dialogs
    $.closeDialog = function(e) {
        e = $e(e);
        var dialog = $id(e.attr('dialogId'));

        $id('pageBlurrer').doTransition({
            opacity: '0'
        }, 25);
        dialog.doTransition({
            opacity: '0'
        }, 25);

        setTimeout(function() {
            $id('pageBlurrer').style.display = 'none';
            dialog.style.display = 'none';
        }, 250);
    }

    // New Category
    $.newCategory = function(e) {
        $id('newCategoryForm').style.display = 'block';
        $id('pageBlurrer').style.display = 'block';

        $id('newCategoryForm').doTransition({
            'opacity': '1.0'
        }, 25);
        $id('pageBlurrer').doTransition({
            'opacity': '0.4'
        }, 25);
    }

    $.newCategorySubmitted = function(e) {
        alert('Not implemented yet. You can create new category when you create new task');
        $.closeDialog(e);
    }

    // View/Edit Task
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

        var cTId = e.attr('taskId');
        var task = activeCategoryTasks[cTId];

        $id('ve_name').html(task['name']);
        $id('ve_attachment').html('Attachment: ' +(task['attachment'] == '' ? 'None' : task['attachment']));

        $id('ve_deadline').html('Deadline: <strong>' + task['deadline'] + '</strong>');
        $id('ve_assignee').html('Assignee: <strong>' + (task['assignee'] == '' ? 'None' : task['assignee']) +  '</strong>');
        $id('ve_tags').html('Tags: <strong>' + TaskHelper.getTagsStr(task) + '</strong>');
        $id('ve_status').html('Status: <strong>' + (task['status'] == '' ? 'Not Done' : 'Done!') + '</strong>');

        $id('taskEditButton').onclick = function(evt) {
            $id('ve_deadline').html('<label>Deadline:</label><input id="ve_deadlineInput" type="date" value="' + task['deadline'] + '" />');
            $id('ve_assignee').html('<label>Assignee:</label><input id="ve_assigneeInput" type="text" value="' + task['assignee'] + '" />');
            $id('ve_tags').html('<label>Tags:</label><input id="ve_tagsInput" type="text" value="' + TaskHelper.getTagsStr(task) + '" />');
            $id('taskEditSubmitButton').removeAttr('disabled');
        }

        $id('taskEditSubmitButton').onclick = function(evt) {
            alert('Not implemented yet.');
            $.closeDialog($e.create('div').attr('dialogId', 'viewEditTaskForm'));
        }
    }

    // [/USER LOADED]
    }
})(window);
