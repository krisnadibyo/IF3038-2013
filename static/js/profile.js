(function($) {

    var user = UserAPI.get(null, false); // Synchronously
    document.title = 'Profile - ' + user['name'];

    $.dialogs = new Array();

    var userTasks = TaskAPI.getUserTasks(null, false);

    $id('name').html(user['name']);
    $id('username').html('Username: <strong>' + user['username'] + '</strong>');
    $id('email').html('Email: <em>' + user['email'] + '</em>');
    $id('birthday').html('Birthday: <strong>' + user['birthday'] + '</strong>');
    $id('avaImg').src = '/static/uploads/avatar/' + user['avatar'];
    $id('bio').html('Bio: <p><em>' + (user['bio'] == '' ? 'This user has no bio' : user['bio']) + '</em></p>');

    for (var i = 0; i < userTasks.length; i++) {
        var e = $e.create('li').html(userTasks[i]['name']);
        $id('userTasks').appendChild(e);
    }

})(window);
