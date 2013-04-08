package madtodo;

import static madtodo.Configuration.getConfig;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class MadDB {
    // An approach to create anonymous function (function delegate) in Java
    public static interface PrepareFunction {
        public void prepare(PreparedStatement stmt) throws SQLException;
    }

    public static interface ResultSetFunction extends PrepareFunction {
        public void withResultSet(ResultSet rs, Object... obj) throws SQLException;
    };

    private static MadDB dbInstance = null;

    private final String connectionString;

    public static MadDB getDB() {
        if (dbInstance == null) {
            System.err.println("Warning! DB is not loaded!");
        }
        return dbInstance;
    }

    public static void loadDB() {
        try {
            if (dbInstance == null) {
                dbInstance = new MadDB();
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private MadDB() {
        try {
            Class.forName("com.mysql.jdbc.Driver").newInstance();
        } catch (Exception e) {
            e.printStackTrace();
        }

        connectionString = String.format("jdbc:mysql://%s/%s?user=%s&password=%s",
                getConfig().getMysqlHostname(),
                getConfig().getMysqlDatabase(),
                getConfig().getMysqlUsername(),
                getConfig().getMysqlPassword());
    }

    public Connection connect() {
        Connection connection = null;
        try {
            connection = DriverManager.getConnection(connectionString);
        } catch(SQLException e) {
            e.printStackTrace();
        }

        return connection;
    }

    public void executeQuery(String sql, ResultSetFunction func, Object... paramObj) {
        Connection conn = connect();
        PreparedStatement stmt = null;
        ResultSet rs = null;

        try {
            stmt = conn.prepareStatement(sql);

            func.prepare(stmt);
            rs = stmt.executeQuery();
            func.withResultSet(rs, paramObj);

        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            closeAll(conn, stmt, rs);
        }
    }

    public int executeUpdate(String sql, PrepareFunction func) {
        Connection conn = connect();
        PreparedStatement stmt = null;
        int retVal = -1;

        try {
            stmt = conn.prepareStatement(sql);
            if (func != null) {
                func.prepare(stmt);
            }
            retVal = stmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            closeAll(conn, stmt);
        }

        return retVal;
    }

    public int executeUpdate(String sql) {
        return executeUpdate(sql, null);
    }

    public void closeAll(Connection conn, Statement stmt) {
        try {
            if (stmt != null) {
                stmt.close();
            }
            if (conn != null) {
                conn.close();
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void closeAll(Connection conn, Statement stmt, ResultSet rs) {
        try {
            if (rs != null) {
                rs.close();
            }
            closeAll(conn, stmt);
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
