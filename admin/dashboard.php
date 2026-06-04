<?php

require_once __DIR__ . '/../includes/functions.php';

$admin = require_role('admin');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['weapon_type_name'])) {
        $name = trim($_POST['weapon_type_name']);
        if ($name === '') {
            $errors[] = 'Weapon type name must not be empty.';
        } else {
            $id = next_id($conn, 'msweapontype', 'weaponTypeId', 'WT');
            $query = $conn->prepare('INSERT INTO msweapontype (weaponTypeId, weaponTypeName) VALUES (?, ?)');
            $query ->bind_param('ss', $id, $name);
            $query->execute();
            header('Location: dashboard.php');
            exit;
        }
    }

    if (isset($_POST['payment_method_name'])) {
        $name = trim($_POST['payment_method_name']);
        if ($name === '') {
            $errors[] = 'Payment method name must not be empty.';
        } else {
            $id = next_id($conn, 'mspayment', 'paymentId', 'PY');
            $used = 0;
            $query = $conn->prepare('INSERT INTO mspayment (paymentId, paymentType, paymentUsed) VALUES (?, ?, ?)');
            $query->bind_param('ssi', $id, $name, $used);
            $query->execute();
            header('Location: dashboard.php');
            exit;
        }
    }
}

$monthly = [];
for ($i = 1; $i <= 12; $i++) {
    $monthly[$i] = ['transactions' => 0, 'revenue' => 0];
}
$result = $conn->query('SELECT MONTH(createdAt) AS monthNumber, COUNT(*) AS transactions, COALESCE(SUM(subtotal), 0) AS revenue FROM transactiondetail WHERE YEAR(createdAt) = YEAR(CURRENT_DATE()) GROUP BY MONTH(createdAt)');
while ($result && $row = $result->fetch_assoc()) {
    $monthly[(int) $row['monthNumber']] = ['transactions' => (int) $row['transactions'], 'revenue' => (int) $row['revenue']];
}
$maxTransactions = max(1, ...array_column($monthly, 'transactions'));

$maxRevenue = max(1, ...array_column($monthly, 'revenue'));

