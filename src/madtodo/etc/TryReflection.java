package madtodo.etc;

import java.lang.reflect.Method;

interface A {
    public String getFoo();
    public int getId();
}

class A1 implements A {
    public String getFoo() {
        return "This is A1";
    }

    public int getId() {
        return 1;
    }
}

class A2 implements A {
    public String getFoo() {
        return "A2 A2 A2";
    }

    public String getFoo(String... params) {
        return params[0];
    }

    public int getId() {
        return 2;
    }
}

public class TryReflection {
    public static void main(String[] args) throws Exception {
        Class<?> c = Class.forName("madtodo.etc.A2");
        A a = (A) c.newInstance();

        Method m = c.getMethod("getFoo", String[].class);
        String ms = (String) m.invoke(a, new Object[] { new String[] { "HelloWorld!" }});

        System.out.println(ms);
    }
}
