<?php

require_once __DIR__ . '/../includes/functions.php';

$user = require_role('member');
$search = trim($_GET['search'] ?? '');
$typeIds = $_GET['weapon_type'] ?? [];
$rarities = $_GET['weapon_rarity'] ?? [];
$types = weapon_type_options($conn);

$sql = 'SELECT w.*, wt.weaponTypeName, a.amount
        FROM armory a
        JOIN msweapon w ON w.weaponId = a.weaponId
        JOIN msweapontype wt ON wt.weaponTypeId = w.weaponTypeId
        WHERE a.Userid = ? AND a.amount > 0';
$params = [$user['userId']];
$bindTypes = 's';

if ($search !== '') {
    $sql .= ' AND w.weaponName LIKE ?';
    $params[] = '%' . $search . '%';
    $bindTypes .= 's';
}

if ($typeIds) {
    $sql .= ' AND w.weaponTypeId IN (' . implode(',', array_fill(0, count($typeIds), '?')) . ')';
    foreach ($typeIds as $typeId) {
        $params[] = $typeId;
        $bindTypes .= 's';
    }
}

if ($rarities) {
    $sql .= ' AND w.weaponRarity IN (' . implode(',', array_fill(0, count($rarities), '?')) . ')';
    foreach ($rarities as $rarity) {
        $params[] = $rarity;
        $bindTypes .= 's';
    }
}

$sql .= ' ORDER BY w.weaponName';
$query = $conn->prepare($sql);
$query->bind_param($bindTypes, ...$params);
$query->execute();
$weapons = $query -> get_result();
$rarityOptions = ['Common', 'Uncommon', 'Rare', 'Epic', 'Legendary'];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/marketplace.css" />
    <title>Severos - Arsenal</title>
  </head>
  <body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    <main>
      <header>
        <h1>Arsenal</h1>
        <p>This arsenal defines you. Every weapon whispers your lethal intent.</p>
      </header>
      <div class="marketplace">
        <div class="left">
          <form method="GET">
            <input type="text" name="search" placeholder="Search arsenal..." value="<?= e($search) ?>" />
          </form>
          <hr />
          <div class="weapon-list">
            <?php if ($weapons -> num_rows === 0): ?>
              <p>No weapon found in your arsenal.</p>
            <?php endif; ?>
            <?php while ($weapon = $weapons -> fetch_assoc()): ?>
              <article class="weapon-card" onclick="window.location.href='arsenal_detail.php?id=<?= e($weapon['weaponId']) ?>'">
                <h5><?= e($weapon['weaponName']) ?></h5>
                <p>Rarity: <?= e(strtoupper($weapon['weaponRarity'])) ?></p>
                <p>Type: <?= e($weapon['weaponTypeName']) ?></p>
                <p>Price: <?= money($weapon['weaponPrice']) ?></p>
              </article>
            <?php endwhile; ?>
          </div>
        </div>
        <aside class="filters">
          <h3>Filters</h3>
          <form action="" method="GET">
            <input type="hidden" name="search" value="<?= e($search) ?>" />
            <div class="weapon-types">
              <h4>Weapon Type</h4>
              <div>
                <?php foreach ($types as $type): ?>
                  <label>
                    <input type="checkbox" name="weapon_type[]" value="<?= e($type['weaponTypeId']) ?>" <?= in_array($type['weaponTypeId'], $typeIds, true) ? 'checked' : '' ?> />
                    <?= e($type['weaponTypeName']) ?>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="weapon-rarity">
              <h4>Rarity</h4>
              <div>
                <?php foreach ($rarityOptions as $rarity): ?>
                  <label>
                    <input type="checkbox" name="weapon_rarity[]" value="<?= e($rarity) ?>" <?= in_array($rarity, $rarities, true) ? 'checked' : '' ?> />
                    <?= e($rarity) ?>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <button class="primary">Apply Filters</button>
          </form>
        </aside>
      </div>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
  </body>
</html>
