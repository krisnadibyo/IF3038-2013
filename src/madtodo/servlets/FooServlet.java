package madtodo.servlets;

import static madtodo.MadConstant._16k;
import static madtodo.MadFile.readFileToString;

import java.io.IOException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.json.JSONArray;
import org.json.JSONObject;
import org.json.JSONTokener;

/**
 * Servlet implementation class FooServlet
 */
public class FooServlet extends HttpServlet {
    private static final long serialVersionUID = 1L;

    /**
     * @see HttpServlet#HttpServlet()
     */
    public FooServlet() {
        super();
    }

    /**
     * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
     */
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        // [Test] Create a JSON Object
        JSONObject json = new JSONObject()
        .put("Foo", "Bar")
        .put("Qux", new JSONArray(new int[] { 1, 2, 3, 4, 5 }));

        // [Test] Parse config.json
        JSONObject json2 = new JSONObject(new JSONTokener(readFileToString("config.json")));

        // response string builder (16k)
        StringBuilder ressb = new StringBuilder(_16k)
        .append("Foo Bar Qux - From FooServlet\n")
        .append("JSON: " + json.toString() + "\n")
        .append("JSON['mysql']: " + json2.getJSONObject("mysql").toString() + "\n")
        .append("JSON['mysql']['username']: " + (String) json2.getJSONObject("mysql").get("username") + "\n");

        response.setContentType("text/plain");
        response.getWriter().write(ressb.toString());
    }

    /**
     * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
     */
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        doGet(request, response);
    }

}
