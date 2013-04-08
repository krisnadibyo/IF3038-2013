package madtodo.etc;

import java.util.Arrays;
import java.util.List;

public class TryRoute {
    public static void main(String[] args) {
        String path = "/task/delete/10".substring(1);

        List<String> splittedPath = Arrays.asList(path.split("/"));
        System.out.println(splittedPath);
    }
}
