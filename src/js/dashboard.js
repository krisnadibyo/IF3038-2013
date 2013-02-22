(function($) {
    var user = Session.getLoggedUser();

    if (!user) {
    alert("You're not signed in! Please sign in first!");
        $.open('./index.html', '_self');
    }

    var resizeDialogs = function(firstTime) {
        var multiplier = firstTime ? 4 : 1.6;
        var cWidth = document.body.clientWidth;
        var cHeight = document.body.clientHeight;

        $id('newCategoryForm').style.left = (cWidth / 2) - (446 / 2) + 'px';
        $id('newTaskForm').style.left = (cWidth / 2) - (686 / 2) + 'px';
        $id('viewEditTaskForm').style.left = (cWidth / 2) - (686 / 2) + 'px';
        $id('pageBlurrer').style.height = cHeight * multiplier + 'px';
    }
    resizeDialogs(true);

    $.onresize = function() {
        resizeDialogs();
    }
    

    $id('loggedUserText').html(user['username']);
    document.title = 'Dashboard - ' + user['name'];

    $id('signOutButton').onclick = function(e) {
        Session.logout();
        alert("You have been logged out!");
        $.open('./index.html', '_self');
    }

    // Populate tasks
    var tasks = Tasks.load();
    var userTasks = Tasks.getOwnerTasks(tasks, user['username']);
    var userDoneTasks = Tasks.getDoneTasks(userTasks);

    // Collect categories
    var categories = [];
    var collectCategories = function() {
        for (var i = 0; i < userTasks.length; i++) {
            if (categories.indexOf(userTasks[i]['category']) === -1) {
            categories.push(userTasks[i]['category']);
            }
        }
    }
    collectCategories(categories);

    var activeCategory = categories[0];
    var activeCategoryTasks = undefined;
    var activeCategoryLi = undefined;

    var showCategories = function() {
        collectCategories();
        $id('categoryList').html('');

        for (var i = 0; i < categories.length; i++) {
            var li = $e.create('li').html(categories[i]).attr('cat', categories[i]);
    
            if (categories[i] == activeCategory) {
                li.addClass('active');
                activeCategoryLi = li;
            }
    
            li.onclick = function(e) {
                activeCategoryLi.removeClass('active');
                this.addClass('active');
                activeCategory = this.attr('cat');
                activeCategoryLi = this;
                showActiveCategoryTasks();    
            }
    
            $id('categoryList').appendChild(li);
        }
    }
    showCategories();

    var showActiveCategoryTasks = function() {
        activeCategoryTasks = Tasks.getByCategory(userTasks, activeCategory);
        
        $id('taskList').html('');
        for (var i = 0; i < activeCategoryTasks.length; i++) {
            var task = activeCategoryTasks[i];
            var li = $e.create('li').attr('acTaskId', i);
    
            var html =
            '<ul class="task">' +
                '<li taskId="' + i + '" class="taskName" onclick="viewTask(this)"><strong>' + (i + 1) + '. ' + task['name'] + '</strong></li>' +
                '<li>Deadline: ' + task['deadline'] + '</li>' +
                '<li>Assignee: ' + (task['assignee'] == '' ? 'None' :  task['assignee']) + '</li>' +
                '<li>Tags: ' + task.getTags() + '</li>' +
                '<li>Status: ' + (task['status'] == '' ? 'Not Done' : 'Done!') + '</li>' +
                '<li>Attachment: ' + (task['attachment'] == '' ? 'None' :  task['attachment']) + '</li>' +
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

    $.newEditTask = function(e, edit) {
        e = $e(e);
        $id('newTaskForm').style.display = 'block';
        $id('pageBlurrer').style.display = 'block';


        $id('newTaskForm').doTransition({
            'opacity': '1.0'
        }, 25);
        $id('pageBlurrer').doTransition({
            'opacity': '0.85'
        }, 25);
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
            'opacity': '0.85'
        }, 25);
    }

    $.newCategorySubmitted = function(e) {
        alert('Not implemented yet. You can create new category when you create new task');
        $.closeDialog(e);
    }

    // View/Edit Task
    $.viewTask = function(e) {
        e = $e(e);
        $id('newTaskForm').style.display = 'block';
        $id('pageBlurrer').style.display = 'block';

        $id('newTaskForm').doTransition({
            'opacity': '1.0'
        }, 25);
        $id('pageBlurrer').doTransition({
            'opacity': '0.85'
        }, 25);
    }

})(window);
