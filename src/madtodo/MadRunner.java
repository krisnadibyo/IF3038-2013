package madtodo;

import java.net.InetSocketAddress;

import org.eclipse.jetty.server.Server;
import org.eclipse.jetty.server.handler.ContextHandler;
import org.eclipse.jetty.server.handler.HandlerList;
import org.eclipse.jetty.server.handler.ResourceHandler;
import org.eclipse.jetty.webapp.WebAppContext;

public class MadRunner {
    protected String getResource(String path) {
        return this.getClass().getClassLoader().getResource(path).toExternalForm();
    }

    private ContextHandler getStaticHandler()
    {
        ResourceHandler hRes = new ResourceHandler();
        hRes.setResourceBase(getResource("static"));
        hRes.setDirectoriesListed(true);

        ContextHandler hCtx = new ContextHandler();
        hCtx.setContextPath("/static");
        hCtx.setHandler(hRes);

        return hCtx;
    }

    private MadRunner(String args[]) {
        Configuration config = null;

        try {
            config = Configuration.loadConfiguration("config.json");
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Error reading file (config.json), or maybe file not found!");
            System.exit(1);
        }

        Server server = new Server(new InetSocketAddress(config.getBindAddress(), config.getPort()));

        WebAppContext hWebApp = new WebAppContext();
        hWebApp.setContextPath("/");
        hWebApp.setDescriptor(getResource("WEB-INF/web.xml"));
        hWebApp.setResourceBase(getResource("web"));

        HandlerList hl = new HandlerList();
        hl.addHandler(getStaticHandler());
        hl.addHandler(hWebApp);

        server.setHandler(hl);
        try {
            server.start();
        } catch (Exception e) {
            System.out.println("Couldn't start server!");
        }

        System.out.format("Server started! [http://%s:%d/ or http://127.0.0.1:%d/]\n",
                config.getBindAddress(), config.getPort(), config.getPort());
        System.out.format("Hit Ctrl-C to stop server.\n");

        try {
            server.join();
        } catch (InterruptedException e) {
            System.out.println("Server interrupted!");
        }
    }

    public static void main(String args[]) {
        new MadRunner(args);
    }
}
