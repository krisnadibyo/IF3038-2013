<jsp:include page="/views/pages/fgmt-header.jsp"></jsp:include>

<div id="dashboardContainer">

    <div id="twoColumns">
    <!-- BEGIN TWOCOLUMNS -->

    <div id="categoriesDiv">
        <ul id="categoryList">

        </ul>

        <a id="newCategory" onclick="newCategory(this)" href="javascript:;">[+] New Category</a>
        <a id="newCategory" onclick="deleteCategory(this)" href="javascript:;">[+] Delete Category</a>
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

<jsp:include page="/views/pages/fgmt-footer.jsp"></jsp:include>
