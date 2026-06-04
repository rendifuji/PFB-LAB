<?php
$user = current_user();
$isAdmin = $user && $user['role'] === 'admin';
?>
<nav>
  <h2>Severos</h2>
  <?php if ($isAdmin): ?>
  <ul>
    <li><a href="../admin/dashboard.php">Dashboard</a></li>
    <li><a href="../admin/weapon_list.php">Weapon</a></li>
  </ul>
  <?php elseif ($user): ?>
  <ul>
    <li><a href="../user/index.php">Home</a></li>
    <li><a href="../user/marketplace.php">Marketplace</a></li>
    <li><a href="../user/arsenal.php">Arsenal</a></li>
    <li><a href="../user/transactions.php">Transaction</a></li>
  </ul>
  <?php endif; ?>
  <ul>
    <?php if ($user): ?>
    <li><?= $isAdmin ? 'Admin ' : '' ?><?= e($user['username']) ?></li>
    <li><a href="../auth/logout.php">Logout</a></li>
    <?php else: ?>
    <li><a href="../auth/login.php">Login</a></li>
    <li>|</li>
    <li><a href="../auth/register.php">Register</a></li>
    <?php endif; ?>
  </ul>
</nav>
