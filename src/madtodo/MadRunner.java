package madtodo;

import java.awt.Dimension;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.net.InetSocketAddress;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;

import org.eclipse.jetty.server.Server;
import org.eclipse.jetty.server.handler.ContextHandler;
import org.eclipse.jetty.server.handler.HandlerList;
import org.eclipse.jetty.server.handler.ResourceHandler;
import org.eclipse.jetty.webapp.WebAppContext;

public class MadRunner {
    protected final String getResource(final String path) {
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
        System.out.format("Hit Ctrl-C to stop server.\n");

        if (!(args.length > 0) || !args[0].equals("--no-gui")) {
            RunnerGUI.run(server);
        }

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

    // Runner GUI
    private static class RunnerGUI extends JFrame {
        private static final long serialVersionUID = 1L;

        private static final void quit(final Server server) {
            try {
                server.stop();
                System.exit(0);
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }

        public RunnerGUI(final Server server) {
            super("MadRunner");
            Configuration config = Configuration.getConfig();

            addWindowListener(new WindowAdapter() {
                @Override
                public void windowClosing(WindowEvent e) {
                    quit(server);
                }
            });

            JLabel infoLabel = new JLabel();
            infoLabel.setText(String.format("Server is running... http://%s:%s/",
                    config.getBindAddress(), config.getPort()));

            JButton stopButton = new JButton("Stop Server");
            stopButton.addActionListener(new ActionListener() {
                public void actionPerformed(ActionEvent e) {
                    quit(server);
                }
            });

            JPanel mainPanel = new JPanel();
            mainPanel.add(infoLabel);
            mainPanel.add(stopButton);

            setContentPane(mainPanel);
            setPreferredSize(new Dimension(300, 100));

            pack();
            setLocationRelativeTo(null);
            setVisible(true);
        }

        public static final void run(final Server server) {
            new Thread(new Runnable() {
                public void run() {
                    new RunnerGUI(server);
                }
            }).start();
        }
    }
}
