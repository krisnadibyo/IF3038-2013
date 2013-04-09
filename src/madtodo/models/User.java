package madtodo.models;

import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.List;

import madtodo.MadDB.PrepareFunction;
import madtodo.MadModel;

public class User extends MadModel {
    public static final String table = "tbl_user AS user";

    private int id;
    private String name;
    private String username;
    private String password;
    private String email;
    private java.sql.Date birthday;
    private String avatar;
    private String bio;

    public void init(int id, String name, String username, String password,
            String email, Date birthday, String avatar, String bio) {
        this.id = id;
        this.name = name;
        this.username = username;
        this.password = password;
        this.email = email;
        this.birthday = birthday;
        this.avatar = avatar;
        this.bio = bio;
    }

    @Override
    public void init(ResultSet rs) throws SQLException {
        init(rs.getInt("id"),
                rs.getString("name"),
                rs.getString("username"),
                rs.getString("password"),
                rs.getString("email"),
                rs.getDate("birthday"),
                rs.getString("avatar"),
                rs.getString("bio"));
    }

    public static List<User> findAll() {
        String sql = "SELECT user.* FROM " + table;

        return findAll(sql, User.class, null);
    }

    public static User findById(final int id) {
        String sql = "SELECT user.* FROM " + table +
                " WHERE user.id = ?";

        return findOne(sql, User.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setInt(1, id);
            }
        });
    }

    public static User findByUsername(final String username) {
        String sql = "SELECT user.* FROM " + table +
                " WHERE user.username = ?";

        return findOne(sql, User.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setString(1, username);
            }
        });
    }

    public static List<User> searchByUsername(final String username) {
        String sql = "SEELECT user.* FROM " + table +
                " WHERE user.username LIKE ?";

        return findAll(sql, User.class, new PrepareFunction() {
            public void prepare(PreparedStatement stmt) throws SQLException {
                stmt.setString(1, "%" + username + "%");
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

    public String getUsername() {
        return username;
    }

    public String getPassword() {
        return password;
    }

    public String getEmail() {
        return email;
    }

    public java.sql.Date getBirthday() {
        return birthday;
    }

    public String getAvatar() {
        return avatar;
    }

    public String getBio() {
        return bio;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setName(String name) {
        this.name = name;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public void setBirthday(java.sql.Date birthday) {
        this.birthday = birthday;
    }

    public void setAvatar(String avatar) {
        this.avatar = avatar;
    }

    public void setBio(String bio) {
        this.bio = bio;
    }
    //// ]}
}
