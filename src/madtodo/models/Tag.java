package madtodo.models;

import java.sql.ResultSet;
import java.sql.SQLException;

import madtodo.MadModel;

public class Tag extends MadModel {
    public static class TaskTag extends MadModel {
        public static final String table = "tbl_task_tag AS task_tag";

        @Override
        public void init(ResultSet rs) throws SQLException {
            // TODO Auto-generated method stub
        }

        // TODO
    }

    public static final String table = "tbl_tag AS tag";

    @Override
    public void init(ResultSet rs) throws SQLException {
        // TODO Auto-generated method stub
    }

    // TODO
}
