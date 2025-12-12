import org.junit.jupiter.api.DisplayName;
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class LoginManagerTest {

    // Példányosítjuk az osztályt, amit tesztelünk
    LoginManager loginManager = new LoginManager();

    @Test
    @DisplayName("Sikeres bejelentkezés helyes adatokkal (Happy Path)")
    void testLoginSuccess() {
        // A LoginManager kódja alapján: "validUser" és "securePass" kell a sikerhez
        boolean result = loginManager.login("validUser", "securePass");
        
        // Elvárjuk, hogy true legyen
        assertTrue(result, "A bejelentkezésnek sikeresnek kell lennie helyes adatokkal.");
    }

    @Test
    @DisplayName("Sikertelen bejelentkezés hibás jelszóval")
    void testLoginFailureWrongPassword() {
        boolean result = loginManager.login("validUser", "rosszJelszo");
        
        // Elvárjuk, hogy false legyen
        assertFalse(result, "A bejelentkezésnek sikertelennek kell lennie hibás jelszóval.");
    }

    @Test
    @DisplayName("Sikertelen bejelentkezés hibás felhasználónévvel")
    void testLoginFailureWrongUser() {
        boolean result = loginManager.login("rosszUser", "securePass");
        assertFalse(result, "A bejelentkezésnek sikertelennek kell lennie hibás felhasználónévvel.");
    }

    @Test
    @DisplayName("Kijelentkezés tesztelése")
    void testLogout() {
        // A logout metódus jelenleg void és csak kiír a konzolra, 
        // de meghívjuk, hogy biztosan ne dobjon hibát (Exception).
        assertDoesNotThrow(() -> loginManager.logout());
    }
}