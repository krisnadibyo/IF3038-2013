package jj.webrunner;

import org.eclipse.jetty.server.Server;
import org.eclipse.jetty.server.handler.ContextHandler;
import org.eclipse.jetty.server.handler.HandlerList;
import org.eclipse.jetty.server.handler.ResourceHandler;
import org.eclipse.jetty.webapp.WebAppContext;

public class WebRunner {
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

    private WebRunner(String args[]) {
        int port = 8088;

        if (args.length > 0) {
            port = Integer.parseInt(args[0]);
        }

        Server server = new Server(port);

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

        System.out.format("---- Server started! http://127.0.0.1:%d/\n", port);

        try {
            server.join();
        } catch (InterruptedException e) {
            System.out.println("Server intterupted!");
        }
    }

    public static void main(String args[]) {
        new WebRunner(args);
    }
}
