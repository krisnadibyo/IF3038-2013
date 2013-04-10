package madtodo.controllers;

import java.util.Arrays;
import java.util.List;

import madtodo.MadController;

public class PageController extends MadController {
    public void jeyesp() {
        List<String> list = Arrays.asList(new String[] {
                "Foo", "Bar", "Qux"
        });

        setAttr("title", "Jeyesp Test");
        setAttr("list", list);
        setAttr("params", getParams());

        renderJSPView("/page/jeyesp.jsp");
    }
}
