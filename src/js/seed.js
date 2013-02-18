/** Local Storage seeder **/
(function($) {
    $.supportLocalStorage = true;

    if (typeof(localStorage) === 'undefined') {
        $.supportLocalStorage = false;

        console.log('*** WARNING! localStorage is not supported on your browser! ***');
        localStorage = {};
    }

    $.seedTasks = function() {
        console.log('*** Task Seeding... ***');

        var tasks = [
            new Task(
                'End the War',
                '',
                '2020-11-01',
                'Joe',
                ['war', 'politics']
            ),
            new Task(
                'Eat',
                'food.jpg',
                '2017-07-07',
                'Terrence',
                ['everyday needs', 'food']
            ),
            new Task(
                'Drink Coca Cola',
                'cocacola.png',
                '2018-08-08',
                'Nancy',
                ['fun', 'refreshing']
            ),
            new Task(
                'Cook Fish',
                '',
                '2014-04-04',
                '',
                ['fun', 'food']
            )
            /*, ... */
        ];

        console.log('*** Tasks (Before serialization): ***');
        console.log(tasks)

        console.log('*** Serializing... ***');
        localStorage['tasks'] = Tasks.serialize(tasks);

        console.log('*** After serialization: ***');
        console.log(localStorage['tasks']);
        console.log('*** After deserialization: ***');
        console.log(Tasks.deserialize(localStorage['tasks']));
    }

    $.seedUsers = function() {

    }
})(window);
