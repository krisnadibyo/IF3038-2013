<%@ page import="java.util.*" %>
<%

boolean isDashboard = request.getAttribute("isDasboard") != null;
boolean isProfile = request.getAttribute("isProfile") != null;
boolean isUserSet = request.getAttribute("isUser") != null;
List<String> footerScripts = (List<String>) request.getAttribute("footerScripts");

%>
<div id="footer"></div>

<% if (isDashboard) { %>
    <jsp:include page="/views/pages/dlg-dashboard.jsp"></jsp:include>
<% } %>

<% if (isProfile) { %>
    <jsp:include page="/views/pages/dlg-profile.jsp"></jsp:include>
<% } %>

<% if (isUserSet) { %>
    <jsp:include page="/views/pages/dlg-search.jsp"></jsp:include>
<% } %>


<% for (String script : footerScripts) { %>
    <script type="text/javascript" src="<%= script %>"></script>
<% } %>

<!-- END BODY -->
</body>
</html>