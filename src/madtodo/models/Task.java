package madtodo.models;

import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

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

    public static Task[] findAll() {
        String sql = "SELECT task.* FROM " + table;

        return findAll(sql, Task.class, null);
    }

    public static Task[] findAllByUsername(final String username) {
        String sql = "SELECT task.* FROM " + table +
                " LEFT JOIN " + User.table + " ON (task.user_id = user.id)" +
                " WHERE user.username = ?";

        return findAll(sql, Task.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setString(1, username);
            }
        });
    }

    public static Task[] findAllByAssignee(final String assignee) {
        String sql = "SELECT task.* FROM " + table +
                " LEFT JOIN " + User.table + " ON (task.assignee_id = user.id)" +
                " WHERE user.username = ?";

        return findAll(sql, Task.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setString(1, assignee);
            }
        });
    }

    public static Task[] findAllByUserCategory(final String category, final String username) {
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
        String sql = "SELECT task.* FROM " + table +
                " WHERE task.id = ? LIMIT 1";

        return findOne(sql, Task.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setInt(1, id);
            }
        });
    }

    public static Task[] searchByName(final String name) {
        String sql = "SELECT task.* FROM " + table +
                " WHERE task.name LIKE ?";

        return findAll(sql, Task.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setString(1, "%" + name + "%");
            }
        });
    }

    // Foreign object getters
    public String getCategory() {
        return Category.findById(getCategoryId()).getName();
    }

    public String getUsername() {
        return User.findById(getUserId()).getUsername();
    }

    public String getAssignee() {
        User assignee = User.findById(getAssigneeId());
        return (assignee != null) ? assignee.getUsername() : null;
    }

    public Tag[] getTags() {
        return Tag.findByTaskId(getId());
    }

    public String getTagsAsString() {
        int i = 0;
        StringBuilder sb = new StringBuilder();

        Tag[] tags = getTags();
        if (tags != null) {
            for (Tag tag : getTags()) {
                if (i++ != 0) {
                    sb.append(", ");
                }
                sb.append(tag.getName());
            }
        }

        return sb.toString();
    }

    // {[
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
    // ]}
}
