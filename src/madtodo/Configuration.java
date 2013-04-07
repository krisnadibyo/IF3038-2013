package madtodo;

import static madtodo.MadFile.readFileToString;

import org.json.JSONObject;
import org.json.JSONTokener;

public class Configuration {
    private static Configuration config = null;

    private String bindAddress;
    private int port;

    private String mysqlHostname;
    private String mysqlUsername;
    private String mysqlPassword;
    private String mysqlDatabase;

    public static Configuration getConfig() {
        return config;
    }

    public static Configuration loadConfiguration(String jsonFile) throws Exception {
        config = new Configuration();

        JSONObject cfg = new JSONObject(new JSONTokener(readFileToString(jsonFile)));
        JSONObject mysqlCfg = cfg.getJSONObject("mysql");

        config.setBindAddress(cfg.getString("bind"));
        config.setPort(cfg.getInt("port"));

        config.setMysqlHostname(mysqlCfg.getString("hostname"));
        config.setMysqlUsername(mysqlCfg.getString("username"));
        config.setMysqlPassword(mysqlCfg.getString("password"));
        config.setMysqlDatabase(mysqlCfg.getString("database"));

        return config;
    }

    private Configuration() {

    }

    public String getBindAddress() {
        return bindAddress;
    }

    public int getPort() {
        return port;
    }

    public String getMysqlHostname() {
        return mysqlHostname;
    }

    public String getMysqlUsername() {
        return mysqlUsername;
    }

    public String getMysqlPassword() {
        return mysqlPassword;
    }

    public String getMysqlDatabase() {
        return mysqlDatabase;
    }

    public void setBindAddress(String bindAddress) {
        this.bindAddress = bindAddress;
    }

    public void setPort(int port) {
        this.port = port;
    }

    public void setMysqlHostname(String mysqlHostname) {
        this.mysqlHostname = mysqlHostname;
    }

    public void setMysqlUsername(String mysqlUsername) {
        this.mysqlUsername = mysqlUsername;
    }

    public void setMysqlPassword(String mysqlPassword) {
        this.mysqlPassword = mysqlPassword;
    }

    public void setMysqlDatabase(String mysqlDatabase) {
        this.mysqlDatabase = mysqlDatabase;
    }

}
