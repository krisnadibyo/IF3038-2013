package madtodo.controllers;

import madtodo.MadController;
import madtodo.models.User;

import org.json.JSONException;
import org.json.JSONObject;
import org.json.JSONTokener;

public class AuthController extends MadController {
    ////
    // url { /auth }
    @Override
    public void index() {
        print404();
    }

    ////
    // url { /auth/login }
    public void login() {
        if (!isCheckPass()) {
            printFailed();
        } else {
            String rawData = getQueryParam("data");

            JSONObject data = null;
            try {
                data = new JSONObject(new JSONTokener(rawData));
            } catch (JSONException e) {
                printJSON(new JSONObject().put("error", "Invalid JSON Data"));
                return;
            }

            String username = data.getString("username");
            String password = data.getString("password");

            User user = User.findByUsername(username);
            if (user == null) {
                printFailed();
                System.out.println("User not found");
            } else {
                if (user.getPassword().equals(md5sum(password))) {
                    session.login(username, user.getId());
                    printSuccess();
                } else {
                    printFailed();
                    System.out.println("Wrong password");
                }
            }
        }
    }

    ////
    // url { /auth/logout }
    public void logout() {
        if (session.isLoggedIn()) {
            session.logout();
            printSuccess();
        } else {
            printFailed();
        }
    }

    private boolean isCheckPass() {
        boolean pass = true;

        if (request.getMethod() != "POST") {
            pass = false;
        }

        if (request.getParameter("data") == null) {
            pass = false;
        }

        return pass;
    }
}
