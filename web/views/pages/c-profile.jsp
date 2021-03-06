<jsp:include page="/views/pages/fgmt-header.jsp"></jsp:include>

<div id="profileContainer">
    <h1 id="name"></h1>

    <div id="username"></div>
    <div id="email"></div>
    <div id="birthday"></div>
    <div id="avatar">
        Avatar:<br />
        <img width="200px" height="200px" id="avaImg" alt="Avatar" />
    </div>
    <div id="bio"></div>
    <div><button onclick="showEditProfileForm()">Edit Profile</button></div>
    <br />

    <div>
        <h1>User's tasks</h1>
        Done:
        <ol id="doneUserTasks">

        </ol><br />
        Unfinished:
        <ol id="unfinishedUserTasks">

        </ol><br />
        <div><a href="/page/dashboard">View in dashboard</a></div>
    </div>

</div>

<jsp:include page="/views/pages/fgmt-footer.jsp"></jsp:include>
