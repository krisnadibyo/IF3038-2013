package madtodo.etc;

import madtodo.Configuration;
import madtodo.MadDB;
import madtodo.models.Task;

public class TryModel {
    public static void main(String[] args) {
        Configuration.loadConfiguration("config.json");
        MadDB.loadDB();

        System.out.println("TaskID 1: " + Task.findById(1).getName());

        System.out.println("\nValjean's tasks:");
        for (Task t : Task.findAllByUsername("valjean")) {
            System.out.println(t.getName());
        }

        System.out.println("\nValjean as assignee:");
        for (Task t : Task.findAllByAssignee("valjean")) {
            System.out.println(t.getName());
        }

        System.out.println("\nValjean's Misc tasks:");
        for (Task t : Task.findAllByUserCategory("Misc", "valjean")) {
            System.out.println(t.getName());
        }
    }
}
