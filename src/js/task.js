/** Task **/

/**
 * The task class and constructor.
 * 
 * @param {String} name
 * @param {String} attachment
 * @param {String} deadline (in date format %Y:%m:%d)
 * @param {String} assignee
 * @param {Array} tags (array of tags, can be set using `setTags`)
 */
function Task(name, attachment, deadline, assignee, tags) {
    this.name = name;
    this.attachment = attachment;
    this.deadline = deadline;
    this.assignee = assignee;
    this.tags = tags;
}

/**
 * Set tags from tags string. Tags will be stored in array (set).
 * 
 * @param {String} tagsString
 */
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

/**
 * Get formatted tags string from tags array.
 */
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

/**
 * Serialize an array of task objects into JSON string format.
 * 
 * @param {Array} tasks
 */
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

/**
 * Deserialize a serialized array of JSON-string formatted tasks into array of
 * task objects.
 * 
 * @param {String} serializedTasks
 */
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
