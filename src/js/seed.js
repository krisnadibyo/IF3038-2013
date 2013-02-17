/** Local Storage seeder **/

supportLocalStorage = true;

if (typeof(localStorage) === 'undefined') {
    supportLocalStorage = false;

    console.log('*** WARNING! localStorage is not supported on your browser! ***');
    localStorage = {};
}

function seedTasks() {
    console.log('*** Task Seeding... ***');

    var tasks = [
        new Task(
            'End the War',
            'peace.mkv',
            '20-11-2020',
            'John',
            ['war', 'politics']
        ),
        new Task(
            'Eat',
            'food.jpg',
            '01-01-2016',
            'Terrence',
            ['everyday needs']
        )
        /*, ... */
    ];

    console.log('*** Tasks (Before serialization): ***');
    console.log(tasks)

    console.log('*** Serializing... ***');
    localStorage['tasks'] = serializeTasks(tasks);

    console.log('*** After serialization: ***');
    console.log(localStorage['tasks']);
    console.log('*** After deserialization: ***');
    console.log(deserializeTasks(localStorage['tasks']));
}

function seedUsers() {
    
}
