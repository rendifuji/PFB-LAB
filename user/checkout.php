<?php
require_once __DIR__ . '/../aunth/functions.php';

$user = require_role('member');
$weaponId = $_GET['id'] ?? ($_POST['weapon_id'] ?? '');
$weapon = get_weapon($conn, $weaponId);
$payments = payment_options($conn);
$errors = [];

if (!$weapon) {
    header('Location: marketplace.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentId = $_POST['payment_id'] ?? '';

    if ($paymentId === '') {
        $errors[] = 'Please choose one payment method.';
    }

    if ((int) $weapon['weaponStock'] < 1) {
        $errors[] = 'Weapon stock is empty.';
    }

    if ((int) $user['credit'] < (int) $weapon['weaponPrice']) {
        $errors[] = 'Your credit is not enough.';
    }

    if (!$errors) {
        $transactionId = next_id($conn, 'transactiondetail', 'transactionId', 'TR');
        $quantity = 1;
        $subtotal = (int) $weapon['weaponPrice'];

        $conn->begin_transaction();
        try {
            $query = $conn->prepare('INSERT INTO transactiondetail (transactionId, userid, paymentId, weaponId, createdAt, quantity, subtotal) VALUES (?, ?, ?, ?, NOW(), ?, ?)');
            $query->bind_param('ssssii', $transactionId, $user['userId'], $paymentId, $weaponId, $quantity, $subtotal);
            $query->execute();

            $query = $conn->prepare('UPDATE msweapon SET weaponStock = weaponStock - 1, weaponSold = weaponSold + 1 WHERE weaponId = ?');
            $query->bind_param('s', $weaponId);
            $query->execute();

            $query = $conn->prepare('UPDATE mspayment SET paymentUsed = paymentUsed + 1 WHERE paymentId = ?');
            $query->bind_param('s', $paymentId);
            $query->execute();

            $query = $conn->prepare('UPDATE msuser SET credit = credit - ? WHERE userId = ?');
            $query->bind_param('is', $subtotal, $user['userId']);
            $query->execute();

            $query = $conn->prepare('SELECT amount FROM armory WHERE Userid = ? AND weaponId = ?');
            $query->bind_param('ss', $user['userId'], $weaponId);
            $query->execute();
            $owned = $query->get_result()->fetch_assoc();

            if ($owned) {
                $query = $conn->prepare('UPDATE armory SET amount = amount + 1 WHERE Userid = ? AND weaponId = ?');
                $query->bind_param('ss', $user['userId'], $weaponId);
            } else {
                $query = $conn->prepare('INSERT INTO armory (Userid, weaponId, amount) VALUES (?, ?, 1)');
                $query->bind_param('ss', $user['userId'], $weaponId);
            }
            $query->execute();

            $conn->commit();
            refresh_user_cookie($conn, $user['userId']);
            header('Location: marketplace.php');
            exit;
        } catch (Throwable $error) {
            $conn->rollback();
            $errors[] = 'Purchase failed. Please try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/checkout.css" />
    <title>Severos - Checkout</title>
  </head>
  <body>
    <?php render_nav('member'); ?>
    <main>
      <header>
        <h1>Receipt</h1>
      </header>
      
      <div class="container">
        <a href="weapon_detail.php?id=<?= e($weapon['weaponId']) ?>" class="back">&larr; Back to Detail</a>
        <form class="receipt" method="POST">
          <input type="hidden" name="weapon_id" value="<?= e($weapon['weaponId']) ?>" />
          <div class="total">
            <div class="item">
              <div class="name">
                <p><?= e($weapon['weaponId']) ?></p>
                <h3><?= e($weapon['weaponName']) ?></h3>
              </div>
              <p>Qty: 1</p>
              <p><?= money($weapon['weaponPrice']) ?></p>
            </div>
            <hr>
            <div class="subtotal">
              <p>Subtotal: </p>
              <p><?= money($weapon['weaponPrice']) ?></p>
            </div>
          </div>
          <div class="payment">
            <h3>Payment Method</h3>
            <?php if ($errors): ?>
              <p style="color: #ffb4b4;"><?= e(implode(' ', $errors)) ?></p>
            <?php endif; ?>
            <div class="methods">
              <?php foreach ($payments as $payment): ?>
                <label>
                  <input type="radio" name="payment_id" value="<?= e($payment['paymentId']) ?>" />
                  <?= e($payment['paymentType']) ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
          <button>Confirm Purchase</button>
        </form>
      </div>
    </main>
    <footer>
      <p>&copy; 2025 Severos</p>
    </footer>
  </body>
</html>
