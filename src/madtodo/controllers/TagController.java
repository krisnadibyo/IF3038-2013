package madtodo.controllers;

import madtodo.MadController;

public class TagController extends MadController {
    @Override
    public void index() {
        print404();
    }

    /**
     * url: /tag/all
     */
    public void all() {
        // TODO
    }

    /**
     * url: /tag/get/:id
     */
    public void get() {
        // TODO
    }

    /**
     * url: /tag/name/:name
     */
    public void name() {
        // TODO
    }

    /**
     * url: /tag/hint/:name
     */
    public void hint() {
        // TODO
    }

    /**
     * url: (POST) /tag/create/:name
     */
    public void create() {

    }

    /**
     * url: (POST) /tag/delete/:name
     */
    public void delete() {

    }

    /**
     * url: (POST) /tag/reassign/:taskId
     */
    public void reassign() {

    }

    /**
     * url: (POST) /tag/edit/:id
     */
    public void edit() {

    }
}
