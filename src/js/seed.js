/** Local Storage seeder **/
/* requires: madtodo.js, task.js, user.js */
(function($) {
    $.seedTasks = function() {
        console.log('*** Task Seeding... ***');

        var tasks = [
            new Task(
                'msweaver',
                'Uncategorized',
                'End the War',
                '',
                '2020-11-01',
                'fitzhugh',
                ['war', 'politics'],
                ''
            ),
            new Task(
                'jgrainger',
                'Uncategorized',
                'Eat',
                'food.jpg',
                '2017-07-07',
                'fitzhugh',
                ['everyday needs', 'food'],
                ''
            ),
            new Task(
                'fitzhugh',
                'Uncategorized',
                'Drink Coca Cola',
                'cocacola.png',
                '2018-08-08',
                'msweaver',
                ['fun', 'refreshing'],
                'done'
            ),
            new Task(
                'fitzhugh',
                'Uncategorized',
                'Cook Fish',
                '',
                '2014-04-04',
                '',
                ['fun', 'food'],
                ''
            )
        ];

        Tasks.save(tasks);
    }

    $.seedUsers = function() {
        console.log('*** User Seeding... ***');

        var users = [
            new User(
                'Terrence Fitzhugh',
                'fitzhugh',
                'chickenrice',
                'fitzhugh@gmail.com',
                UserHelper.parseBirthday('1950-12-04'),
                'fitzhugh.png',
                ''
            ),
            new User(
                'Stephanie Weaver',
                'msweaver',
                'manhattan',
                'sweaver@yahoo.co.uk',
                UserHelper.parseBirthday('1986-06-28'),
                'msweaver.png',
                ''
            ),
            new User(
                'James Grainger',
                'jgrainger',
                'tuvalu',
                'jgrainger@mail.ru',
                UserHelper.parseBirthday('1964-09-11'),
                'jgrainger.png',
                ''
            )
        ];

        Users.save(users);
    }
})(window);
