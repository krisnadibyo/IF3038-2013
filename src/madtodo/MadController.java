package madtodo;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.json.JSONObject;

public abstract class MadController {
    private List<String> params;

    protected HttpServletRequest request;
    protected HttpServletResponse response;
    protected MadSession session;

    protected BufferedReader reqReader;
    protected PrintWriter resWriter;

    public MadController() {

    }

    public void init(HttpServletRequest request, HttpServletResponse response, List<String> params) {
        setRequest(request);
        setResponse(response);
        setParams(params);

        this.session = new MadSession();
        this.session.init(request.getSession());

        try {
            this.reqReader = request.getReader();
            this.resWriter = response.getWriter();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    // Helpers
    public void printStringResponse(String contentType, String responseString) {
        response.setContentType(contentType);
        resWriter.write(responseString);
    }

    public void printJSON(JSONObject json) {
        printStringResponse("application/json", json.toString());
    }

    public void printHTML(String html) {
        printStringResponse("text/html", html);
    }

    public void printPlainText(String text) {
        printStringResponse("text/plain", text);
    }

    public void print404() {
        response.setStatus(404);
        printPlainText("\"404 Not Found\"");
    }

    public void renderJSPView(String uri) {
        try {
            request.getRequestDispatcher("/views" + uri).forward(request, response);
        } catch (Exception e) {
            response.setStatus(500);
            e.printStackTrace(resWriter);
            e.printStackTrace();
        }
    }

    public void sendRedirect(String url) {
        try {
            response.sendRedirect(url);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    // Getters & setters
    //// {[
    public HttpServletRequest getRequest() {
        return request;
    }

    public HttpServletResponse getResponse() {
        return response;
    }

    public List<String> getParams() {
        return params;
    }

    public void setRequest(HttpServletRequest request) {
        this.request = request;
    }

    public void setResponse(HttpServletResponse response) {
        this.response = response;
    }

    public void setParams(List<String> params) {
        this.params = params;
    }
    //// ]}

    // Param getters
    protected String getParam(int index, String defaultValue) {
        if (index >= params.size()) {
            return defaultValue;
        } else {
            return params.get(index);
        }
    }

    protected String getParam(int index) {
        return getParam(index, "");
    }

    protected int getParamCount() {
        return params.size();
    }

    protected String getQueryParam(String qParamName) {
        return request.getParameter(qParamName);
    }

    // Request attribute shortcuts
    public MadController setAttr(String key, Object val) {
        request.setAttribute(key, val);
        return this;
    }

    public Object getAttr(String key) {
        return request.getAttribute(key);
    }

    // Default action
    public void index() throws IOException {
        setAttr("ctrlClassName", this.getClass().getSimpleName());
        renderJSPView("/default.jsp");
    }

    // Static functions
    public static String controllerlify(String ctrlName) {
        return Character.toUpperCase(
                ctrlName.charAt(0)) +
                ctrlName.substring(1) + "Controller";
    }
}
