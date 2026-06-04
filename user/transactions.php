<?php

require_once __DIR__ . '/../includes/functions.php';

$user = require_role('member');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = (int) ($_POST['amount'] ?? 0);
    if ($amount <= 0) {
        $errors[] = 'Top up amount must be a positive integer greater than 0.';
    } else {
        $query = $conn->prepare('UPDATE msuser SET credit = credit + ? WHERE userId = ?');
        $query->bind_param('is', $amount, $user['userId']);
        $query->execute();
        refresh_user_cookie($conn, $user['userId']);
        header('Location: transactions.php');
        exit;
    }
}

$query = $conn->prepare('SELECT transactionId, createdAt, subtotal FROM transactiondetail WHERE userid = ? ORDER BY createdAt DESC');
$query->bind_param('s', $user['userId']);
$query->execute();
$transactions = $query->get_result();

$query = $conn->prepare('SELECT COALESCE(SUM(subtotal), 0) AS monthlySpending FROM transactiondetail WHERE userid = ? AND MONTH(createdAt) = MONTH(CURRENT_DATE()) AND YEAR(createdAt) = YEAR(CURRENT_DATE())');
$query->bind_param('s', $user['userId']);
$query->execute();
$monthlySpending = (int) $query->get_result()->fetch_assoc()['monthlySpending'];

$query = $conn->prepare('SELECT credit FROM msuser WHERE userId = ?');
$query->bind_param('s', $user['userId']);
$query->execute();
$credit = (int) $query->get_result()->fetch_assoc()['credit'];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/transactions.css" />
    <title>Severos - Transactions</title>
  </head>
  <body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    <main>
      <header>
        <h1>Transactions</h1>
        <p>Every purchase seals your fate. Power is bought, not given.</p>
      </header>
      <div class="container">
        <div class="transactions">
          <header>
            <h3>Transaction ID</h3>
            <h3>Date</h3>
            <h3>Total Amount</h3>
          </header>
          <ol class="transaction-list">
            <?php if ($transactions->num_rows === 0): ?>
              <div class="transaction-item">
                <p>-</p>
                <p>No transaction yet</p>
                <p><?= money(0) ?></p>
              </div>
            <?php endif; ?>
            <?php while ($transaction = $transactions->fetch_assoc()): ?>
              <div class="transaction-item">
                <p><?= e($transaction['transactionId']) ?></p>
                <p><?= e($transaction['createdAt']) ?></p>
                <p><?= money($transaction['subtotal']) ?></p>
              </div>
            <?php endwhile; ?>
          </ol>
        </div>
        <aside class="credits">
          <div class="credits-card">
            <div>
              <h4>This Month's Spending</h4>
              <p><?= money($monthlySpending) ?></p>
            </div>

            <hr />

            <div>
              <h4>Your Credit</h4>
              <p><?= money($credit) ?></p>
            </div>

            <hr />

            <form class="topup" method="POST">
              <h4>Top Up Credit</h4>
              <?php if ($errors): ?>
                <p style="color: #ffb4b4;"><?= e(implode(' ', $errors)) ?></p>
              <?php endif; ?>
              <input type="number" name="amount" placeholder="Amount" />
              <button class="primary">Top Up</button>
            </form>
          </div>
        </aside>
      </div>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
  </body>
</html>
