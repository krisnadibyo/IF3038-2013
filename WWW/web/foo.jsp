<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Foo JSP</title>
</head>
<body>
    <ul>
    <%
    for (int i = 0; i < 10; i++) { 
        out.write(String.format("<li>Foo Bar Qux %d</li>", i));
    } 
    %>
    </ul>
</body>
</html>
