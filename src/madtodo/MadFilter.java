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

    /**
     * @see Filter#doFilter(ServletRequest, ServletResponse, FilterChain)
     */
    public void doFilter(ServletRequest request, ServletResponse response,
            FilterChain chain)
                    throws IOException, ServletException {
        HttpServletRequest xrequest = (HttpServletRequest) request;
        HttpServletResponse xresponse = (HttpServletResponse) response;

        String uri = xrequest.getRequestURI();

        // jsp files, servlets, /tests/, /static/, go ahead
        if (uri.endsWith(".jsp")
                || uri.startsWith("/tests/")
                || uri.startsWith("/static/")
                || isServlet(uri)) {
            chain.doFilter(request, response);
        } else {
            route(uri, xrequest, xresponse, chain);
        }
    }

    private boolean isServlet(String uri) {
        for (String servletUri : getConfig().getAppServletUriException()) {
            if (uri.startsWith(servletUri)) {
                return true;
            }
        }

        return false;
    }

    private void print404JSON(HttpServletRequest request,
            HttpServletResponse response, Exception e)
                    throws IOException {
        // e.printStackTrace();

        JSONObject json = new JSONObject()
        .put("error", 404)
        .put("message", "404 Not Found");

        response.setStatus(404);
        response.setContentType("application/json");
        response.getWriter().write(json.toString());
    }


    private void route(String uri, HttpServletRequest request,
            HttpServletResponse response, FilterChain chain)
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
            print404JSON(request, response, e);
            return;
        }

        // Create the controller instance
        MadController ctrlObj;
        try {
            ctrlObj = (MadController) ctrlCls.newInstance();
            ctrlObj.init(request, response, router.getParams());
        } catch (InstantiationException e) {
            print404JSON(request, response, e);
            return;
        } catch (IllegalAccessException e) {
            print404JSON(request, response, e);
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
            print404JSON(request, response, e);
            return;
        } catch (SecurityException e) {
            print404JSON(request, response, e);
            return;
        }

        // Invoke action method
        try {
            actionMethod.invoke(ctrlObj);
        } catch (IllegalAccessException e) {
            print404JSON(request, response, e);
            return;
        } catch (IllegalArgumentException e) {
            print404JSON(request, response, e);
            return;
        } catch (InvocationTargetException e) {
            print404JSON(request, response, e);
            return;
        }
    }

    /**
     * @see Filter#init(FilterConfig)
     */
    public void init(FilterConfig fConfig) throws ServletException {

    }
}
