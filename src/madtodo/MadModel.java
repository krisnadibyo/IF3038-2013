package madtodo;

public abstract class MadModel {
    public static final String table = "tbl_tblname AS tblname";
    protected final static MadDB db = MadDB.getDB();

    public MadModel() {

    }

    public MadModel(Object... params) {
        this();
    }
}
