<?php

require_once __DIR__ . '/../includes/functions.php';

$admin = require_role('admin');
$search = trim($_GET['search'] ?? '');
$typeId = $_GET['weapon_type'] ?? '';
$rarity = $_GET['weapon_rarity'] ?? '';
$types = weapon_type_options($conn);

$sqlselect = 'SELECT w.*, wt.weaponTypeName FROM msweapon w JOIN msweapontype wt ON wt.weaponTypeId = w.weaponTypeId WHERE 1 = 1';
$params = [];
$bindTypes = '';

if ($search !== '') {
    $sqlselect .= ' AND w.weaponName LIKE ?';
    $params[] = '%' . $search . '%';
    $bindTypes .= 's';
}

if ($typeId !== '') {
    $sqlselect .= ' AND w.weaponTypeId = ?';
    $params[] = $typeId;
    $bindTypes .= 's';
}

if ($rarity !== '') {
    $sqlselect .= ' AND w.weaponRarity = ?';
    $params[] = $rarity;
    $bindTypes .= 's';
}

$sqlselect .= ' ORDER BY w.weaponName';
$query = $conn->prepare($sqlselect);
if ($params) {
    $query->bind_param($bindTypes, ...$params);
}
$query->execute();
$weapons = $query->get_result();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/weapon_list.css" />
    <title>Severos - Weapon List</title>
  </head>
  <body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    <main>
      <div class="marketplace">
        <form class="top" method="GET">
          <div class="filters">
            <input id="weapon-name" name="search" type="text" placeholder="Search weapons..." value="<?= e($search) ?>" />
            <select name="weapon_type" id="weapon-type">
              <option value="">Weapon Type</option>
              <?php foreach ($types as $type): ?>
                <option value="<?= e($type['weaponTypeId']) ?>" <?= $typeId === $type['weaponTypeId'] ? 'selected' : '' ?>><?= e($type['weaponTypeName']) ?></option>
              <?php endforeach; ?>
            </select>
            <select name="weapon_rarity" id="weapon-rarity">
              <option value="">Weapon Rarity</option>
              <?php foreach (['Rare', 'Epic', 'Legendary'] as $option): ?>
                <option value="<?= e($option) ?>" <?= $rarity === $option ? 'selected' : '' ?>><?= e(strtoupper($option)) ?></option>
              <?php endforeach; ?>
            </select>
            <button class="primary">Apply</button>
          </div>
          <button class="primary" type="button" onclick="window.location.href='add_weapon.php'">Add New Weapon</button>
        </form>
        <hr />
        <div class="weapon-list">
          <?php if ($weapons->num_rows === 0): ?>
            <p>No weapon found.</p>
          <?php endif; ?>
          <?php while ($weapon = $weapons->fetch_assoc()): ?>
            <article class="weapon-card" onclick="window.location.href='weapon_detail.php?id=<?= e($weapon['weaponId']) ?>'">
              <h5><?= e($weapon['weaponName']) ?></h5>
              <p>Rarity: <?= e(strtoupper($weapon['weaponRarity'])) ?></p>
              <p>Type: <?= e($weapon['weaponTypeName']) ?></p>
              <p>Price: <?= money($weapon['weaponPrice']) ?></p>
            </article>
          <?php endwhile; ?>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
  </body>
</html>
