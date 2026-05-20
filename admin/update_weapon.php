<?php

require_once __DIR__ . '/../aunth/functions.php';

$admin = require_role('admin');
$weaponId = $_GET['id'] ?? ($_POST['weaponId'] ?? '');
$weapon = get_weapon($conn, $weaponId);
$types = weapon_type_options($conn);
$errors = [];

if (!$weapon) {
    header('Location: weapon_list.php');
    exit;
}

$data = $weapon;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['weaponName', 'weaponTypeId', 'weaponRarity', 'weaponTrait', 'weaponDamage', 'weaponFireRate', 'weaponMagazineSize', 'weaponRecoil', 'weaponStock', 'weaponPrice'] as $field) {
        $data[$field] = $_POST[$field] ?? $data[$field];
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
        $query = $conn->prepare(
            'UPDATE msweapon
             SET weaponTypeId = ?, weaponPrice = ?, weaponStock = ?, weaponName = ?, weaponRarity = ?, weaponDamage = ?, weaponFireRate = ?, weaponMagazineSize = ?, weaponRecoil = ?, weaponTrait = ?
             WHERE weaponId = ?'
        );
        $query->bind_param(
            'siissiiiiss',
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
            $weaponId
        );
        $query->execute();
        header('Location: weapon_detail.php?id=' . urlencode($weaponId));
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
    <title>Severos - Update Weapon</title>
  </head>
  <body>
    <?php render_nav('admin'); ?>
    <main>
      <a class="back" href="weapon_detail.php?id=<?= e($weaponId) ?>">&larr; Back to Weapon Detail</a>
      <?php if ($errors): ?>
        <p style="color: #ffb4b4;"><?= e(implode(' ', $errors)) ?></p>
      <?php endif; ?>
      <form class="weapon" method="POST">
        <input type="hidden" name="weaponId" value="<?= e($weaponId) ?>" />
        <div class="weapon-desc">
          <input class="name-input" name="weaponName" type="text" value="<?= e($data['weaponName']) ?>" />

          <div class="field">
            <label for="weapon-type"><strong>Type:</strong></label>
            <select id="weapon-type" name="weaponTypeId">
              <?php foreach ($types as $type): ?>
                <option value="<?= e($type['weaponTypeId']) ?>" <?= $data['weaponTypeId'] === $type['weaponTypeId'] ? 'selected' : '' ?>><?= e($type['weaponTypeName']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="field">
            <label for="weapon-rarity"><strong>Rarity:</strong></label>
            <select id="weapon-rarity" name="weaponRarity">
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
              <p>Sold: <span><?= e($data['weaponSold']) ?></span></p>
              <p>Stock: <input type="number" name="weaponStock" value="<?= e($data['weaponStock']) ?>" /></p>
              <p class="price">
                Price: <span>$</span> <input type="number" name="weaponPrice" value="<?= e($data['weaponPrice']) ?>" />
              </p>
            </div>
          </div>
          <button class="primary">Update Weapon</button>
        </div>
      </form>
    </main>
    <footer>
      <p>&copy; 2025 Severos</p>
    </footer>
  </body>
</html>
