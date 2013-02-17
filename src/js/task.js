if (typeof(localStorage) === 'undefined') {
    console.log('localStorage is not supported!');
    localStorage = {};
}

function Task(name, attachment, deadline, assignee, tags) {
    this.name = name;
    this.attachment = attachment;
    this.deadline = deadline;
    this.assignee = assignee;
    this.tags = tags;
}

Task.prototype.setTags = function(tagsString) {
    if (tagsString === undefined || tagsString === '') {
        return;
    }

    var tStr = tagsString.replace(/,\s+/g, ',');
    var tArr = Array();

    tStr.split(',').forEach(function(tag) {
        if (tArr.indexOf(tag) === -1 && tag !== '') {
            tArr.push(tag);
        }
    });

    this.tags = tArr;
}

Task.prototype.getTags = function() {
    var t = '';
    var len = this.tags.length
    this.tags.forEach(function(tag, index) {
        t += tag;
        if (index < len - 1) {
            t += ', ';
        }
    });

    return t;
}

function serializeTasks(tasks) {
    var serialized = '';
    tasks.forEach(function(task, index) {
        serialized += JSON.stringify(task);
        if (index < tasks.length - 1) {
            serialized += ';';
        }
    });

    return serialized;
}

function deserializeTasks(serializedTasks) {
    if (serializedTasks === undefined || serializedTasks === '') {
        return Array();
    }

    var splitted = serializedTasks.split(';');
    var tasks = Array();
    splitted.forEach(function(task) {
        task = JSON.parse(task);

        tasks.push(new Task(
            task.name,
            task.attachment,
            task.deadline,
            task.assignee,
            task.tags)
        );
    });

    return tasks;
}

function seedTasks() {
    console.log('*** Seeding...');
    var t1 = new Task(
        'End the War',
        'peace.mkv',
        '20-11-2020',
        'John',
        ['war', 'politics']
    );

    var t2 = new Task(
        'Eat',
        'food.jpg',
        '01-01-2016',
        'Terrence',
        ['everyday needs']
    );

    console.log('*** Tasks (Before serialization):');
    console.log([t1, t2])

    console.log('\n*** Serializing...');
    localStorage['tasks'] = serializeTasks([t1, t2]);

    console.log('\n*** After serialization:');
    console.log(localStorage['tasks']);

    console.log('\n*** After deserialization:');
    console.log(deserializeTasks(localStorage['tasks']));
}

