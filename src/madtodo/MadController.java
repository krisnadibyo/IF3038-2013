package madtodo;

import static madtodo.MadConstant._2k;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import org.json.JSONObject;

public abstract class MadController {
    private List<String> params;

    protected HttpServletRequest request;
    protected HttpServletResponse response;
    protected HttpSession session;

    protected BufferedReader reqReader;
    protected PrintWriter resWriter;

    public MadController() {

    }

    public void init(HttpServletRequest request, HttpServletResponse response, List<String> params) {
        setRequest(request);
        setResponse(response);
        setParams(params);

        this.session = request.getSession();

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

    // Getters & setters
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

    // Default action
    public void index() throws IOException {
        StringBuilder html = new StringBuilder(_2k)
        .append("<!DOCTYPE html>\n")
        .append("<html>\n")
        .append("    <head>\n")
        .append("        <meta charset=\"utf-8\" />\n")
        .append("        <title>" + this.getClass().getSimpleName() + "</title>\n")
        .append("    </head>\n")
        .append("    <body>\n")
        .append("        <h1>Index of " + this.getClass().getSimpleName() + "</h1>\n")
        .append("    </body>\n")
        .append("</html>\n");

        printHTML(html.toString());
    }

    // Static functions
    public static String controllerlify(String ctrlName) {
        return Character.toUpperCase(
                ctrlName.charAt(0)) +
                ctrlName.substring(1) + "Controller";
    }
}
