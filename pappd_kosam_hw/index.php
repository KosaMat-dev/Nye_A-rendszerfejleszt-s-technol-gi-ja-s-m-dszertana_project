<?php
session_start();
$JSON_FILE = 'data.json';
$error = '';

// --- Segédfüggvények ---

// Adatok betöltése a JSON fájlból
function loadData() {
    global $JSON_FILE;
    if (!file_exists($JSON_FILE)) {
        die("Hiba: A data.json fájl nem található!");
    }
    $data = json_decode(file_get_contents($JSON_FILE), true);
    return $data;
}

// Adatok mentése a JSON fájlba
function saveData($data) {
    global $JSON_FILE;
    // JSON_PRETTY_PRINT: olvasható formátum
    // JSON_UNESCAPED_UNICODE: ékezetes karakterek helyes mentése
    file_put_contents($JSON_FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// --- Műveletek Kezelése (POST) ---

// Adatok betöltése a műveletek előtt
$data = loadData();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. BEJELENTKEZÉS
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $loggedIn = false;

        foreach ($data['users'] as $user) {
            if ($user['username'] === $username && $user['password'] === $password) {
                $_SESSION['user'] = $user; // Felhasználó mentése a session-be
                $loggedIn = true;
                break;
            }
        }
        if (!$loggedIn) {
            $error = "Hibás felhasználónév vagy jelszó!";
        }
    }

    // 2. KIJELENTKEZÉS
    if (isset($_POST['action']) && $_POST['action'] === 'logout') {
        session_unset();
        session_destroy();
        header("Location: index.php"); // Átirányítás a tiszta login oldalra
        exit;
    }

    // 3. ÚJ FELADAT HOZZÁADÁSA (Csak vezető)
    if (isset($_POST['action']) && $_POST['action'] === 'addTask') {
        // Biztonsági ellenőrzés: csak a vezető adhat hozzá
        if (isset($_SESSION['user']) && $_SESSION['user']['title'] === 'IT Csoportvezető') {
            $taskDesc = $_POST['task_description'];
            $assignToUser = $_POST['assign_to']; // Ez a 'username'

            // Keressük meg a célfelhasználót és adjuk hozzá a feladatot
            foreach ($data['users'] as $key => $user) {
                if ($user['username'] === $assignToUser) {
                    $data['users'][$key]['tasks'][] = $taskDesc;
                    break;
                }
            }
            saveData($data); // Változások mentése a fájlba
        }
    }
}

// Friss adatok betöltése (fontos, hogy a hozzáadás után is frissüljön a nézet)
$data = loadData();

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egyszerű Feladatkezelő</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        h1, h2, h3 {
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }
        form {
            margin-bottom: 20px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }
        input[type="text"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Fontos a helyes méretezéshez */
        }
        button {
            background: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .logout-btn {
            background: #dc3545;
            float: right;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .task-list {
            list-style-type: none;
            padding: 0;
        }
        .task-list li {
            background: #fdfdfd;
            border: 1px solid #eee;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 4px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">

        <?php if (isset($_SESSION['user'])): ?>
            
            <form method="POST" style="background: none; padding: 0; box-shadow: none;">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="logout-btn">Kijelentkezés</button>
            </form>
            
            <h1>Üdvözlet, <?php echo htmlspecialchars($_SESSION['user']['fullName']); ?>!</h1>
            <p style="margin-top: -10px; font-style: italic; color: #555;">(<?php echo htmlspecialchars($_SESSION['user']['title']); ?>)</p>

            <hr>

            <?php if ($_SESSION['user']['title'] === 'IT Csoportvezető'): ?>
                
                <h2>Új feladat létrehozása</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="addTask">
                    <div>
                        <label for="task_description">Feladat leírása:</label>
                        <textarea id="task_description" name="task_description" rows="3" required></textarea>
                    </div>
                    <div>
                        <label for="assign_to">Delegálás (Munkavállaló):</label>
                        <select id="assign_to" name="assign_to" required>
                            <option value="">Válasszon...</option>
                            <?php foreach ($data['users'] as $user): ?>
                                <?php // Csak munkavállalóknak lehessen delegálni ?>
                                <?php if ($user['title'] === 'IT Munkavállaló'): ?>
                                    <option value="<?php echo $user['username']; ?>">
                                        <?php echo htmlspecialchars($user['fullName']); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit">Feladat hozzáadása</button>
                </form>

                <hr>
                
                <h2>Munkavállalók aktuális feladatai</h2>
                <?php foreach ($data['users'] as $user): ?>
                    <?php if ($user['title'] === 'IT Munkavállaló'): ?>
                        <h3><?php echo htmlspecialchars($user['fullName']); ?></h3>
                        <ul class="task-list">
                            <?php if (empty($user['tasks'])): ?>
                                <li>Nincs kiosztott feladata.</li>
                            <?php else: ?>
                                <?php foreach ($user['tasks'] as $task): ?>
                                    <li><?php echo htmlspecialchars($task); ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                <?php endforeach; ?>

            <?php else: ?>
                
                <h2>Az Önre kiosztott feladatok</h2>
                <ul class="task-list">
                    <?php 
                    // Friss feladatlista kell, nem a session-ből (ami elavult lehet)
                    // Megkeressük az aktuális felhasználót a friss $data-ban
                    $currentUserTasks = [];
                    foreach ($data['users'] as $user) {
                        if ($user['username'] === $_SESSION['user']['username']) {
                            $currentUserTasks = $user['tasks'];
                            break;
                        }
                    }
                    ?>

                    <?php if (empty($currentUserTasks)): ?>
                        <li>Nincs Önre kiosztott feladat.</li>
                    <?php else: ?>
                        <?php foreach ($currentUserTasks as $task): ?>
                            <li><?php echo htmlspecialchars($task); ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

            <?php endif; ?>


        <?php else: ?>

            <h1>Bejelentkezés</h1>
            <p>Kérem, jelentkezzen be a feladatkezelő rendszerbe.</p>
            
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="action" value="login">
                <div>
                    <label for="username">Felhasználónév:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div>
                    <label for="password">Jelszó:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Bejelentkezés</button>
            </form>

        <?php endif; ?>
    
    </div> </body>
</html>