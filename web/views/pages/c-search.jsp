<jsp:include page="/views/pages/fgmt-header.jsp"></jsp:include>
<%@ page import="java.util.List" %>
<%@ page import="madtodo.models.*" %>
<%

String keyword = (String) request.getAttribute("keyword");
String filter = (String) request.getAttribute("filter");

%>
<div id="searchContainer">
    <h1>Search Result for '<%= keyword %>'</h1>
    <h3>Filter: <strong><%= filter %></strong></h3>
    
    <% if (filter.equals("all")) { %>
    <!-- [[[ All ]]] -->
        <%
        List<User> users = (List<User>) request.getAttribute("users");
        List<Category> cats = (List<Category>) request.getAttribute("cats");
        List<Task> tasks = (List<Task>) request.getAttribute("tasks");
        int i = 0;
        %>

        <!-- Users -->
        <h2>Users</h2>
        <% if (users != null) { %>
            <% i = 0; for (User user : users) { %>
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

        <!-- Categories -->
        <h2>Categories</h2>
        <% if (cats != null) { %>
            <% i = 0; for (Category cat : cats) { %>
            <div>
                <div><strong><%= ++i %>.
                    <a href="/page/dashboard"><%= cat.getName() %></a>
                </strong></div>
            </div>
            <% } %>
        <% } %>

        <!-- Tasks -->
        <h2>Tasks:</h2>
        <% if (tasks != null) { %>
            <% i = 0; for (Task task : tasks) { %>
            <ul class="task">
                <li taskId="<%= task.getId() %>" taskNumber="<%= i++ %>" class="taskName" onclick="viewTask(this)">
                    <strong><%= i %>. <%= task.getName() %></strong>
                </li>
    
                <li>Deadline: <strong><%= task.getDeadline().toString() %></strong></li>
                <li>User: <strong><%= task.getUsername() %></strong></li>
                <%
                String assignee = task.getAssignee();
                assignee = assignee == null ? "None" : assignee;
                %>
                <li>Assignee: <%= assignee %></li>
                <li>Tags: <%= task.getTagsAsString() %></li>
                <% String status = (task.getStatus() == 0) ? "Unfinished" : "Done"; %>
                <li>Status: <%= status %></li>
                <%
                String attachment = task.getAttachment();
                attachment = attachment == "none" ? "None" : attachment;
                %>
                <li>Attachment: <%= attachment %></li>
            </ul>
            <% } %>
        <% } %>
    <% } %>
    
    <% if (filter.equals("username")) { %>
    <!-- [[[[ Username ]]]] -->
        <% List<User> users = (List<User>) request.getAttribute("users"); %>
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

    <% if (filter.equals("category")) { %>
    <!-- [[[[ Category ]]]] -->
        <% List<Category> cats = (List<Category>) request.getAttribute("cats"); %>
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

    <% if (filter.equals("task")) { %>
    <!-- [[[[ Task ]]]] -->
        <% List<Task> tasks = (List<Task>) request.getAttribute("tasks"); %>
        <% if (tasks != null) { %>
            <% int i = 0; for (Task task : tasks) { %>
            <ul class="task">
                <li taskId="<%= task.getId() %>" taskNumber="<%= i++ %>" class="taskName" onclick="viewTask(this)">
                    <strong><%= i %>. <%= task.getName() %></strong>
                </li>
    
                <li>Deadline: <strong><%= task.getDeadline().toString() %></strong></li>
                <li>User: <strong><%= task.getUsername() %></strong></li>
                <%
                String assignee = task.getAssignee();
                assignee = assignee == null ? "None" : assignee;
                %>
                <li>Assignee: <%= assignee %></li>
                <li>Tags: <%= task.getTagsAsString() %></li>
                <% String status = (task.getStatus() == 0) ? "Unfinished" : "Done"; %>
                <li>Status: <%= status %></li>
                <%
                String attachment = task.getAttachment();
                attachment = attachment == "none" ? "None" : attachment;
                %>
                <li>Attachment: <%= attachment %></li>
            </ul>
            <% } %> 
        <% } %>
    <% } %>
</div>

<jsp:include page="/views/pages/fgmt-footer.jsp"></jsp:include>
