(function($) {
	var user = Session.getLoggedUser();

	if (!user) {
    alert("You're not signed in! Please sign in first!");
        $.open('./home.html', '_self');
	}

	var cWidth = document.body.clientWidth;

	$id('newTaskForm').style.left = (cWidth / 2) - (446 / 2) + 'px';

	$id('loggedUserText').html(user['username']);
	document.title = 'Dashboard - ' + user['name'];

	$id('signOutButton').onclick = function(e) {
        Session.logout();
        alert("You have been logged out!");
        $.open('./home.html', '_self');
	}

	// Populate tasks
	var tasks = Tasks.load();
	var userTasks = Tasks.getOwnerTasks(tasks, user['username']);
	var userDoneTasks = Tasks.getDoneTasks(userTasks);

	console.log('Tasks:');
	console.log(tasks);
	console.log('UserTasks: ');
	console.log(userTasks);
	console.log('UserDoneTasks:');
	console.log(userDoneTasks);

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
	console.log('Categories:');
	console.log(categories);

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
        console.log('Active category tasks:');
        console.log(activeCategoryTasks);
        
        $id('taskList').html('');
        for (var i = 0; i < activeCategoryTasks.length; i++) {
        	var task = activeCategoryTasks[i];
        	var li = $e.create('li').attr('acTaskId', i);
    
        	var html =
        	'<ul class="task">' +
                '<li taskId="' + i + '" class="taskName" onclick="viewTask(this)"><strong>' + (i + 1) + '. ' + task['name'] + '</strong></li>' +
                '<li>Deadline: ' + task['deadline'] + '</li>' +
                '<li>Assignee: ' + task['assignee'] + '</li>' +
                '<li>Tags: ' + task['tags'] + '</li>' +
                '<li>Status: ' + (task['status'] == '' ? 'Not Done' : 'Done!') + '</li>' +
                '<li>Attachment: ' + task['attachment'] + '</li>' +
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

        $id('newTaskForm').doTransition({
            'opacity': '1.0'
        }, 25);
	}

})(window);
