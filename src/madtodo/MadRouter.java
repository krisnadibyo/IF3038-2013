package madtodo;

import static madtodo.Configuration.getConfig;
import static madtodo.MadController.controllerlify;

import java.io.IOException;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class MadRouter {
    private String controller = null;
    private String controllerClassName = null;
    private String action = null;
    private final List<String> params = new ArrayList<String>(10);

    private MadRouter(String uri) {
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

    public static void route(String uri, HttpServletRequest request,
            HttpServletResponse response)
                    throws IOException {
        MadRouter router = new MadRouter(uri);

        // Get controller, controller class
        Class<?> ctrlCls;
        try {
            String className = router.getControllerClassName();
            if (className == null) {
                className = controllerlify(getConfig().getAppDefaultController());
            }

            ctrlCls = Class.forName("madtodo.controllers." + className);
        } catch (ClassNotFoundException e) {
            print404(request, response, e);
            return;
        }

        // Create the controller instance
        MadController ctrlObj;
        try {
            ctrlObj = (MadController) ctrlCls.newInstance();
            ctrlObj.init(request, response, router.getParams());
        } catch (InstantiationException e) {
            print404(request, response, e);
            return;
        } catch (IllegalAccessException e) {
            print404(request, response, e);
            return;
        }

        // Get method from action
        Method actionMethod;
        try {
            String actionName = router.getAction();
            if (actionName == null) {
                actionName = getConfig().getAppDefaultAction();
            }

            actionMethod = ctrlCls.getMethod(actionName);
        } catch (NoSuchMethodException e) {
            print404(request, response, e);
            return;
        } catch (SecurityException e) {
            print404(request, response, e);
            return;
        }

        // Invoke action method
        try {
            actionMethod.invoke(ctrlObj);
        } catch (IllegalAccessException e) {
            print404(request, response, e);
            return;
        } catch (IllegalArgumentException e) {
            print404(request, response, e);
            return;
        } catch (InvocationTargetException e) {
            print404(request, response, e);
            return;
        }
    }

    private static void print404(HttpServletRequest request,
            HttpServletResponse response, Exception e)
                    throws IOException {
        // e.printStackTrace();

        response.setStatus(404);
        response.setContentType("text/plain");
        response.getWriter().write("\"404 Not Found\"");
    }
}
