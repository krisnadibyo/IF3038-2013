(function($) {
    var user = Session.getLoggedUser();

    if (!user) {
    alert("You're not signed in! Please sign in first!");
        $.open($.AppRoot, '_self');
    }

    var tasks = Tasks.load();
    var userTasks = Tasks.getOwnerTasks(tasks, user['username']);

    $id('name').html(user['name']);
    $id('username').html('Username: <strong>' + user['username'] + '</strong>');
    $id('email').html('Email: <em>' + user['email'] + '</em>');
    $id('birthday').html('Birthday: <strong>' + user['birthday']['year'] + '-' + user['birthday']['month'] + '-' + user['birthday']['day'] + '</strong>');
    $id('avaImg').src = $ls[user['avatar']];
    $id('bio').html('Bio: <p><em>' + (user['bio'] == '' ? 'This user has no bio' : user['bio']) + '</em></p>');

    for (var i = 0; i < userTasks.length; i++) {
        var e = $e.create('li').html(userTasks[i]['name']);
        $id('userTasks').appendChild(e);
    }

})(window);
