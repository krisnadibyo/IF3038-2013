<!DOCTYPE html>
<%@page import="java.util.Enumeration"%>
<%@ page import="java.util.List" %>
<%

String[] list = (String[]) request.getAttribute("list");
String[] params = (String[]) request.getAttribute("params");

%>
<html>
<head>
    <meta charset="utf-8" />
    <title><%= request.getAttribute("title") %></title>
</head>

<body>
    <h2>Jeyesp</h2>

    <h3>list:</h3>
    <ul>
        <%
        for (String str : list) {
            out.write("<li>" + str + "</li>\n");
        }
        %>
    </ul>
    
    <h3>params:</h3>
    <ul>
        <%
        if (params != null) {
            for (String str : params) {
                out.write("<li>" + str + "</li>\n");
            }
        }
        %>
    </ul>
    
    <h3>queries:</h3>
    <ul>
        <%
        Enumeration<String> names = request.getParameterNames();
        while (names.hasMoreElements()) {
            String name = names.nextElement();
            out.write("<li>" + name + ": " + request.getParameter(name) + "</li>");
        }
        %>
    </ul>
</body>

</html>
