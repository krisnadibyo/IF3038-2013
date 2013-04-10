package madtodo;

import javax.servlet.ServletContextEvent;
import javax.servlet.ServletContextListener;

public class MadListener implements ServletContextListener {
    public MadListener() {

    }

    public void contextInitialized(ServletContextEvent arg0) {
        Configuration.loadConfiguration("config.json");
        MadDB.loadDB();
    }

    public void contextDestroyed(ServletContextEvent arg0) {

    }
}
