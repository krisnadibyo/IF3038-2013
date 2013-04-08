package madtodo.etc;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.LinkedList;
import java.util.List;

import madtodo.Configuration;
import madtodo.MadDB;
import madtodo.MadDB.ResultSetFunction;

public class TrySql {
    public static void fNew() {
        MadDB db = MadDB.getDB();

        final List<String> taskNameList = new LinkedList<String>();

        db.executeQuery("SELECT task.* FROM tbl_task AS task WHERE name LIKE ?", new ResultSetFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setString(1, "%s%");
            }

            public void withResultSet(ResultSet rs, Object... obj) throws SQLException {
                while(rs.next()) {
                    taskNameList.add(rs.getString("name") + " - " + rs.getString("deadline"));
                }
            }
        });

        for (String name : taskNameList) {
            System.out.println(name);
        }
    }

    /* ---
    public static void fOld() throws Exception {
        Class.forName("com.mysql.jdbc.Driver").newInstance();

        String cs = String.format("jdbc:mysql://%s/%s?user=%s&password=%s",
                getConfig().getMysqlHostname(),
                getConfig().getMysqlDatabase(),
                getConfig().getMysqlUsername(),
                getConfig().getMysqlPassword());

        Connection conn = DriverManager.getConnection(cs);

        Statement stmt = conn.createStatement();
        ResultSet rs = stmt.executeQuery(
                "SELECT task.id, task.name, user.name FROM tbl_task AS task " +
                "LEFT JOIN tbl_user AS user ON (task.user_id = user.id)");

        while (rs.next()) {
            System.out.format("id: %s, name: %s, user: %s\n", rs.getInt("id"), rs.getString("name"), rs.getString("user.name"));
        }

        rs.close();
        stmt.close();
        conn.close();
    }
    --- */

    public static void main(String[] args) throws Exception {
        Configuration.loadConfiguration("config.json");
        MadDB.loadDB();

        fNew();
        /* fOld(); */
    }
}
