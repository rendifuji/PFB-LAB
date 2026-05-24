-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2026 at 08:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `severos_blackmarket`
--

-- --------------------------------------------------------

--
-- Table structure for table `armory`
--

CREATE TABLE `armory` (
  `Userid` char(5) NOT NULL,
  `weaponId` char(5) NOT NULL,
  `amount` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `armory`
--

INSERT INTO `armory` (`Userid`, `weaponId`, `amount`) VALUES
('US003', 'WP006', 1),
('US003', 'WP012', 1),
('US004', 'WP014', 1),
('US006', 'WP012', 2),
('US006', 'WP002', 1),
('US006', 'WP008', 1),
('US004', 'WP005', 1),
('US005', 'WP018', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mspayment`
--

CREATE TABLE `mspayment` (
  `paymentId` char(5) NOT NULL,
  `paymentType` varchar(255) NOT NULL,
  `paymentUsed` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mspayment`
--

INSERT INTO `mspayment` (`paymentId`, `paymentType`, `paymentUsed`) VALUES
('PY001', 'Debit Card', 2),
('PY002', 'Paypal', 1),
('PY003', 'Cryptocurrency', 1),
('PY004', 'Gopay', 1),
('PY005', 'ETH', 4);

-- --------------------------------------------------------

--
-- Table structure for table `msuser`
--

CREATE TABLE `msuser` (
  `userId` char(5) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `credit` int(10) NOT NULL,
  `role` char(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `msuser`
--

INSERT INTO `msuser` (`userId`, `username`, `password`, `email`, `credit`, `role`) VALUES
('US001', 'Torazonnn', '$2y$10$sorYuff4i70aYQqulQPCLOZxSjkdqWuXk23TqGhtnvvKZMe.rjlIy', 'Torazon@gmail.com', 0, 'Admin'),
('US002', 'Yohannes', '$2y$10$xch/Q9KFo8u39fSgyd8RT.4lP2aCIDatI2zYLORgO6VRb0SZ2SO22', 'yohannes@gmail.com', 600, 'User'),
('US003', 'Nicholus', '$2y$10$OFkwO/.2ZPFPJ7Xq0E5J4Oauv.xiIDFZpo3UrUNUluuAFW2XSXqGC', 'nicholus@gmail.com', 861, 'User'),
('US004', 'Tracey Odelia', '$2y$10$c8Ex1zFaSnihecXY1A9IseUv8pMsZ10YLiKND/5Lxc8irqvIU6eai', 'tracey112@gmail.com', 8, 'User'),
('US005', 'RenBoxxzz', '$2y$10$Ndjr6mLUg/O9kIkqtcbH0ecW6cO8ENt/d024JsfJ4ZsIEQYgNjZ2e', 'rendiwikarta@gmail.com', 42, 'User'),
('US006', 'WilliamVWS', '$2y$10$nnie8H0SPXsEJ68LiV646OCW.2bJIjz378xT/bTTNqr2KT1x/jq02', 'Vincent@gmail.com', 9612, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `msweapon`
--

CREATE TABLE `msweapon` (
  `weaponId` char(5) NOT NULL,
  `weaponTypeId` char(5) NOT NULL,
  `weaponPrice` int(10) NOT NULL,
  `weaponStock` int(10) NOT NULL,
  `weaponName` varchar(255) NOT NULL,
  `weaponRarity` varchar(255) NOT NULL,
  `weaponDamage` int(10) NOT NULL,
  `weaponFireRate` int(10) NOT NULL,
  `weaponMagazineSize` int(10) NOT NULL,
  `weaponRecoil` int(10) NOT NULL,
  `weaponTrait` varchar(255) NOT NULL,
  `weaponSold` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `msweapon`
--

INSERT INTO `msweapon` (`weaponId`, `weaponTypeId`, `weaponPrice`, `weaponStock`, `weaponName`, `weaponRarity`, `weaponDamage`, `weaponFireRate`, `weaponMagazineSize`, `weaponRecoil`, `weaponTrait`, `weaponSold`) VALUES
('WP001', 'WT001', 66, 1000, 'Desert Eagle \"Saint Edge\"', 'Epic', 78, 240, 7, 20, 'Massive stopping power packed into a semi-automatic sidearm. The Desert Eagle delivers devastating high-caliber shots capable of piercing light cover and staggering enemies on impact. Its extreme recoil and limited magazine reward precision, patience, and', 0),
('WP002', 'WT002', 142, 63, 'White Fang - 465 \"Artic Howl\"', 'Epic', 92, 210, 8, 78, 'The White Fang-465 “Arctic Howl” is a precision-engineered heavy pistol designed for ruthless efficiency in freezing combat zones. Its reinforced slide and cryo-coated barrel provide exceptional stability under pressure, delivering brutal stopping power w', 1),
('WP003', 'WT002', 87900, 75, 'AR - 70/223 \"Urban Specter\"', 'Rare', 34, 758, 30, 42, 'The AR-70/223 “Urban Specter” is a modular assault rifle optimized for rapid-response urban warfare. Combining manageable recoil with a high cyclic rate, it excels in close-to-mid range engagements where mobility and target acquisition are critical. Its l', 0),
('WP004', 'WT002', 96780, 100, 'L85A2 \"Divine Order\"', 'Epic', 41, 690, 30, 36, 'The L85A2 “Divine Order” is a disciplined bullpup assault rifle engineered for precision and battlefield control. Its compact frame conceals exceptional range performance and steady shot placement, making it ideal for operators who value tactical consiste', 0),
('WP005', 'WT002', 11, 199, 'G11 \"Caseless Edge', 'Epic', 39, 980, 45, 28, 'The G11 “Caseless Edge” is an experimental assault platform built around advanced caseless ammunition technology, enabling unmatched burst efficiency and near-instant target suppression. Its hyper-fast firing cycle overwhelms opponents before recoil fully', 1),
('WP006', 'WT002', 100, 499, 'AK - 15 \"Guardian\"', 'Epic', 52, 640, 30, 67, 'The AK-15 “Guardian” is a hard-hitting battle rifle designed for frontline dominance and relentless reliability. Chambered for a larger-caliber cartridge, it delivers devastating impact per shot while maintaining the rugged durability expected from the AK', 1),
('WP007', 'WT002', 45, 213, 'VX - Raptor \"Sky Hunter\"', 'Epic', 44, 820, 36, 31, 'The VX-Raptor “Sky Hunter” is a futuristic precision assault platform engineered for rapid aerial-response operations and high-speed engagements. Featuring an advanced recoil compensation system and lightweight composite chassis, it maintains deadly accur', 0),
('WP008', 'WT003', 168, 11, 'MG42 \"Destroyer Mark II\"', 'Legendary', 46, 1200, 91, 39, 'The MG42 “Destroyer Mark II” is a terrifying high-capacity machine gun built for overwhelming suppressive fire and battlefield domination. Its extreme rate of fire can shred defensive positions within seconds, turning sustained engagements into chaos for ', 1),
('WP009', 'WT003', 89, 330, 'MG36 \"Suppressor Vanguard\"', 'Epic', 40, 780, 60, 48, 'The MG36 “Suppressor Vanguard” is a versatile support weapon engineered to bridge the gap between an assault rifle and a full machine gun. Designed for mobile suppression tactics, it offers controllable automatic fire with superior handling compared to he', 0),
('WP010', 'WT003', 95, 30, 'MK - LMG \"Storm Breaker\"', 'Legendary', 49, 920, 120, 73, 'The MK-LMG “Storm Breaker” is a heavy suppression platform built to dominate prolonged firefights through relentless sustained fire. Engineered with an advanced recoil buffering assembly and reinforced barrel housing, it maintains impressive firing stabil', 0),
('WP011', 'WT004', 104, 55, 'Eye of Horus + Shield \"Guardians Haven\"', 'Epic', 58, 320, 24, 22, 'The Eye of Horus + Shield “Guardian’s Haven” is a sacred defensive weapon system combining precision energy projection with an ancient reinforced shield core. Designed for frontline protectors and tactical anchors, it allows the wielder to absorb incoming', 0),
('WP012', 'WT004', 39, 1097, 'Choir Shotgun \"Harmony Breaker\"', 'Rare', 96, 110, 8, 89, 'The Choir Shotgun “Harmony Breaker” is a devastating close-quarters weapon forged to unleash overwhelming force in confined combat zones. Its multi-chamber firing mechanism produces a thunderous spread capable of tearing through armored targets at short r', 3),
('WP013', 'WT004', 52, 1233, 'SPAS - 12 \"Dust Breaker\"', 'Rare', 91, 140, 8, 82, 'The SPAS-12 “Dust Breaker” is a rugged combat shotgun built for relentless close-range dominance in hostile environments. Featuring a dual-mode firing system and reinforced tactical frame, it delivers brutal stopping power while maintaining dependable per', 0),
('WP014', 'WT005', 81, 2099, 'Hellfire SMG \"Red Comet\"', 'Rare', 27, 1150, 40, 58, 'The Hellfire SMG “Red Comet” is an ultra-high-speed submachine gun engineered for aggressive assault tactics and rapid target elimination. Its compact frame and blazing cyclic rate allow operators to overwhelm enemies in seconds, turning close-quarters co', 1),
('WP015', 'WT005', 21, 989, 'Nano Blazer MP7 \"Tech Blade\"', 'Rare', 25, 1000, 50, 34, 'The Nano Blazer MP7 “Tech Blade” is a next-generation personal defense weapon built for extreme mobility and surgical close-range engagements. Enhanced with nano-alloy internals and a smart recoil stabilization system, it delivers blistering fire rates wi', 0),
('WP016', 'WT005', 29, 300, 'Shadow-Wraith \"Silent Dagger\"', 'Rare', 29, 870, 36, 26, 'The Shadow-Wraith “Silent Dagger” is a covert-operations submachine gun engineered for stealth eliminations and rapid infiltration missions. Equipped with an integrated suppression system and low-signature firing mechanism, it excels at silent target remo', 0),
('WP017', 'WT006', 52, 10, 'Seraphim DMR \"Sky-Harbinger\"', 'Legendary', 130, 320, 10, 100, 'The Seraphim DMR “Sky-Harbinger” is a sacred precision rifle crafted for elite marksmen who dominate the battlefield from afar. Combining high-caliber impact with advanced stabilization technology, it delivers devastating semi-automatic fire with remarkab', 0),
('WP018', 'WT006', 8, 99, 'Quantum AWM \"Silent Resolve\"', 'Legendary', 110, 100, 6, 90, 'The Quantum AWM “Silent Resolve” is an elite anti-material sniper rifle engineered for absolute precision and silent lethality. Utilizing quantum-stabilized targeting systems and a heavily suppressed barrel assembly, it can eliminate high-value targets fr', 1),
('WP019', 'WT006', 24, 5, '.50 BMG \"Longroach Titan\"', 'Legendary', 140, 1000, 7, 99, 'The .50 BMG “Longroach Titan” is a colossal long-range anti-material rifle built to dominate fortified positions and heavily armored targets. Engineered with a reinforced macro-frame and massive recoil absorption system, it fires devastating high-caliber ', 0),
('WP020', 'WT006', 14, 55, 'Titan-Sniper \"Eagle Eye\"', 'Legendary', 190, 900, 9, 10, 'The Titan-Sniper “Eagle Eye” is a high-precision tactical sniper platform designed for reconnaissance specialists and elite long-range operators. Built with an advanced targeting interface and ultra-stable barrel system, it delivers consistent one-shot le', 0);

-- --------------------------------------------------------

--
-- Table structure for table `msweapontype`
--

CREATE TABLE `msweapontype` (
  `weaponTypeId` char(5) NOT NULL,
  `weaponTypeName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `msweapontype`
--

INSERT INTO `msweapontype` (`weaponTypeId`, `weaponTypeName`) VALUES
('WT001', 'Handgun'),
('WT002', 'Assault Rifle'),
('WT003', 'Machine Gun'),
('WT004', 'Shotgun'),
('WT005', 'Submachine Gun'),
('WT006', 'Sniper Rifle'),
('WT007', 'Grenade Launcher'),
('WT008', 'Rocket Launcher'),
('WT009', 'Railgun');

-- --------------------------------------------------------

--
-- Table structure for table `transactiondetail`
--

CREATE TABLE `transactiondetail` (
  `transactionId` char(5) NOT NULL,
  `userid` char(5) NOT NULL,
  `paymentId` char(5) NOT NULL,
  `weaponId` char(5) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `quantity` int(10) NOT NULL,
  `subtotal` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactiondetail`
--

INSERT INTO `transactiondetail` (`transactionId`, `userid`, `paymentId`, `weaponId`, `createdAt`, `quantity`, `subtotal`) VALUES
('TR001', 'US003', 'PY005', 'WP006', '2026-04-27 06:03:19', 1, 100),
('TR002', 'US003', 'PY005', 'WP012', '2026-04-30 09:03:40', 1, 39),
('TR003', 'US004', 'PY001', 'WP014', '2026-05-24 06:08:15', 1, 81),
('TR004', 'US006', 'PY001', 'WP012', '2026-05-24 06:08:59', 1, 39),
('TR005', 'US006', 'PY005', 'WP012', '2026-05-24 06:09:05', 1, 39),
('TR006', 'US006', 'PY003', 'WP002', '2026-05-24 06:09:12', 1, 142),
('TR007', 'US006', 'PY002', 'WP008', '2026-05-24 06:18:21', 1, 168),
('TR008', 'US004', 'PY004', 'WP005', '2026-05-24 06:19:00', 1, 11),
('TR009', 'US005', 'PY005', 'WP018', '2026-06-01 06:19:20', 1, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `armory`
--
ALTER TABLE `armory`
  ADD KEY `fk_userId` (`Userid`),
  ADD KEY `fk_weaponId` (`weaponId`);

--
-- Indexes for table `mspayment`
--
ALTER TABLE `mspayment`
  ADD PRIMARY KEY (`paymentId`);

--
-- Indexes for table `msuser`
--
ALTER TABLE `msuser`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `msweapon`
--
ALTER TABLE `msweapon`
  ADD PRIMARY KEY (`weaponId`),
  ADD KEY `fk_weaponTypeId` (`weaponTypeId`);

--
-- Indexes for table `msweapontype`
--
ALTER TABLE `msweapontype`
  ADD PRIMARY KEY (`weaponTypeId`);

--
-- Indexes for table `transactiondetail`
--
ALTER TABLE `transactiondetail`
  ADD PRIMARY KEY (`transactionId`),
  ADD KEY `FK_TransactionDetail_userId` (`userid`),
  ADD KEY `FK_TransactionDetail_paymentId` (`paymentId`),
  ADD KEY `FK_TransactionDetail_weaponId` (`weaponId`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `armory`
--
ALTER TABLE `armory`
  ADD CONSTRAINT `fk_userId` FOREIGN KEY (`Userid`) REFERENCES `msuser` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_weaponId` FOREIGN KEY (`weaponId`) REFERENCES `msweapon` (`weaponId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `msweapon`
--
ALTER TABLE `msweapon`
  ADD CONSTRAINT `fk_weaponTypeId` FOREIGN KEY (`weaponTypeId`) REFERENCES `msweapontype` (`weaponTypeId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactiondetail`
--
ALTER TABLE `transactiondetail`
  ADD CONSTRAINT `FK_TransactionDetail_paymentId` FOREIGN KEY (`paymentId`) REFERENCES `mspayment` (`paymentId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_TransactionDetail_userId` FOREIGN KEY (`userid`) REFERENCES `msuser` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_TransactionDetail_weaponId` FOREIGN KEY (`weaponId`) REFERENCES `msweapon` (`weaponId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
