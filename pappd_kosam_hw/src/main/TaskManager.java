import java.util.ArrayList;
import java.util.List;

/**
 * A TaskManager osztály a feladatok (teendők) létrehozásáért,
 * delegálásáért és lekérdezéséért felel az alkalmazásban.
 *
 * @author A Te Neved
 * @version 1.0
 */
public class TaskManager {

    /**
     * Új feladatot hoz létre egy adott felhasználóhoz és hozzárendeli.
     *
     * @param taskDescription A létrehozandó feladat részletes leírása
     * @param assignToUser A felhasználó felhasználóneve, akinek a feladatot delegáljuk
     * @return true, ha a feladat sikeresen létrejött és hozzá lett rendelve, különben false
     */
    public boolean addTask(String taskDescription, String assignToUser) {
        // IDE JÖN A KÓD: pl. adatbázisba írás
        System.out.println("Feladat hozzáadva (" + taskDescription + ") felhasználóhoz: " + assignToUser);
        
        // Helykitöltő visszatérés
        return true;
    }

    /**
     * Lekéri egy felhasználó aktuális, még be nem fejezett feladatait.
     *
     * @param username A felhasználó felhasználóneve, akinek a feladatait le akarjuk kérni
     * @return A felhasználóhoz rendelt feladatok listája (String-ek listájaként), vagy üres lista, ha nincs feladata.
     */
    public List<String> getTasks(String username) {
        // IDE JÖN A KÓD: pl. adatbázisból történő lekérdezés
        System.out.println("Feladatok lekérése felhasználóhoz: " + username);
        
        // Helykitöltő: egy üres listával tér vissza
        return new ArrayList<>(); 
    }
}