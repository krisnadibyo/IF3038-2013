package madtodo;

import static madtodo.MadFile.readFileToString;

import java.util.ArrayList;
import java.util.List;

import org.json.JSONArray;
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

    private String appDefaultController;
    private String appDefaultAction;
    private List<String> appServletUriException;

    public static boolean isLoaded() {
        return config != null;
    }

    public static Configuration getConfig() {
        if (!isLoaded()) {
            System.err.println("Warning! Configuration is not loaded!");
        }
        return config;
    }

    public static Configuration loadConfiguration(String jsonFile) {
        config = new Configuration();

        try {
            JSONObject cfg = new JSONObject(new JSONTokener(readFileToString(jsonFile)));
            JSONObject mysqlCfg = cfg.getJSONObject("mysql");
            JSONObject appCfg = cfg.getJSONObject("app");

            config.setBindAddress(cfg.getString("bind"));
            config.setPort(cfg.getInt("port"));

            config.setMysqlHostname(mysqlCfg.getString("hostname"));
            config.setMysqlUsername(mysqlCfg.getString("username"));
            config.setMysqlPassword(mysqlCfg.getString("password"));
            config.setMysqlDatabase(mysqlCfg.getString("database"));

            config.setAppDefaultController(appCfg.getString("default-controller"));
            config.setAppDefaultAction(appCfg.getString("default-action"));

            List<String> servletUriException = new ArrayList<String>();
            JSONArray tmp = appCfg.getJSONArray("servlet-uri-exception");
            if (tmp != null) {
                for (int i = 0; i < tmp.length(); i++) {
                    servletUriException.add(tmp.getString(i));
                }
            }
            config.setAppServletUriException(servletUriException);
        } catch (Exception e) {
            e.printStackTrace();
        }

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

    public String getAppDefaultController() {
        return appDefaultController;
    }

    public String getAppDefaultAction() {
        return appDefaultAction;
    }

    public List<String> getAppServletUriException() {
        return appServletUriException;
    }

    public void setAppDefaultController(String appDefaultController) {
        this.appDefaultController = appDefaultController;
    }

    public void setAppDefaultAction(String appDefaultAction) {
        this.appDefaultAction = appDefaultAction;
    }

    public void setAppServletUriException(List<String> appServletUriException) {
        this.appServletUriException = appServletUriException;
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
