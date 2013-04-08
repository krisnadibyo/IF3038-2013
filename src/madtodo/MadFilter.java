package madtodo;

import static madtodo.Configuration.getConfig;
import static madtodo.MadRouter.route;

import java.io.IOException;

import javax.servlet.Filter;
import javax.servlet.FilterChain;
import javax.servlet.FilterConfig;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class MadFilter implements Filter {
    public MadFilter() {

    }

    /**
     * @see Filter#destroy()
     */
    public void destroy() {

    }

    /**
     * @see Filter#doFilter(ServletRequest, ServletResponse, FilterChain)
     */
    public void doFilter(ServletRequest request, ServletResponse response,
            FilterChain chain)
                    throws IOException, ServletException {
        HttpServletRequest xrequest = (HttpServletRequest) request;
        HttpServletResponse xresponse = (HttpServletResponse) response;

        String uri = xrequest.getRequestURI();

        // jsp files, servlets, /tests/, /static/, go ahead
        if (uri.endsWith(".jsp")
                || uri.startsWith("/tests/")
                || uri.startsWith("/static/")
                || isServlet(uri)) {
            chain.doFilter(request, response);
        } else {
            route(uri, xrequest, xresponse);
        }
    }

    private boolean isServlet(String uri) {
        for (String servletUri : getConfig().getAppServletUriException()) {
            if (uri.startsWith(servletUri)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @see Filter#init(FilterConfig)
     */
    public void init(FilterConfig fConfig) throws ServletException {

    }
}
