package madtodo.controllers;

import java.util.LinkedList;
import java.util.List;

import madtodo.MadController;
import madtodo.models.Category;

public class CategoryController extends MadController {
    @Override
    public void index() {
        print404();
    }

    /**
     * url: /category/all
     */
    public void all() {
        Category[] cats = Category.findAll();
        if (cats == null) {
            print404();
        } else {
            // TODO
        }
    }

    /**
     * url: /category/get/:id
     */
    public void get() {
        int id = Integer.parseInt(getParam(0, "0"));

        Category cat = Category.findById(id);
        if (cat == null) {
            print404();
        } else {
            // TODO
        }
    }

    /**
     * url: /category/name/:name
     */
    public void name() {
        String name = getParam(0, "");

        Category cat = Category.findByName(name, session.getInt("userId"));
        if (cat == null) {
            print404();
        } else {
            // TODO
        }
    }

    /**
     * url: /category/user/[:username]
     */
    public void user() {
        String username = getParam(0) != null ? getParam(0)
                : session.getString("user");

        Category[] cats = Category.findAllByUser(username);
        if (cats == null) {
            print404();
        } else {
            // TODO
        }
    }

    /**
     * url: /category/hint/:name
     */
    public void hint() {
        String name = urlDecode(getParam(0, ""));
        List<String> hints = new LinkedList<String>();

        Category[] cats = Category.searchByName(name, session.getInt("userId"));
        if (cats == null || name == "") {
            print404();
        } else {
            for (Category cat : cats) {
                hints.add(cat.getName());
            }

            // TODO
        }
    }

    /**
     * url: (POST) /category/create/:name
     */
    public void create() {
        String name = urlDecode(getParam(0, ""));

        // TODO
    }

    /**
     * url: (POST) /category/delete/:name
     */
    public void delete() {
        String name = urlDecode(getParam(0, ""));

        // TODO
    }
}
