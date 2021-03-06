<jsp:include page="/views/pages/fgmt-header.jsp"></jsp:include>

<div id="columnsContainer">

    <div id="coloumnOne">
        <!-- Sign Up -->
        <div class="colPlaceholder" id="signUpPlaceholder">
            <div>Sign Up</div>
        </div>

        <form id="signUpForm" class="signXForm" action="javascript:;">
            <div><input id="signup_name" data-rule="name" type="text" placeholder="Name *" /></div>
            <div><input id="signup_username" data-rule="username" type="text" placeholder="Username *" /></div>
            <div><input id="signup_password" data-rule="password" type="password" placeholder="Password *" /></div>
            <div><input id="signup_email" data-rule="email" type="text" placeholder="Email *" /></div>
            <div>
                <label>Birthday:</label>
                <select name="" id="bdYear"></select>-<select id="bdMonth"></select>-<select id="bdDay"></select>
                <input id="signup_birthday" data-rule="birthday" type="hidden" /></div>
            <div id="uploadAvatarDiv">
                <input id="signup_avatar" type="text" placeholder="Avatar Image (Browse...)" disabled />
                <input id="avatarFile" type="file" />
                <img id="avatarImg" width="200" height="200" alt="Avatar Image" src="javascript:;" />
            </div>
            <div><label>Short bio:</label><textarea id="signup_bio"></textarea></div>
            <div><button type="submit" id="signUpButton" disabled>Sign Up</button></div>
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

<jsp:include page="/views/pages/fgmt-footer.jsp"></jsp:include>
