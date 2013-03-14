<?php vh_render('pages.header', $data); ?>

<div id="columnsContainer">

    <div id="coloumnOne">
        <!-- Sign Up -->
        <div class="colPlaceholder" id="signUpPlaceholder">
            <div>Sign Up</div>
        </div>

        <form id="signUpForm" class="signXForm" action="javascript:;">
            <div><input id="signup_name" rule="name" type="text" placeholder="Name *" /></div>
            <div><input id="signup_username" rule="username" type="text" placeholder="Username *" /></div>
            <div><input id="signup_password" rule="password" type="password" placeholder="Password *" /></div>
            <div><input id="signup_email" rule="email" type="text" placeholder="Email *" /></div>
            <div>
                <label>Birthday:</label>
                <select name="" id="bdYear"></select>-<select id="bdMonth"></select>-<select id="bdDay"></select>
                <input id="signup_birthday" rule="birthday" type="hidden" placeholder="Birthday *" /></div>
            <div id="uploadAvatarDiv">
                <input id="signup_avatar" type="text" placeholder="Avatar Image (Browse...)" disabled="true" />
                <input id="avatarFile" type="file" />
                <img id="avatarImg" width="200px" height="200px" />
            </div>
            <div><label>Short bio:</label><textarea id="signup_bio"></textarea></div>
            <div><button type="submit" id="signUpButton" disabled="true">Sign Up</button></div>
        </form>
    </div>

    <div class="colSpace"></div>
    <div id="coloumnTwo"></div>
    <div class="clear"></div>
    <div id="coloumnThree"></div>
    <div class="colSpace"></div>

    <div id="coloumnFour">
        <!-- Sign In -->
        <div class="colPlaceholder" id="signInPlaceholder">
            <div>Sign In</div>
        </div>

        <form id="signInForm" class="signXForm" action="javascript:;">
            <div><input id="signin_username" type="text" placeholder="Username" /></div>
            <div><input id="signin_password" type="password" placeholder="Password" /></div>
            <div><button type="submit" id="signInButton">Sign In</button></div>
        </form>
    </div>

    <div class="clear"></div>
</div>

<?php vh_render('pages.footer', $data); ?>
