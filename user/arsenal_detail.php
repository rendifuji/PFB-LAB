<?php

require_once __DIR__ . '/../aunth/functions.php';

$user = require_role('member');
$weaponId = $_GET['id'] ?? ($_POST['weapon_id'] ?? '');
$errors = [];

$query = $conn->prepare(
    'SELECT w.*, wt.weaponTypeName, a.amount
     FROM armory a
     JOIN msweapon w ON w.weaponId = a.weaponId
     JOIN msweapontype wt ON wt.weaponTypeId = w.weaponTypeId
     WHERE a.Userid = ? AND a.weaponId = ? AND a.amount > 0'
);
$query->bind_param('ss', $user['userId'], $weaponId);
$query->execute();
$weapon = $query -> get_result() -> fetch_assoc();

if (!$weapon) {
    header('Location: arsenal.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = (int) ($_POST['quantity'] ?? 0);
    if ($quantity <= 0 || $quantity > (int) $weapon['amount']) {
        $errors[] = 'Sell quantity is invalid.';
    }

    if (!$errors) {
        $compensation = (int) ($weapon['weaponPrice'] * 0.5 * $quantity);
        $conn ->begin_transaction();
        try {
            if ($quantity === (int) $weapon['amount']) {
                $query = $conn->prepare('DELETE FROM armory WHERE Userid = ? AND weaponId = ?');
                $query->bind_param('ss', $user['userId'], $weaponId);
            } else {
                $query = $conn->prepare('UPDATE armory SET amount = amount - ? WHERE Userid = ? AND weaponId = ?');
                $query->bind_param('iss', $quantity, $user['userId'], $weaponId);
            }
            $query->execute();

            $query = $conn->prepare('UPDATE msuser SET credit = credit + ? WHERE userId = ?');
            $query->bind_param('is', $compensation, $user['userId']);
            $query->execute();

            $conn->commit();
            refresh_user_cookie($conn, $user['userId']);
            header('Location: arsenal.php');
            exit;
        } catch (Throwable $error) {
            $conn->rollback();
            $errors[] = 'Sell failed. Please try again.';
        }
    }
}

$sellOptions = array_values(array_unique(array_filter([1, 5, 10, (int) $weapon['amount']], fn($qty) => $qty <= (int) $weapon['amount'])));
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/detail.css" />
    <title>Severos - Arsenal Detail</title>
  </head>
  <body>
    <?php render_nav('member'); ?>
    <main>
      <a class="back" href="arsenal.php">&larr; Back to Arsenal</a>
      <div class="weapon">
        <div class="weapon-desc">
          <h1><?= e($weapon['weaponName']) ?></h1>
          <p><strong>Type:</strong> <?= e($weapon['weaponTypeName']) ?></p>
          <p><strong>Rarity:</strong> <?= e(strtoupper($weapon['weaponRarity'])) ?></p>
        </div>
        <div class="weapon-details">
          <div class="trait">
            <h3>Weapon Trait</h3>
            <p><?= e($weapon['weaponTrait']) ?></p>
          </div>
          <div class="weapon-stats">
            <div class="stats">
              <h3>Weapon Stats</h3>
              <p><span>Damage:</span> <?= e($weapon['weaponDamage']) ?></p>
              <p><span>Fire Rate:</span> <?= e($weapon['weaponFireRate']) ?></p>
              <p><span>Magazine Size:</span> <?= e($weapon['weaponMagazineSize']) ?></p>
              <p><span>Recoil:</span> <?= e($weapon['weaponRecoil']) ?></p>
            </div>
          </div>

          <form class="sell" method="POST">
            <input type="hidden" name="weapon_id" value="<?= e($weapon['weaponId']) ?>" />
            <p><span>Amount:</span> <?= e($weapon['amount']) ?></p>
            <?php if ($errors): ?>
              <p style="color: #ffb4b4;"><?= e(implode(' ', $errors)) ?></p>
            <?php endif; ?>
            <div class="options">
              <?php foreach ($sellOptions as $option): ?>
                <label class="option">
                  <input type="radio" name="quantity" value="<?= e($option) ?>" />
                  <p><?= $option === (int) $weapon['amount'] ? 'Sell All' : 'Sell ' . e($option) ?></p>
                  <p>+<?= money($weapon['weaponPrice'] * 0.5 * $option) ?></p>
                </label>
              <?php endforeach; ?>
            </div>
            <button class="primary danger"> Sell Weapon</button>
            <p class="disclaimer">* You will receive 50% of the weapon's price upon selling.</p>
          </form>
        </div>
      </div>
    </main>
    <footer>
      <p>&copy; 2025 Severos</p>
    </footer>
  </body>
</html>
