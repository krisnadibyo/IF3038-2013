package madtodo;

import javax.servlet.http.HttpSession;

public class MadSession {
    private HttpSession session;

    public void init(HttpSession session) {
        setHttpSession(session);
    }

    public Object get(String key) {
        return session.getAttribute(key);
    }

    public String getString(String key) {
        return (String) get(key);
    }

    public int getInt(String key) {
        return (Integer) get(key);
    }

    public boolean getBool(String key) {
        return (Boolean) get(key);
    }

    public MadSession set(String key, Object val) {
        session.setAttribute(key, val);
        return this;
    }

    public MadSession rm(String key) {
        session.removeAttribute(key);
        return this;
    }

    public boolean isLoggedIn() {
        return get("login") != null;
    }

    public void login(String username, int userId) {
        this
        .set("login", true)
        .set("username", username)
        .set("userid", userId);
    }

    public void logout() {
        this
        .rm("login")
        .rm("username")
        .rm("userid");

        session.invalidate();
    }

    //// {[
    public HttpSession getHttpSession() {
        return session;
    }

    public void setHttpSession(HttpSession session) {
        this.session = session;
    }
    //// ]}
}
