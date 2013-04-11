<!DOCTYPE html>
<%@ page import="java.util.*" %>
<%@ page import="madtodo.models.User" %>
<%

boolean isUserSet = request.getAttribute("isUserSet") != null;
String pageTitle = (String) request.getAttribute("pageTitle");
String[] headerScripts = (String[]) request.getAttribute("headerScripts");

%>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />

    <% for (String script : headerScripts) { %>
        <script type="text/javascript" src="<%= script %>"></script>
    <%  } %>

    <style type="text/css">@import url('/static/css/maincss.css');</style>
    <title><%= pageTitle %></title>
</head>

<body>

<div id="header">
    <div>
        <div id="logo"><a href="/">MadToDo!</a></div>
        <ul id="topNav">
            <li><a href="/page/profile">Profile</a></li>
            <li><a href="/page/dashboard">Dashboard</a></li>
            <li><a href="/">Home</a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>

<% if (isUserSet) { %>
<% User user = (User) request.getAttribute("user"); %>
<div id="extraHeader">
    <form id="searchBar">
        <input id="searchInput" type="text" placeholder="Search..." />
        <select id="searchFilter">
            <option value="all">Filter: All</option>
            <option value="username">Filter: Username</option>
            <option value="category">Filter: Category</option>
            <option value="task">Filter: Task</option>
        </select>
        <script type="text/javascript">
            $id('searchBar').onsubmit = function(evt) {
                evt.preventDefault();
                window.open("/page/search/" + $id('searchFilter').val() + "/" + $id('searchInput').val(), "_self");
            }
        </script>
    </form>

    <div id="miniUserInfo">
        <strong id="loggedUserText">
            <%= user.getName() %>
        </strong>
        <img id="miniAvatar" alt="" width="36px" height="36px" src="/static/uploads/avatar/<%= user.getAvatar() %>" />
        <form id="signOutButton" method="GET" action="/page/logout">
            <button type="submit">Sign Out</button>
        </form>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<% } %>
