<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Untuk koneksi ke SQLite
    $_GET['sqlite'] = $_POST['auth']['driver'] === 'sqlite' ? $_POST['auth']['db'] : '';
    $_GET['username'] = '';
}

function adminer_object() {
    class AdminerSoftware extends Adminer {
        function login($login, $password) {
            return true; // Skip login untuk SQLite
        }
        
        function credentials() {
            // Untuk SQLite, path database
            if (isset($_GET['sqlite'])) {
                return array($_SERVER['DOCUMENT_ROOT'] . '/../' . $_GET['sqlite'], '', '');
            }
            return array('localhost', '', '');
        }
        
        function database() {
            // Otomatis set ke database SQLite
            return '';
        }
        
        function loginForm() {
            // Form khusus untuk SQLite
            ?>
            <table cellspacing="0">
            <tr><th>Driver<th><select name="auth[driver]">
                <option value="sqlite" selected>SQLite 3
                <option value="server">MySQL
            </select>
            <tr><th>Database file<th><input type="text" name="auth[db]" value="<?php echo getenv('DB_DATABASE', 'database/database.sqlite') ?>">
            </table>
            <p><input type="submit" value="Login">
            <?php
            echo script("qsl('select').onchange = function() { this.form['auth[db]'].style.display = (this.value == 'sqlite' ? '' : 'none'); };");
            return true;
        }
    }
    
    return new AdminerSoftware;
}

include_once "adminer.php";