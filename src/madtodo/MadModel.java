package madtodo;

import java.lang.reflect.Array;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.LinkedList;
import java.util.List;

import madtodo.MadDB.PrepareFunction;
import madtodo.MadDB.ResultSetFunction;

public abstract class MadModel {
    protected final static MadDB db = MadDB.getDB();

    public static final String table = "tbl_tblname AS tblname";
    abstract public void init(ResultSet rs) throws SQLException;

    // Generic base findAll() and findOne();
    @SuppressWarnings("unchecked")
    protected static<T extends MadModel> T[] findAll(String sql, final Class<T> c, final PrepareFunction pf) {
        final List<T> list = new LinkedList<T>();

        db.executeQuery(sql, new ResultSetFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                if (pf != null) { pf.prepare(stmt); }
            }

            public void withResultSet(ResultSet rs, Object... obj) throws SQLException {
                while(rs.next()) {
                    T t = null;
                    try {
                        t = c.newInstance();
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                    t.init(rs);
                    list.add(t);
                }
            }
        });

        if (list.size() > 0) {
            return list.toArray((T[]) Array.newInstance(c, list.size()));
        } else {
            return null;
        }
    }

    protected static<T extends MadModel> T findOne(String sql, final Class<T> c, final PrepareFunction pf) {
        final List<T> list = new LinkedList<T>();

        db.executeQuery(sql, new ResultSetFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                if (pf != null) { pf.prepare(stmt); }
            }

            public void withResultSet(ResultSet rs, Object... obj) throws SQLException {
                if (rs.next()) {
                    T t = null;
                    try {
                        t = c.newInstance();
                    } catch (Exception e) {
                        e.printStackTrace();
                    }

                    t.init(rs);
                    list.add(t);
                }
            }
        });

        if (list.size() > 0) {
            return list.get(0);
        } else {
            return null;
        }
    }
}
