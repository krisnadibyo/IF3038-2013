<?php vh_render('pages.header', $data); ?>

<div id="dashboardContainer">
    <div id="welcomeMessage">
        Welcome to your Dashboard! You're logged in as <strong id="loggedUserText">monkey</strong>
        <button id="signOutButton">Sign Out</button>
    </div>

    <div id="twoColumns">
    <!-- BEGIN TWOCOLUMNS -->

    <div id="categoriesDiv">
        <ul id="categoryList">
        
        </ul>

        <a id="newCategory" onclick="newCategory(this)" href="javascript:;">[+] New Category</a>
        <a id="newTask" onclick="newEditTask(this, false)" href="javascript:;">[+] New Task</a>
    </div>

    <div id="tasksDiv">
        <div id="taskListHeader">
        <strong>Task List</strong><button onclick="newEditTask(this, false)" id="newTask">New Task</button>
        </div>

        <ul id="taskList">
            <li>
            </li>
        </ul>
    </div>

    <div class="clear"></div>

    <!-- END TWOCOLUMNS -->
    </div>
</div>

<?php vh_render('pages.footer', $data); ?>
