<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) && !empty($_COOKIE['remember'])) {
    $bagian = explode(':', $_COOKIE['remember'], 2);

    if (count($bagian) === 2) {
        [$id, $token] = $bagian;

        try {
            require_once __DIR__ . '/koneksi.php';

            $s = $pdo->prepare("SELECT * FROM users WHERE id = :id");
            $s->execute(['id' => (int)$id]);
            $u = $s->fetch(PDO::FETCH_ASSOC);

            if ($u && !empty($u['remember_token'])
                && hash_equals($u['remember_token'], hash('sha256', $token))) {
                $_SESSION['user_id'] = $u['id'];
                $_SESSION['nama']    = $u['nama'];
                $_SESSION['role']    = $u['role'];
            }
        } catch (Throwable $e) {
        }
    }
}