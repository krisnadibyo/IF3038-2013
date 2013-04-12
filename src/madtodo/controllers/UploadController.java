package madtodo.controllers;

import madtodo.MadController;

public class UploadController extends MadController {
    @Override
    public void index() {
        print404();
    }

    /**
     * url: (POST) /upload/avatar/:username
     */
    public void avatar() {
        // TODO
    }

    /**
     * url: (POST) /upload/attachment/:taskId
     */
    public void attachment() {
        // TODO
    }

    /* Testing */

    /**
     * url: (POST) /upload/test
     */
    public void test() {
        // TODO
    }
}
