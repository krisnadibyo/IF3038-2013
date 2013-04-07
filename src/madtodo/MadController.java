package madtodo;

import java.io.IOException;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class MadController {
    private List<String> params;
    protected HttpServletRequest request;
    protected HttpServletResponse response;

    public MadController(HttpServletRequest request, HttpServletResponse response) {
        this.request = request;
        this.response = response;
    }

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

    public MadController() {
        this(null, null);
    }

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

    public void index() throws IOException {
        response.setContentType("text/html");
        String html = "<!DOCTYPE html>\n" +
                "<html>\n" +
                "    <head>\n" +
                "        <meta charset=\"utf-8\" />\n" +
                "        <title>" + this.getClass().getSimpleName() + "</title>\n" +
                "    </head>\n" +
                "    <body>\n" +
                "        <h1>Index of " + this.getClass().getSimpleName() + "</h1>\n" +
                "    </body>\n" +
                "</html>\n";

        response.getWriter().write(html);
    }
}
