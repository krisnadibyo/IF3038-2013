package madtodo.servlets;

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
    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        response.setContentType("text/plain");

        // response string builder (64k)
        StringBuilder ressb = new StringBuilder(1024 << 6);

        // [Test] Create a JSON Object
        JSONObject json = new JSONObject();
        json.put("Foo", "Bar");
        json.put("Qux", new JSONArray(new int[] { 1, 2, 3, 4, 5 }));

        // [Test] Parse config.json
        JSONTokener t = new JSONTokener(readFileToString("config.json"));
        JSONObject jsonx = new JSONObject(t);

        ressb.append("Foo Bar Qux - From FooServlet\n");
        ressb.append("JSON: " + json.toString() + "\n");
        ressb.append("JSON['mysql']['username']: " + (String) jsonx.getJSONObject("mysql").get("username") + "\n");

        response.getWriter().write(ressb.toString());
    }

    /**
     * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
     */
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        doGet(request, response);
    }

}
