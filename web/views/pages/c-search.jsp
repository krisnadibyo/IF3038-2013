<jsp:include page="/views/pages/fgmt-header.jsp"></jsp:include>
<%@ page import="madtodo.models.*" %>
<%

String keyword = (String) request.getAttribute("keyword");
String filter = (String) request.getAttribute("filter");

User[] users = (User[]) request.getAttribute("users");
Category[] cats = (Category[]) request.getAttribute("cats");
Task[] tasks = (Task[]) request.getAttribute("tasks");

%>
<div id="searchContainer">
    <h1>Search Result for '<%= keyword %>'</h1>
    <h3>Filter: <strong><%= filter %></strong></h3>

    <% if (filter.equals("all") || filter.equals("username")) { %>
    <h3>Users</h3>
    <!-- [[[[ Username ]]]] -->
        <% if (users != null) { %>
            <% int i = 0; for (User user : users) { %>
            <div>
                <div><strong><%= ++i %>.
                    <a href="/page/profile"><%= user.getUsername() %></a>
                </strong></div>

                <div>Name: <%= user.getName() %></div>
                <div>Email: <%= user.getEmail() %></div>
                <br />
            </div>
            <% } %>
        <% } %>
    <% } %>

    <% if (filter.equals("all") || filter.equals("category")) { %>
    <h3>Categories</h3>
    <!-- [[[[ Category ]]]] -->
        <% if (cats != null) { %>
            <% int i = 0; for (Category cat : cats) { %>
            <div>
                <div><strong><%= ++i %>.
                    <a href="/page/dashboard"><%= cat.getName() %></a>
                </strong></div>
            </div>
            <% } %>
        <% } %>
    <% } %>

    <% if (filter.equals("all") || filter.equals("task")) { %>
    <h3>Tasks</h3>
    <!-- [[[[ Task ]]]] -->
        <% if (tasks != null) { %>
            <% int i = 0; for (Task task : tasks) { %><%

            String assignee = task.getAssignee();
            assignee = assignee == null ? "None" : assignee;

            String status = (task.getStatus() == 0) ? "Unfinished" : "Done";

            String attachment = task.getAttachment();
            attachment = attachment == "none" ? "None" : attachment;

            %><ul class="task">
                <li data-taskId="<%= task.getId() %>" data-taskNumber="<%= i++ %>" class="taskName" onclick="viewTask(this)">
                    <strong><%= i %>. <%= task.getName() %></strong>
                </li>

                <li>Deadline: <strong><%= task.getDeadline().toString() %></strong></li>
                <li>User: <strong><%= task.getUsername() %></strong></li>
                <li>Assignee: <%= assignee %></li>
                <li>Tags: <%= task.getTagsAsString() %></li>
                <li>Status: <%= status %></li>
                <li>Attachment: <%= attachment %></li>
            </ul>
            <% } %>
        <% } %>
    <% } %>
</div>

<jsp:include page="/views/pages/fgmt-footer.jsp"></jsp:include>
