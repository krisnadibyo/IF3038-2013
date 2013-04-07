package madtodo;

import static madtodo.MadController.controllerlify;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

public class MadRouter {
    private String controller = null;
    private String controllerClassName = null;
    private String action = null;
    private final List<String> params = new ArrayList<String>(10);

    public MadRouter(String uri) {
        List<String> splittedUri = Arrays.asList(uri.substring(1).split("/"));
        if (splittedUri.size() >= 1 && splittedUri.get(0).length() > 0) {
            this.controller = splittedUri.get(0);
            this.controllerClassName = controllerlify(this.controller);
        }

        if (splittedUri.size() >= 2) {
            this.action = splittedUri.get(1);
        }

        if (splittedUri.size() > 2) {
            for (int i = 2; i < splittedUri.size(); i++) {
                params.add(splittedUri.get(i));
            }
        }
    }

    public String getController() {
        return controller;
    }

    public String getControllerClassName() {
        return controllerClassName;
    }

    public String getAction() {
        return action;
    }

    public List<String> getParams() {
        return params;
    }
}
