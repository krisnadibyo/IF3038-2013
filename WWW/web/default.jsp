<!DOCTYPE html>
<html>
<%
    String ctrlClassName = (String) request.getAttribute("ctrlClassName");
    if (ctrlClassName == null) {
        ctrlClassName = "NullController";
    }
%>
<head>
    <meta charset="utf-8" />
    <title><%= ctrlClassName %></title>
</head>

<body>
    <h1>Index of <%= ctrlClassName %></h1>
</body>

</html>
