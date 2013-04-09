package madtodo.models;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.List;

import madtodo.MadDB.PrepareFunction;
import madtodo.MadModel;

class TaskTag {
    public static final String table = "tbl_task_tag AS task_tag";
}

public class Tag extends MadModel {
    public static final String table = "tbl_tag AS tag";

    private int id;
    private String name;

    public void init(int id, String name) {
        this.id = id;
        this.name = name;
    }

    @Override
    public void init(ResultSet rs) throws SQLException {
        init(rs.getInt("id"),
                rs.getString("name"));
    }

    public static List<Tag> findAll() {
        String sql = "SELECT tag.* FROM " + table;
        return findAll(sql, Tag.class, null);
    }

    public static List<Tag> findByTaskId(final int taskId) {
        String sql = "SELECT tag.* FROM " + table +
                " LEFT JOIN " + TaskTag.table + " ON (tag.id = task_tag.tag_id)" +
                " WHERE task_tag.task_id = ?";

        return findAll(sql, Tag.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setInt(1, taskId);
            }
        });
    }

    public static Tag findById(final int id) {
        String sql = "SELECT tag.* FROM " + table +
                " WHERE tag.id = ?";

        return findOne(sql, Tag.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setInt(1, id);
            }
        });
    }

    public static List<Tag> searchTagByName(final String name) {
        String sql = "SELECT tag.* FROM " + table +
                " WHERE tag.name LIKE ?";

        return findAll(sql, Tag.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setString(1, "%" + name + "%");
            }
        });
    }

    //// {[
    public int getId() {
        return id;
    }

    public String getName() {
        return name;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setName(String name) {
        this.name = name;
    }
    //// ]}
}
