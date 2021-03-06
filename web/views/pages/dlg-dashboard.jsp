<!-- Dashboard Dialogs -->
<div id="newTaskForm" class="formDialog">
    <div>
        <div data-dialogId="newTaskForm" class="dialogCloseBox" onclick="closeDialog(this)">X</div>
        <form data-dialogId="newTaskForm" action="javascript:;">
            <h2>New Task</h2>
            <div><input type="text" id="ntask_owner" data-rule="owner" placeholder="Owner *" disabled="disabled"/></div>
            <div>
                <select id="ntask_category">

                </select>
            </div>
            <div><input type="text" id="ntask_name" data-rule="name" placeholder="Name *" /></div>
            <div id="uploadAttachmentDiv">
                <input id="ntask_attachment" type="text" placeholder="Attachment (Browse...)" disabled />
                <input id="attachmentFile" type="file" />
            </div>
            <div><input type="date" id="ntask_deadline" data-rule="date" placeholder="Deadline (in YYYY-MM-DD) *" /></div>
            <div class="relative">
                <input type="text" id="ntask_assignee" placeholder="Assignee" autocomplete="off" />
                <div id="assigneeLoadingBox" class="suggestionBox"></div>
            </div>
            <div><input type="text" id="ntask_tags" placeholder="Tags" /></div>
            <div><button type="submit" id="taskSubmitButton">Create</button></div>

            <input id="ntask_user_id" type="hidden" />
            <input id="ntask_assignee_id" type="hidden" />
        </form>
    </div>
</div>

<div id="viewEditTaskForm" class="formDialog">
    <div>
        <div data-dialogId="viewEditTaskForm" class="dialogCloseBox" onclick="closeDialog(this)">X</div>
    </div>

    <form data-dialogId="newTaskForm" action="javascript:;">
        <h2 id="ve_name">Task Name</h2>

        <div id="ve_attachment"></div>
        <div id="ve_deadline"></div>
        <div id="ve_assignee"></div>
        <div id="ve_tags"></div>
        <div id="ve_status"></div>
        <div id="ve_comment"></div>

        <div><label>Comment:</label><textarea id="ve_inputComment"></textarea></div>

        <div>
            <button type="button" id="taskEditButton">Edit</button>
            <button type="submit" id="taskEditSubmitButton" disabled="disabled">Save Editing</button>
        </div>
    </form>
</div>

<div id="newCategoryForm" class="formDialog">
    <div>
        <div data-dialogId="newCategoryForm" class="dialogCloseBox" onclick="closeDialog(this)">X</div>
        <form data-dialogId="newCategoryForm" action="javascript:;" onsubmit="newCategorySubmitted(this)">
            <input id="newCategoryName" type="text" placeholder="Enter new category name..." />
            <button type="submit">Create</button>
        </form>
    </div>
</div>

<div id="deleteCategoryForm" class="formDialog">
    <div>
        <div data-dialogId="deleteCategoryForm" class="dialogCloseBox" onclick="closeDialog(this)">X</div>
        <form data-dialogId="deleteCategoryForm" action="javascript:;" onsubmit="deleteCategorySubmitted(this)">
            <select id="deleteCategoryName"></select><br />
            <button type="submit">Delete</button>
        </form>
    </div>
</div>
