package madtodo.controllers;

import java.io.IOException;

import madtodo.MadController;

import org.json.JSONObject;

public class BarController extends MadController {
    public void hello() throws IOException {
        int paramCount = getParamCount();
        String firstParam = getParam(0);
        String secondParam = getParam(1);

        response.setContentType("application/json");

        JSONObject json = new JSONObject();
        json.put("msg", "HelloWorld!");
        json.put("params", getParams());

        json.put("param-count", paramCount);
        json.put("first-param", firstParam);
        json.put("second-param", secondParam);

        response.getWriter().write(json.toString());
    }

    public void method() throws IOException {
        response.setContentType("application/json");

        JSONObject json = new JSONObject();
        json.put("method", request.getMethod());

        response.getWriter().write(json.toString());
    }
}
