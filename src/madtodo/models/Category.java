package madtodo.models;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import madtodo.MadDB.PrepareFunction;
import madtodo.MadModel;

public class Category extends MadModel {
    public static final String table = "tbl_category AS category";

    private int id;
    private String name;
    private int userId;

    public void init(int id, String name, int userId) {
        this.id = id;
        this.name = name;
        this.userId = userId;
    }

    @Override
    public void init(ResultSet rs) throws SQLException {
        init(rs.getInt("id"),
                rs.getString("name"),
                rs.getInt("user_id"));
    }

    public static Category[] findAll() {
        String sql = "SELECT category.* FROM " + table;

        return findAll(sql, Category.class, null);
    }

    public static Category findById(final int id) {
        String sql = "SELECT category.* FROM " + table +
                " WHERE category.id = ?";

        return findOne(sql, Category.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setInt(1, id);
            }
        });
    }

    public static Category findByName(final String name, final int userId) {
        String sql = "SELECT category.* FROM " + table +
                " WHERE category.name = ? AND category.user_id = ?";

        return findOne(sql, Category.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setString(1, name);
                stmt.setInt(2, userId);
            }
        });
    }

    public static Category[] searchByName(final String name, final int userId) {
        String sql = "SELECT category.* FROM " + table +
                " WHERE category.name LIKE ? AND category.user_id = ?";

        return findAll(sql, Category.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setString(1, "%" + name + "%");
                stmt.setInt(2, userId);
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

    public int getUserId() {
        return userId;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setName(String name) {
        this.name = name;
    }

    public void setUserId(int userId) {
        this.userId = userId;
    }
    //// ]}
}
