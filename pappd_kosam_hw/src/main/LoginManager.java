/**
 * A LoginManager osztály a felhasználók be- és kijelentkeztetéséért felel az
 * alkalmazásban.
 *
 * @author A Te Neved
 * @version 1.0
 */
public class LoginManager {

    /**
     * Bejelentkezteti a felhasználót a megadott felhasználónév és jelszó alapján.
     *
     * @param username A felhasználó felhasználóneve
     * @param password A felhasználó jelszava
     * @return true, ha a bejelentkezés sikeres, különben false
     */
    public boolean login(String username, String password) {
        // IDE JÖN A KÓD: pl. adatbázis ellenőrzés és session beállítás
        
        // Helykitöltő: Sikeres bejelentkezés szimulálása
        if ("validUser".equals(username) && "securePass".equals(password)) {
            System.out.println(username + " sikeresen bejelentkezett.");
            return true;
        } else {
            System.out.println("Hibás bejelentkezési adatok.");
            return false;
        }
    }

    /**
     * Kijelentkezteti a jelenleg bejelentkezett felhasználót.
     * A metódus érvényteleníti a felhasználó session-jét.
     */
    public void logout() {
        // IDE JÖN A KÓD: pl. session megszüntetése, cookie-k törlése
        System.out.println("A felhasználó kijelentkeztetve.");
    }
}