$monthNames = [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

$recent = $conn->query('SELECT transactionId, weaponId, subtotal, createdAt FROM transactiondetail ORDER BY createdAt DESC LIMIT 20');

$totalUsers = (int) $conn->query("SELECT COUNT(*) AS total FROM msuser WHERE LOWER(role) <> 'admin'")->fetch_assoc()['total'];

$topUsers = $conn->query('SELECT u.userId, u.username, COALESCE(SUM(t.subtotal), 0) AS spent, COUNT(t.transactionId) AS totalTransactions FROM msuser u LEFT JOIN transactiondetail t ON t.userid = u.userId WHERE LOWER(u.role) <> "admin" GROUP BY u.userId, u.username ORDER BY totalTransactions DESC, spent DESC LIMIT 5');

$totalWeapons = (int) $conn->query('SELECT COUNT(*) AS total FROM msweapon')->fetch_assoc()['total'];

$topWeapons = $conn->query('SELECT weaponName, weaponSold FROM msweapon ORDER BY weaponSold DESC LIMIT 5');

$totalPayments = (int) $conn->query('SELECT COUNT(*) AS total FROM mspayment')->fetch_assoc()['total'];

$topPayments = $conn->query('SELECT paymentType, paymentUsed FROM mspayment ORDER BY paymentUsed DESC LIMIT 5');

$weaponTypes = $conn->query('SELECT weaponTypeName FROM msweapontype ORDER BY weaponTypeName');

$payments = $conn->query('SELECT paymentType FROM mspayment ORDER BY paymentType');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/dashboard.css" />
    <title>Severos - Admin Dashboard</title>
  </head>
  <body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    <main>
      <div class="row">
        <section class="monthly">
          <h3>Monthly Transactions</h3>
          <div class="chart">
            <?php foreach ($monthly as $number => $data): ?>
              <div class="month">
                <div class="bars">
                  <div class="transactions">
                    <p class="value"><?= e($data['transactions']) ?></p>
                    <div class="bar" style="height: <?= (int) (($data['transactions'] / $maxTransactions) * 92) ?>%"></div>
                  </div>
                  <div class="revenue">
                    <p class="value"><?= e($data['revenue']) ?></p>
                    <div class="bar" style="height: <?= (int) (($data['revenue'] / $maxRevenue) * 92) ?>%"></div>
                  </div>
                </div>
                <p class="name"><?= e($monthNames[$number]) ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
        <section class="recent">
          <h3>Recent Transactions</h3>
          <ul>
            <?php while ($row = $recent->fetch_assoc()): ?>
              <li>
                <p><?= e($row['weaponId']) ?></p>
                <p><?= money($row['subtotal']) ?></p>
                <p><?= e($row['createdAt']) ?></p>
              </li>
            <?php endwhile; ?>
          </ul>
        </section>
      </div>
      <div class="row">
        <section class="users">
          <div class="table">
            <h3>Total Users</h3>
            <p><?= e($totalUsers) ?></p>
          </div>
          <div class="table">
            <h3>Users</h3>
            <ul>
              <?php while ($row = $topUsers->fetch_assoc()): ?>
                <li>
                  <p><?= e($row['username']) ?></p>
                  <p><?= money($row['spent']) ?></p>
                  <p><?= e($row['totalTransactions']) ?> Transactions</p>
                </li>
              <?php endwhile; ?>
            </ul>
          </div>
        </section>
        <section class="weapons">
          <div class="table">
            <h3>Total Weapons</h3>
            <p><?= e($totalWeapons) ?></p>
          </div>
          <div class="table">
            <h3>Top Weapons</h3>
            <ul>
              <?php while ($row = $topWeapons->fetch_assoc()): ?>
                <li>
                  <p><?= e($row['weaponName']) ?></p>
                  <p><?= e($row['weaponSold']) ?> Sold</p>
                </li>
              <?php endwhile; ?>
            </ul>
          </div>
        </section>
        <section class="payments">
          <div class="table">
            <h3>Total Payment Methods</h3>
            <p><?= e($totalPayments) ?></p>
          </div>
          <div class="table">
            <h3>Top Payment Methods</h3>
            <ul>
              <?php while ($row = $topPayments->fetch_assoc()): ?>
                <li>
                  <p><?= e($row['paymentType']) ?></p>
                  <p><?= e($row['paymentUsed']) ?> Used</p>
                </li>
              <?php endwhile; ?>
            </ul>
          </div>
        </section>
      </div>
      <div class="row">
        <section class="weapon-types">
          <div class="table">
            <h3>Weapon Types</h3>
            <ul>
              <?php while ($row = $weaponTypes->fetch_assoc()): ?>
                <li><?= e($row['weaponTypeName']) ?></li>
              <?php endwhile; ?>
            </ul>
          </div>
        </section>
        <section class="payment-methods">
          <div class="table">
            <h3>Payment Methods</h3>
            <ul>
              <?php while ($row = $payments->fetch_assoc()): ?>
                <li><?= e($row['paymentType']) ?></li>
              <?php endwhile; ?>
            </ul>
          </div>
        </section>
        <section class="forms">
          <?php if ($errors): ?>
            <p style="color: #ffb4b4;"><?= e(implode(' ', $errors)) ?></p>
          <?php endif; ?>
          <section class="table">
            <h3>Add Weapon Type</h3>
            <form class="content" method="POST">
              <label for="weapon-type">Weapon Type Name</label>
              <input type="text" name="weapon_type_name" id="weapon-type" />
              <button class="primary">Add Weapon Type</button>
            </form>
          </section>
          <section class="table">
            <h3>Add Payment Method</h3>
            <form class="content" method="POST">
              <label for="payment-method">Payment Method Name</label>
              <input type="text" name="payment_method_name" id="payment-method" />
              <button class="primary">Add Payment Method</button>
            </form>
          </section>
        </section>
      </div>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
  </body>
</html>
