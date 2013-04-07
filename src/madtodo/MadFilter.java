package madtodo;

import static madtodo.Configuration.getConfig;
import static madtodo.MadController.controllerlify;

import java.io.IOException;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

import javax.servlet.Filter;
import javax.servlet.FilterChain;
import javax.servlet.FilterConfig;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.json.JSONObject;

public class MadFilter implements Filter {
    public MadFilter() {

    }

    /**
     * @see Filter#destroy()
     */
    public void destroy() {

    }

    private boolean isServlet(String uri) {
        for (String servletUri : getConfig().getAppServletUriException()) {
            if (uri.startsWith(servletUri)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @see Filter#doFilter(ServletRequest, ServletResponse, FilterChain)
     */
    public void doFilter(ServletRequest request, ServletResponse response, FilterChain chain)
            throws IOException, ServletException {
        HttpServletRequest req = (HttpServletRequest) request;
        HttpServletResponse res = (HttpServletResponse) response;

        String uri = req.getRequestURI();

        // jsp files, servlets, /tests/, /static/, go ahead
        if (uri.endsWith(".jsp")
                || uri.startsWith("/tests/")
                || uri.startsWith("/static/")
                || isServlet(uri)) {
            chain.doFilter(request, response);
        } else {
            doRoute(uri, req, res, chain);
        }
    }

    public void doRoute(String uri, HttpServletRequest req, HttpServletResponse res, FilterChain chain)
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
            send404JsonMessage(req, res, e);
            return;
        }

        // Create the controller instance
        MadController ctrlObj;
        try {
            ctrlObj = (MadController) ctrlCls.newInstance();

            ctrlObj.setRequest(req);
            ctrlObj.setResponse(res);
            ctrlObj.setParams(router.getParams());
        } catch (InstantiationException e) {
            send404JsonMessage(req, res, e);
            return;
        } catch (IllegalAccessException e) {
            send404JsonMessage(req, res, e);
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
            send404JsonMessage(req, res, e);
            return;
        } catch (SecurityException e) {
            send404JsonMessage(req, res, e);
            return;
        }

        // Invoke action method
        try {
            actionMethod.invoke(ctrlObj);
        } catch (IllegalAccessException e) {
            send404JsonMessage(req, res, e);
            return;
        } catch (IllegalArgumentException e) {
            send404JsonMessage(req, res, e);
            return;
        } catch (InvocationTargetException e) {
            send404JsonMessage(req, res, e);
            return;
        }
    }

    public void send404JsonMessage(HttpServletRequest req, HttpServletResponse res, Exception e)
            throws IOException {
        // e.printStackTrace();

        res.setStatus(404);
        res.setContentType("application/json");

        JSONObject json = new JSONObject();
        json.put("error", 404);
        json.put("message", "404 Not Found");

        res.getWriter().write(json.toString());
    }

    /**
     * @see Filter#init(FilterConfig)
     */
    public void init(FilterConfig fConfig) throws ServletException {

    }
}
