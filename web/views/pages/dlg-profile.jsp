<div id="editProfileForm" class="formDialog">
    <div>
        <div data-dialogId="editProfileForm" class="dialogCloseBox" onclick="closeDialog(this)">X</div>
        <form data-dialogId="editProfileForm" action="javascript:;">
            <h2>Edit Profile</h2>
            <div>
                <label>Name:</label>
                <input type="text" id="edit_name" data-rule="name" />
            </div>

            <div></div>

            <div>
                <label>Email:</label>
                <input type="text" id="edit_email" data-rule="email" />
            </div>

            <div>
                <label>Birthday:</label>
                <input type="date" id="edit_birthday" data-rule="birthday" />
            </div>

            <div id="uploadAvatarDiv">
                <input id="edit_avatar" type="text" placeholder="New Avatar Image (Browse...)" disabled />
                <input id="avatarFile" type="file" />

                <img id="avatarImg" width="200" height="200" alt="New Avatar Image" src="javascript:;" />
            </div>

            <div>
                <br />
                <label>Bio:</label><textarea id="edit_bio"></textarea>
            </div>

            <div></div>

            <div>
                <label>New Password:</label>
                <input type="password" id="edit_password" data-rule="password"  />
            </div>

            <div>
                <label>Confirm New Password:</label>
                <input type="password" id="confirm_password" data-rule="password"  />
            </div>

            <div><button type="submit" id="editSubmitButton">Edit</button><br /><br /></div>
        </form>
    </div>
</div>
