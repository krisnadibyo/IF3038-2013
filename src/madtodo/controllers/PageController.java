package madtodo.controllers;

import java.util.Arrays;
import java.util.List;

import madtodo.MadController;
import madtodo.models.Category;
import madtodo.models.Task;
import madtodo.models.User;

public class PageController extends MadController {
    ////
    // url { / }
    @Override
    public void index() {
        if (session.isLoggedIn()) {
            sendRedirect("/page/dashboard");
        } else {
            this
            .setAttr("pageTitle", "Home - MadToDo")
            .setAttr("isHome", true)
            .setAttr("headerScripts", Arrays.asList(new String[] {
                    "/static/js/madtodo.js",
                    "/static/js/xhr.js",
                    "/static/js/user.js"
            }))
            .setAttr("footerScripts", Arrays.asList(new String[] {
                    "/static/js/home.js"
            }));

            renderJSPView("/pages/c-index.jsp");
        }
    }

    ////
    // url { /page/dashboard }
    public void dashboard() {
        if (!session.isLoggedIn()) {
            sendRedirect("/");
        } else {
            final User user = User.findById(session.getInt("userId"));

            this
            .setAttr("pageTitle", "Dashboard - MadToDo")
            .setAttr("isDashboard", true)
            .setAttr("headerScripts", Arrays.asList(new String[] {
                    "/static/js/madtodo.js",
                    "/static/js/xhr.js",
                    "/static/js/user.js",
                    "/static/js/task.js"
            }))
            .setAttr("footerScripts", Arrays.asList(new String[] {
                    "/static/js/dashboard.js",
                    "/static/js/dialog.js"
            }))
            .setAttr("user", user)
            .setAttr("isUserSet", true);

            renderJSPView("/pages/c-dashboard.jsp");
        }
    }

    ////
    // url { /page/profile }
    public void profile() {
        if (!session.isLoggedIn()) {
            sendRedirect("/");
        } else {
            final User user = User.findById(session.getInt("userId"));

            this
            .setAttr("pageTitle", "Profile - MadToDo")
            .setAttr("isProfile", true)
            .setAttr("headerScripts", Arrays.asList(new String[] {
                    "/static/js/madtodo.js",
                    "/static/js/xhr.js",
                    "/static/js/user.js",
                    "/static/js/task.js"
            }))
            .setAttr("footerScripts", Arrays.asList(new String[] {
                    "/static/js/profile.js",
                    "/static/js/dialog.js"
            }))
            .setAttr("user", user)
            .setAttr("isUserSet", true);

            renderJSPView("/pages/c-profile.jsp");
        }
    }

    ////
    // url { /page/search/:filter/:keyword }
    public void search() {
        final String filter = getParam(0, null);
        final String keyword = getParam(1, "");

        if (!session.isLoggedIn() || filter == null) {
            sendRedirect("/");
        } else {
            int userId = session.getInt("userId");

            if (filter.equals("all")) {
                List<User> users = User.searchByUsername(keyword);
                setAttr("users", users);

                List<Category> cats = Category.searchByName(keyword, userId);
                setAttr("cats", cats);

                List<Task> tasks = Task.searchByName(keyword);
                setAttr("tasks", tasks);
            }
            else if (filter.equals("username")) {
                List<User> users = User.searchByUsername(keyword);
                setAttr("users", users);
            }
            else if (filter.equals("category")) {
                List<Category> cats = Category.searchByName(keyword, userId);
                setAttr("cats", cats);
            }
            else if (filter.equals("task")) {
                List<Task> tasks = Task.searchByName(keyword);
                setAttr("tasks", tasks);
            }
            else {
                print404();
                return;
            }

            User user = User.findById(userId);

            this
            .setAttr("pageTitle", "Search - MadToDo")
            .setAttr("isSearch", true)
            .setAttr("headerScripts", Arrays.asList(new String[] {
                    "/static/js/madtodo.js",
                    "/static/js/xhr.js",
                    "/static/js/user.js",
                    "/static/js/task.js",
            }))
            .setAttr("footerScripts", Arrays.asList(new String[] {
                    "/static/js/search.js",
            }))
            .setAttr("user", user)
            .setAttr("isUserSet", true)
            .setAttr("filter", filter)
            .setAttr("keyword", keyword);

            renderJSPView("/pages/c-search.jsp");
        }
    }

    ////
    // url { /page/logout
    public void logout() {
        if (session.isLoggedIn()) {
            session.logout();
        }

        sendRedirect("/");
    }

    // For testing purpose:

    ////
    // url { /page/jeyesp }
    public void jeyesp() {
        List<String> list = Arrays.asList(new String[] {
                "Foo", "Bar", "Qux"
        });

        setAttr("title", "Jeyesp Test");
        setAttr("list", list);
        setAttr("params", getParams());

        renderJSPView("/pages/jeyesp.jsp");
    }
}
