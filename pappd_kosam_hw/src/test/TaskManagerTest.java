import org.junit.jupiter.api.DisplayName;
import org.junit.jupiter.api.Test;
import java.util.List;
import static org.junit.jupiter.api.Assertions.*;

class TaskManagerTest {

    TaskManager taskManager = new TaskManager();

    @Test
    @DisplayName("Feladat hozzáadása sikeres")
    void testAddTask() {
        boolean result = taskManager.addTask("Új feladat leírása", "pappd");
        
        // A jelenlegi kód mindig true-t ad vissza
        assertTrue(result, "A feladat hozzáadásának sikeresnek kell lennie.");
    }

    @Test
    @DisplayName("Feladatok lekérdezése üres listát ad (jelenlegi implementáció)")
    void testGetTasks() {
        List<String> tasks = taskManager.getTasks("kosam");
        
        // Ellenőrizzük, hogy nem null az eredmény
        assertNotNull(tasks, "A feladatlista nem lehet null.");
        
        // A jelenlegi implementáció (return new ArrayList<>()) miatt üresnek kell lennie
        assertTrue(tasks.isEmpty(), "A feladatlistának üresnek kell lennie a jelenlegi kód alapján.");
    }
}