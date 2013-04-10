package madtodo;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.InetSocketAddress;

import org.eclipse.jetty.server.Server;
import org.eclipse.jetty.server.handler.ContextHandler;
import org.eclipse.jetty.server.handler.HandlerList;
import org.eclipse.jetty.server.handler.ResourceHandler;
import org.eclipse.jetty.webapp.WebAppContext;

public class MadRunner {
    protected String getResource(final String path) {
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

    private MadRunner(final String[] args) {
        Configuration config = null;

        try {
            config = Configuration.loadConfiguration("config.json");
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Error reading file (config.json), or maybe file not found!");
            System.exit(1);
        }

        final Server server = new Server(new InetSocketAddress(config.getBindAddress(), config.getPort()));

        WebAppContext hWebApp = new WebAppContext();
        hWebApp.setContextPath("/");
        hWebApp.setDescriptor(getResource("WEB-INF/web.xml"));

        String resourceBase = getResource("WEB-INF/");
        resourceBase = resourceBase.substring(0, resourceBase.indexOf("WEB-INF/"));
        hWebApp.setResourceBase(resourceBase);

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

        new Thread(new Runnable() {
            public void run() {
                try {
                    while (true) {
                        System.out.format("Type 'stop' to stop server and quit: ");
                        String input = new BufferedReader(new InputStreamReader(System.in)).readLine();
                        if (input.equals("stop")) {
                            break;
                        }
                    }

                    server.stop();
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        }).start();

        try {
            server.join();
        } catch (InterruptedException e) {
            e.printStackTrace();
            System.out.println("Server interrupted!");
        }
    }

    public static void main(final String[] args)
            throws InterruptedException {
        Thread t = new Thread(new Runnable() {
            public void run() {
                new MadRunner(args);
            }
        });

        t.start();
        t.join();
    }
}
