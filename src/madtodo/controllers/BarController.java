package madtodo.controllers;

import java.io.IOException;

import madtodo.MadController;

import org.json.JSONObject;

public class BarController extends MadController {
    public void hello() throws IOException {
        String firstParam = getParam(0);
        String secondParam = getParam(1);
        int paramCount = getParamCount();

        if (paramCount > 2) {
            print404JSON();
        } else {
            JSONObject json = new JSONObject()
            .put("msg", "HelloWorld!")
            .put("params", getParams())

            .put("param-count", paramCount)
            .put("first-param", firstParam)
            .put("second-param", secondParam);

            printJSON(json);
        }
    }

    public void method() throws IOException {
        JSONObject json = new JSONObject()
        .put("method", request.getMethod());

        printJSON(json);
    }
}
