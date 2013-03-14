<?php vh_render('pages.header', $data); ?>

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
    <div><button onclick="alert('Not implemented')">Edit Profile</button></div>
    <br />

    <div>
        <h1>User's tasks</h1>
        <ol id="userTasks">
            
        </ol><br />
        <div><a href="./dashboard.html">View in dashboard</a></div>
    </div>
    
</div>

<?php vh_render('pages.footer', $data); ?>
