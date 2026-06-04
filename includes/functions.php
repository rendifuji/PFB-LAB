<?php

require_once __DIR__ . '/connection.php';

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function money($value) {
    return '$' . number_format((float) $value, 2);
}

function current_user() {
    if (empty($_COOKIE['userId'])) {
        return null;
    }

    $rawRole = strtolower(trim($_COOKIE['role'] ?? 'member'));
    $role = str_starts_with($rawRole, 'admin') ? 'admin' : 'member';

    return [
        'userId' => $_COOKIE['userId'],
        'username' => $_COOKIE['username'] ?? 'User',
        'role' => $role,
        'credit' => (int) ($_COOKIE['credit'] ?? 0),
    ];
}

function set_login_cookies($user) {
    $expires = time() + 86400;
    setcookie('userId', $user['userId'], $expires, '/');
    setcookie('username', $user['username'], $expires, '/');
    setcookie('role', strtolower(trim($user['role'])) === 'admin' ? 'admin' : 'member', $expires, '/');
    setcookie('credit', (string) $user['credit'], $expires, '/');
}

function clear_login_cookies() {
    foreach (['userId', 'username', 'role', 'credit'] as $name) {
        setcookie($name, '', time() - 3600, '/');
        unset($_COOKIE[$name]);
    }
}

function require_role($roles) {
    $user = current_user();
    $roles = array_map('strtolower', (array) $roles);

    if (!$user) {
        header('Location: ../auth/login.php');
        exit;
    }

    if (!in_array($user['role'], $roles, true)) {
        header('Location: ../user/index.php');
        exit;
    }

    return $user;
}

function redirect_if_authenticated() {
    $user = current_user();
    if (!$user) {
        return;
    }

    header('Location: ' . ($user['role'] === 'admin' ? '../admin/dashboard.php' : '../user/index.php'));
    exit;
}

function refresh_user_cookie($conn, $userId) {
    $query = $conn->prepare('SELECT userId, username, role, credit FROM msuser WHERE userId = ?');
    $query->bind_param('s', $userId);
    $query->execute();
    $user = $query->get_result()->fetch_assoc();
    if ($user) {
        set_login_cookies($user);
    }
}

function weapon_type_options($conn) {
    $types = [];
    $result = $conn->query('SELECT weaponTypeId, weaponTypeName FROM msweapontype ORDER BY weaponTypeName');
    while ($result && $row = $result->fetch_assoc()) {
        $types[] = $row;
    }
    return $types;
}

function payment_options($conn) {
    $payments = [];
    $result = $conn->query('SELECT paymentId, paymentType FROM mspayment ORDER BY paymentType');
    while ($result && $row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
    return $payments;
}

function next_id($conn, $table, $column, $prefix) {
    $sql = "SELECT MAX(CAST(SUBSTRING($column, 3) AS UNSIGNED)) AS lastNumber FROM $table";
    $result = $conn->query($sql);
    $last = (int) (($result && $result->num_rows) ? $result->fetch_assoc()['lastNumber'] : 0);
    return $prefix . str_pad((string) ($last + 1), 3, '0', STR_PAD_LEFT);
}

function get_weapon($conn, $weaponId) {
    $query = $conn->prepare(
        'SELECT w.*, wt.weaponTypeName
         FROM msweapon w
         JOIN msweapontype wt ON wt.weaponTypeId = w.weaponTypeId
         WHERE w.weaponId = ?'
    );
    $query->bind_param('s', $weaponId);
    $query->execute();
    return $query->get_result()->fetch_assoc();
}
