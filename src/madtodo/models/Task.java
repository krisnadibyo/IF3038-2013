package madtodo.models;

import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.List;

import madtodo.MadDB.PrepareFunction;
import madtodo.MadModel;

public class Task extends MadModel {
    public static final String table = "tbl_task AS task";

    private int id;
    private String name;
    private String attachment;
    private java.sql.Date deadline;
    private int status;
    private int categoryId;
    private int userId;
    private int assigneeId;

    public void init(int id, String name, String attachment, Date deadline,
            int status, int categoryId, int userId, int assigneeId) {
        this.id = id;
        this.name = name;
        this.attachment = attachment;
        this.deadline = deadline;
        this.status = status;
        this.categoryId = categoryId;
        this.userId = userId;
        this.assigneeId = assigneeId;
    }

    @Override
    public void init(ResultSet rs) throws SQLException {
        init(rs.getInt("id"),
                rs.getString("name"),
                rs.getString("attachment"),
                rs.getDate("deadline"),
                rs.getInt("status"),
                rs.getInt("category_id"),
                rs.getInt("user_id"),
                rs.getInt("assignee_id"));
    }

    public static List<Task> findAll() {
        String sql = "SELECT task.* FROM " + table;

        return findAll(sql, Task.class, null);
    }

    public static List<Task> findAllByUsername(final String username) {
        String sql = "SELECT task.* FROM " + table +
                " LEFT JOIN " + User.table + " ON (task.user_id = user.id)" +
                " WHERE user.username = ?";

        return findAll(sql, Task.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setString(1, username);
            }
        });
    }

    public static List<Task> findAllByAssignee(final String assignee) {
        String sql = "SELECT task.* FROM " + table +
                " LEFT JOIN " + User.table + " ON (task.assignee_id = user.id)" +
                " WHERE user.username = ?";

        return findAll(sql, Task.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setString(1, assignee);
            }
        });
    }

    public static List<Task> findAllByUserCategory(final String category, final String username) {
        String sql = "SELECT task.* FROM " + table +
                " LEFT JOIN " + Category.table + " ON (task.category_id = category.id)" +
                " LEFT JOIN " + User.table + " ON (category.user_id = user.id)" +
                " WHERE category.name = ? AND user.username = ?";

        return findAll(sql, Task.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setString(1, category);
                stmt.setString(2, username);
            }
        });
    }

    public static Task findById(final int id) {
        String sql = "SELECT task.* FROM " + table + " WHERE task.id = ? LIMIT 1";

        return findOne(sql, Task.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setInt(1, id);
            }
        });
    }

    // Extra getters
    public String getCategory() {
        // TODO
        return null;
    }

    public String getUsername() {
        // TODO
        return null;
    }

    public String getAssignee() {
        // TODO
        return null;
    }

    public String getTags() {
        // TODO
        return null;
    }

    // Getters & setters
    //// {[
    public int getId() {
        return id;
    }

    public String getName() {
        return name;
    }

    public String getAttachment() {
        return attachment;
    }

    public java.sql.Date getDeadline() {
        return deadline;
    }

    public int getStatus() {
        return status;
    }

    public int getCategoryId() {
        return categoryId;
    }

    public int getUserId() {
        return userId;
    }

    public int getAssigneeId() {
        return assigneeId;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setName(String name) {
        this.name = name;
    }

    public void setAttachment(String attachment) {
        this.attachment = attachment;
    }

    public void setDeadline(java.sql.Date deadline) {
        this.deadline = deadline;
    }

    public void setStatus(int status) {
        this.status = status;
    }

    public void setCategoryId(int categoryId) {
        this.categoryId = categoryId;
    }

    public void setUserId(int userId) {
        this.userId = userId;
    }

    public void setAssigneeId(int assigneeId) {
        this.assigneeId = assigneeId;
    }
    //// ]}
}
