package madtodo.controllers;

import madtodo.MadController;
import madtodo.models.User;

public class UserController extends MadController
{
    ////
    // url { /user }
    @Override
    public void index() {
        print404();
    }

    ////
    // url { /user/get }
    /**
     * Get logged user
     */
    public void get() {
        User user = User.findById(session.getInt("userId"));

        // TODO
    }

    ////
    // url { /user/hint/:username }
    public void hint() {
        String username = getParam(0, "");

        User[] user = User.searchByUsername(username);

        // TODO
    }

    ////
    // url { /user/getid/:username }
    /**
     * Retrieve the userId of specified user found by username
     */
    public void getid() {
        String username = getParam(0);

        if (username == null) {
            print404();
        } else {
            User user = User.findByUsername(username);
            if (user == null) {
                print404();
            } else {
                // TODO
                // user->getId();
            }
        }
    }

    ////
    // url { /user/edit }
    public void edit() {
        // TODO
    }

    ////
    // url { /user/register }
    public void register() {
        // TODO
    }

    ////
    // Magic functions
    ////

    ////
    // url { /user/all }
    public void all() {
        User[] users = User.findAll();

        // TODO
    }

    ////
    // url { /user/delete/:username }
    public void delete() {
        String username = getParam(0);

        if (username == null) {
            print404();
        } else {
            User user = User.findByUsername(username);
            if (user == null) {
                print404();
            } else {
                // TODO
            }
        }
    }
}
