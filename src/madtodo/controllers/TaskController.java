package madtodo.controllers;

import madtodo.MadController;

public class TaskController extends MadController {
    @Override
    public void index() {
        print404();
    }

    /**
     * url: /task/all
     */
    public void all() {
        // TODO
    }

    /**
     * url: /tas/get/:id/[:complete]
     */
    public void get() {
        // TODO
    }

    /**
     * url: /task/search_name/:name/[:complete]
     */
    public void search_name() {
        // TODO
    }

    /**
     * url: /task/hine/:name
     */
    public void hint() {
        // TODO
    }

    /**
     * url: /task/category/:categoryName/[:complete]
     */
    public void category() {
        // TODO
    }

    /**
     * url: /task/user/:username/[:complete]
     */
    public void user() {
        // TODO
    }

    /**
     * url: /task/assignee/:assignee/[:complete]
     */
    public void assignee() {
        // TODO
    }

    /**
     * url: /task/tag/:tagName/[:complete]
     */
    public void tag() {
        // TODO
    }

    /**
     * url: (POST) /task/done/:taskId
     */
    public void done() {
        // TODO
    }

    /**
     * url: (POST) /task/undone/:taskId
     */
    public void undone() {
        // TODO
    }

    /**
     * url: (POST) /task/create
     */
    public void create() {
        // TODO
    }

    /**
     * url: (POST) /task/edit
     */
    public void edit() {
        // TODO
    }

    /**
     * url: (POST) /task/delete/:id
     */
    public void delete() {
        // TODO
    }
}
