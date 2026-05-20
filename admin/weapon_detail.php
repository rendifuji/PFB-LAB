<?php

require_once __DIR__ . '/../aunth/functions.php';

$admin = require_role('admin');
$weaponId = $_GET['id'] ?? '';
$weapon = get_weapon($conn, $weaponId);

if (!$weapon) {
    header('Location: weapon_list.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/detail.css" />
    <title>Severos - Weapon Detail</title>
  </head>
  <body>
    <?php render_nav('admin'); ?>
    <main>
      <a class="back" href="weapon_list.php">&larr; Back to Weapon List</a>
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
              <p>Damage: <span><?= e($weapon['weaponDamage']) ?></span></p>
              <p>Fire Rate: <span><?= e($weapon['weaponFireRate']) ?></span></p>
              <p>Magazine Size: <span><?= e($weapon['weaponMagazineSize']) ?></span></p>
              <p>Recoil: <span><?= e($weapon['weaponRecoil']) ?></span></p>
            </div>
            <div class="sales">
              <h3>Weapon Sales</h3>
              <p>Sold: <span><?= e($weapon['weaponSold']) ?></span></p>
              <p>Stock: <span><?= e($weapon['weaponStock']) ?></span></p>
              <p class="price">Price: <?= money($weapon['weaponPrice']) ?></p>
            </div>
          </div>
          <a class="primary" href="update_weapon.php?id=<?= e($weapon['weaponId']) ?>">Update Weapon </a>
        </div>
      </div>
    </main>
    <footer>
      <p>&copy; 2025 Severos</p>
    </footer>
  </body>
</html>
