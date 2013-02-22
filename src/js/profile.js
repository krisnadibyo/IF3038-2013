(function($) {
    var user = Session.getLoggedUser();

    if (!user) {
    alert("You're not signed in! Please sign in first!");
        $.open('./index.html', '_self');
    }

    $id('name').html(user['name']);
    $id('username').html('Username: <strong>' + user['username'] + '</strong>');
    $id('email').html('Email: <em>' + user['email'] + '</em>');
    $id('birthday').html('Birthday: <strong>' + user['birthday']['year'] + '-' + user['birthday']['month'] + '-' + user['birthday']['day'] + '</strong>');
    $id('avaImg').src = $ls[user['avatar']];
    $id('bio').html('Bio: <p><em>' + (user['bio'] == '' ? 'This user has no bio' : user['bio']) + '</em></p>');
})(window);
