<?php

require_once __DIR__ . '/../aunth/functions.php';

$admin = require_role('admin');
$types = weapon_type_options($conn);
$errors = [];

$data = [
    'weaponName' => '',
    'weaponTypeId' => '',
    'weaponRarity' => '',
    'weaponTrait' => '',
    'weaponDamage' => 0,
    'weaponFireRate' => 0,
    'weaponMagazineSize' => 0,
    'weaponRecoil' => 0,
    'weaponStock' => 0,
    'weaponPrice' => 0,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $key => $value) {
        $data[$key] = $_POST[$key] ?? $value;
    }

    if (trim($data['weaponName']) === '' || $data['weaponTypeId'] === '' || $data['weaponRarity'] === '' || trim($data['weaponTrait']) === '') {
        $errors[] = 'All weapon identity fields must not be empty.';
    }

    foreach (['weaponDamage', 'weaponFireRate', 'weaponMagazineSize', 'weaponRecoil', 'weaponStock', 'weaponPrice'] as $numberField) {
        if ((int) $data[$numberField] < 0) {
            $errors[] = 'Number fields must not be negative.';
            break;
        }
    }

    if (!$errors) {
        $weaponId = next_id($conn, 'msweapon', 'weaponId', 'WP');
        $sold = 0;
        $query = $conn->prepare(
            'INSERT INTO msweapon (weaponId, weaponTypeId, weaponPrice, weaponStock, weaponName, weaponRarity, weaponDamage, weaponFireRate, weaponMagazineSize, weaponRecoil, weaponTrait, weaponSold)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $query->bind_param(
            'ssiissiiiisi',
            $weaponId,
            $data['weaponTypeId'],
            $data['weaponPrice'],
            $data['weaponStock'],
            $data['weaponName'],
            $data['weaponRarity'],
            $data['weaponDamage'],
            $data['weaponFireRate'],
            $data['weaponMagazineSize'],
            $data['weaponRecoil'],
            $data['weaponTrait'],
            $sold
        );
        $query->execute();
        header('Location: weapon_list.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/detail.css" />
    <link rel="stylesheet" href="../css/weapon_form.css" />
    <title>Severos - Add Weapon</title>
  </head>
  <body>
    <?php render_nav('admin'); ?>
    <main>
      <a class="back" href="weapon_list.php">&larr; Back to Weapon List</a>
      <?php if ($errors): ?>
        <p style="color: #ffb4b4;"><?= e(implode(' ', $errors)) ?></p>
      <?php endif; ?>
      <form class="weapon" method="POST">
        <div class="weapon-desc">
          <input class="name-input" type="text" name="weaponName" placeholder="Weapon Name" value="<?= e($data['weaponName']) ?>" />

          <div class="field">
            <label for="weapon-type"><strong>Type:</strong></label>
            <select id="weapon-type" name="weaponTypeId">
              <option value="">Select Type</option>
              <?php foreach ($types as $type): ?>
                <option value="<?= e($type['weaponTypeId']) ?>" <?= $data['weaponTypeId'] === $type['weaponTypeId'] ? 'selected' : '' ?>><?= e($type['weaponTypeName']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="field">
            <label for="weapon-rarity"><strong>Rarity:</strong></label>
            <select id="weapon-rarity" name="weaponRarity">
              <option value="">Select Rarity</option>
              <?php foreach (['Rare', 'Epic', 'Legendary'] as $rarity): ?>
                <option value="<?= e($rarity) ?>" <?= $data['weaponRarity'] === $rarity ? 'selected' : '' ?>><?= e(strtoupper($rarity)) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="weapon-details">
          <div class="trait">
            <h3>Weapon Trait</h3>
            <textarea name="weaponTrait" rows="4"><?= e($data['weaponTrait']) ?></textarea>
          </div>
          <div class="weapon-stats">
            <div class="stats">
              <h3>Weapon Stats</h3>
              <p>Damage: <input type="number" name="weaponDamage" value="<?= e($data['weaponDamage']) ?>" /></p>
              <p>Fire Rate: <input type="number" name="weaponFireRate" value="<?= e($data['weaponFireRate']) ?>" /></p>
              <p>Magazine Size: <input type="number" name="weaponMagazineSize" value="<?= e($data['weaponMagazineSize']) ?>" /></p>
              <p>Recoil: <input type="number" name="weaponRecoil" value="<?= e($data['weaponRecoil']) ?>" /></p>
            </div>
            <div class="sales">
              <h3>Weapon Sales</h3>
              <p>Sold: <span>0</span></p>
              <p>Stock: <input type="number" name="weaponStock" value="<?= e($data['weaponStock']) ?>" /></p>
              <p class="price">
                Price: <span>$</span> <input type="number" name="weaponPrice" value="<?= e($data['weaponPrice']) ?>" />
              </p>
            </div>
          </div>
          <button class="primary">Add Weapon</button>
        </div>
      </form>
    </main>
    <footer>
      <p>&copy; 2025 Severos</p>
    </footer>
  </body>
</html>
