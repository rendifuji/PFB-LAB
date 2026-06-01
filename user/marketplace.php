<?php

require_once __DIR__ . '/../aunth/functions.php';

$user = require_role('member');
$search = trim($_GET['search'] ?? '');
$typeIds = $_GET['weapon_type'] ?? [];
$maxPrice = isset($_GET['price_range']) ? (int) $_GET['price_range'] : 10000;
$types = weapon_type_options($conn);

$sql = 'SELECT w.*, wt.weaponTypeName FROM msweapon w JOIN msweapontype wt ON wt.weaponTypeId = w.weaponTypeId WHERE w.weaponStock > 0';
$params = [];
$bindTypes = '';

if ($search !== '') {
    $sql .= ' AND w.weaponName LIKE ?';
    $params[] = '%' . $search . '%';
    $bindTypes .= 's';
}

if ($typeIds) {
    $placeholders = implode(',', array_fill(0, count($typeIds), '?'));
    $sql .= " AND w.weaponTypeId IN ($placeholders)";
    foreach ($typeIds as $typeId) {
        $params[] = $typeId;
        $bindTypes .= 's';
    }
}

if (isset($_GET['apply'])) {
    $sql .= ' AND w.weaponPrice <= ?';
    $params[] = $maxPrice;
    $bindTypes .= 'i';
}

$sql .= ' ORDER BY w.weaponName';
$query = $conn -> prepare($sql);
if ($params) {
    $query -> bind_param($bindTypes, ...$params);
}
$query -> execute();
$weapons = $query -> get_result();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/marketplace.css" />
    <title>Severos - Marketplace</title>
  </head>
  <body>
    <?php render_nav('member'); ?>
    <main>
      <header>
        <h1>Marketplace</h1>
        <p>Only the strongest survive. Choose your weapons, enforce your dominance.</p>
      </header>
      <div class="marketplace">
        <div class="left">
          <form method="GET">
            <input type="text" name="search" placeholder="Search weapons..." value="<?= e($search) ?>" />
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

            <div class="price">
              <h4>Price Range</h4>
              <input type="range" id="price-range" name="price_range" min="50" max="10000" value="<?= e($maxPrice) ?>" />
              <p>Min: $50 - Max: $<?= e($maxPrice) ?></p>
            </div>

            <button class="primary" name="apply" value="1">Apply Filters</button>
          </form>
        </aside>
      </div>
    </main>
    <footer>
      <p>&copy; 2025 Severos</p>
    </footer>
  </body>
</html>
