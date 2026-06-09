<?php
$host = 'sql305.ezyro.com';
$db   = 'ezyro_42111165_siaptrek';
$user = 'ezyro_42111165';
$pass = 'e0c306a09c';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Buat tabel session otomatis
$pdo->exec("CREATE TABLE IF NOT EXISTS php_sessions (
    id VARCHAR(128) NOT NULL PRIMARY KEY,
    data MEDIUMTEXT NOT NULL,
    last_activity INT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

class DBSessionHandler implements SessionHandlerInterface {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }
    public function open($path, $name): bool { return true; }
    public function close(): bool { return true; }
    public function read($id): string|false {
        $stmt = $this->pdo->prepare(
            "SELECT data FROM php_sessions WHERE id = ? AND last_activity > ?"
        );
        $stmt->execute([$id, time() - 7200]); // 2 jam
        $row = $stmt->fetch();
        return $row ? $row['data'] : '';
    }
    public function write($id, $data): bool {
        $stmt = $this->pdo->prepare(
            "REPLACE INTO php_sessions (id, data, last_activity) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$id, $data, time()]);
    }
    public function destroy($id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM php_sessions WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function gc($max): int|false {
        $stmt = $this->pdo->prepare(
            "DELETE FROM php_sessions WHERE last_activity < ?"
        );
        $stmt->execute([time() - $max]);
        return $stmt->rowCount();
    }
}

if (session_status() == PHP_SESSION_NONE) {
    $handler = new DBSessionHandler($pdo);
    session_set_save_handler($handler, true);
    register_shutdown_function('session_write_close');
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_lifetime', 7200);
    ini_set('session.gc_maxlifetime', 7200);
    session_name('SIAPTREK');
    session_start();
}
