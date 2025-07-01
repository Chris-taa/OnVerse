-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2025 at 05:58 AM
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
-- Database: `onverse`
--

-- --------------------------------------------------------

--
-- Table structure for table `chapter_images`
--

CREATE TABLE `chapter_images` (
  `image_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `image_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chapter_images`
--

INSERT INTO `chapter_images` (`image_id`, `episode_id`, `image_url`, `image_order`) VALUES
(61, 10, 'database/karya/Magic_Academys_Genius_Blinker/Chapter_1/chapter_img_6858633f03a914.56081039.jpg', 1),
(62, 10, 'database/karya/Magic_Academys_Genius_Blinker/Chapter_1/chapter_img_6858633f03c4d1.49140465.jpg', 2),
(63, 10, 'database/karya/Magic_Academys_Genius_Blinker/Chapter_1/chapter_img_6858633f03dbb4.75786537.jpg', 3),
(64, 10, 'database/karya/Magic_Academys_Genius_Blinker/Chapter_1/chapter_img_6858633f03f152.60126107.jpg', 4),
(65, 10, 'database/karya/Magic_Academys_Genius_Blinker/Chapter_1/chapter_img_6858633f040587.25274088.jpg', 5),
(66, 10, 'database/karya/Magic_Academys_Genius_Blinker/Chapter_1/chapter_img_6858633f0421a2.06150371.jpg', 6),
(67, 10, 'database/karya/Magic_Academys_Genius_Blinker/Chapter_1/chapter_img_6858633f043572.77644151.jpg', 7),
(68, 10, 'database/karya/Magic_Academys_Genius_Blinker/Chapter_1/chapter_img_6858633f044989.33089714.jpg', 8),
(69, 10, 'database/karya/Magic_Academys_Genius_Blinker/Chapter_1/chapter_img_6858633f045926.15018131.jpg', 9),
(70, 11, 'database/karya/As_Deep_as_the_SKy/Chapter_1/chapter_img_685867b3d358f5.33904398.jpeg', 1),
(71, 11, 'database/karya/As_Deep_as_the_SKy/Chapter_1/chapter_img_685867b3d37255.34826523.jpeg', 2),
(72, 11, 'database/karya/As_Deep_as_the_SKy/Chapter_1/chapter_img_685867b3d38700.56150089.jpeg', 3),
(73, 11, 'database/karya/As_Deep_as_the_SKy/Chapter_1/chapter_img_685867b3d39bb5.77295912.jpeg', 4),
(74, 11, 'database/karya/As_Deep_as_the_SKy/Chapter_1/chapter_img_685867b3d3b075.62838269.jpeg', 5),
(75, 11, 'database/karya/As_Deep_as_the_SKy/Chapter_1/chapter_img_685867b3d3cbe6.33561899.jpeg', 6),
(76, 11, 'database/karya/As_Deep_as_the_SKy/Chapter_1/chapter_img_685867b3d3e007.82088756.jpeg', 7),
(77, 11, 'database/karya/As_Deep_as_the_SKy/Chapter_1/chapter_img_685867b3d3f532.05390294.jpeg', 8),
(78, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd788d25.58965036.jpeg', 1),
(79, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd789f77.55130155.jpeg', 2),
(80, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd78b186.28979404.jpeg', 3),
(81, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd78c5f9.78586341.jpeg', 4),
(82, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd78d818.84483488.jpeg', 5),
(83, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd78f319.05630638.jpeg', 6),
(84, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd790449.19209434.jpeg', 7),
(85, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd791357.63983576.jpeg', 8),
(86, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd7921f2.19120175.jpeg', 9),
(87, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd7c16c1.90183424.jpeg', 10),
(88, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd7c36e8.23794986.jpeg', 11),
(89, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd7c4fa9.36981324.jpeg', 12),
(90, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd7c5eb0.17104772.jpeg', 13),
(91, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd7c70b3.68936108.jpeg', 14),
(92, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd7c7f67.63722022.jpeg', 15),
(93, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd7c8fe8.78035100.jpeg', 16),
(94, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd7ca113.55033729.jpeg', 17),
(95, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd7cb034.31208339.jpeg', 18),
(96, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd7cbec1.74196087.jpeg', 19),
(97, 12, 'database/karya/Detective_Conan/Chapter_1/chapter_img_685868bd7ccd63.54060654.jpeg', 20),
(98, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d23de4.28173039.jpeg', 1),
(99, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d253f8.43200068.jpeg', 2),
(100, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d26814.98674087.jpeg', 3),
(101, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d27930.36026303.jpeg', 4),
(102, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d28da2.89197898.jpeg', 5),
(103, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d2a977.83488384.jpeg', 6),
(104, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d2be45.97445291.jpeg', 7),
(105, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d2d373.02956428.jpeg', 8),
(106, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d2e452.33452544.jpeg', 9),
(107, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d2f6e1.88575008.jpeg', 10),
(108, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d306f6.14002596.jpeg', 11),
(109, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d315f8.84585922.jpeg', 12),
(110, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d324a7.82114518.jpeg', 13),
(111, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d33342.77837501.jpeg', 14),
(112, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d342d8.55913648.jpeg', 15),
(113, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d35406.54720039.jpeg', 16),
(114, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d362b5.24353028.jpeg', 17),
(115, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d37102.07698139.jpeg', 18),
(116, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d385a4.36216345.jpeg', 19),
(117, 13, 'database/karya/Detective_Conan/Chapter_2/chapter_img_685868c2d39428.72163991.jpeg', 20),
(118, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c91dc804.91365703.jpeg', 1),
(119, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c91dde00.49629676.jpeg', 2),
(120, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c91def34.42361708.jpeg', 3),
(121, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c91dfeb4.80114154.jpeg', 4),
(122, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c91e0d50.07825322.jpeg', 5),
(123, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c91e2bc8.81499315.jpeg', 6),
(124, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c91e3f79.68011283.jpeg', 7),
(125, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c91e5188.37568546.jpeg', 8),
(126, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c922f555.30711163.jpeg', 9),
(127, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c9230dd8.24004016.jpeg', 10),
(128, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c92321e9.88502717.jpeg', 11),
(129, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c92332e3.18357685.jpeg', 12),
(130, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c92342a2.51693210.jpeg', 13),
(131, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c92351e7.14350919.jpeg', 14),
(132, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c92360c7.10087240.jpeg', 15),
(133, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c92372b5.72176466.jpeg', 16),
(134, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c9238339.89187441.jpeg', 17),
(135, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c9239255.98665234.jpeg', 18),
(136, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c923a112.40303582.jpeg', 19),
(137, 14, 'database/karya/Detective_Conan/Chapter_3/chapter_img_685868c923b100.77370766.jpeg', 20),
(138, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cded4230.69665472.jpeg', 1),
(139, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cded5b20.82156187.jpeg', 2),
(140, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cded7165.04431477.jpeg', 3),
(141, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cded8719.64056934.jpeg', 4),
(142, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cded9b79.74246058.jpeg', 5),
(143, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdedb786.22087016.jpeg', 6),
(144, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdedc7b6.78883901.jpeg', 7),
(145, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdedd932.42847769.jpeg', 8),
(146, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdedeb66.48076476.jpeg', 9),
(147, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdedfdd2.26669999.jpeg', 10),
(148, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdee0f39.55258982.jpeg', 11),
(149, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdee1f75.04383447.jpeg', 12),
(150, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdee3153.30482119.jpeg', 13),
(151, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdee4351.13812089.jpeg', 14),
(152, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdee5bd4.70974590.jpeg', 15),
(153, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdee72e1.01482179.jpeg', 16),
(154, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdee8628.14963605.jpeg', 17),
(155, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdee9b14.45181507.jpeg', 18),
(156, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdeeb153.64731569.jpeg', 19),
(157, 15, 'database/karya/Detective_Conan/Chapter_4/chapter_img_685868cdeec680.50590147.jpeg', 20),
(158, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d5591812.13427159.jpeg', 1),
(159, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d5592cd2.93851360.jpeg', 2),
(160, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d5593e07.62612959.jpeg', 3),
(161, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d5594d22.32786361.jpeg', 4),
(162, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d5595b90.24686465.jpeg', 5),
(163, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d5597a13.73904712.jpeg', 6),
(164, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d5598b99.34281200.jpeg', 7),
(165, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d5599b10.52933571.jpeg', 8),
(166, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d559ab70.89704570.jpeg', 9),
(167, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d559c055.87613283.jpeg', 10),
(168, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d559d082.30363185.jpeg', 11),
(169, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d559df87.84908741.jpeg', 12),
(170, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d559ee20.25851471.jpeg', 13),
(171, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d559fe46.46432697.jpeg', 14),
(172, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d55a0e47.08008317.jpeg', 15),
(173, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d55a1e70.44411974.jpeg', 16),
(174, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d55a2f06.32304351.jpeg', 17),
(175, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d55a4126.67556294.jpeg', 18),
(176, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d55a5415.23639905.jpeg', 19),
(177, 16, 'database/karya/Detective_Conan/Chapter_5/chapter_img_685868d55a6556.36630396.jpeg', 20),
(178, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827e4e86.95625881.jpg', 1),
(179, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827e6128.42050492.jpg', 2),
(180, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827e70f7.54418706.jpg', 3),
(181, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827e8005.89212879.jpg', 4),
(182, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827e9485.62560903.jpg', 5),
(183, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827ebba7.65795510.jpg', 6),
(184, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827ed871.49347087.jpg', 7),
(185, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827eeec5.87232290.jpg', 8),
(186, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827f0112.02272940.jpg', 9),
(187, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827f1636.72778902.jpg', 10),
(188, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827f2ca6.57343081.jpg', 11),
(189, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827f4380.89473970.jpg', 12),
(190, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827f54e0.47333603.jpg', 13),
(191, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b827f6479.51766236.jpg', 14),
(192, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b8283d560.04193169.jpg', 15),
(193, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b8283fe45.66770072.jpg', 16),
(194, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b82841830.52575617.jpg', 17),
(195, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b82842a68.78577100.jpg', 18),
(196, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b82843c22.94144984.jpg', 19),
(197, 17, 'database/karya/Hunter_X_Hunter/Chapter_1/chapter_img_68586b82844c01.69031745.jpg', 20),
(198, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76c5bb0.58603872.jpg', 1),
(199, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76c6db3.58045185.jpg', 2),
(200, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76c7dd2.98337288.jpg', 3),
(201, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76c8f72.48323693.jpg', 4),
(202, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76c9f21.00073765.png', 5),
(203, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76cb927.20909915.jpg', 6),
(204, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76cc8b2.04261675.jpg', 7),
(205, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76cd778.26772208.png', 8),
(206, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76ce870.81952952.png', 9),
(207, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76d0164.94637522.png', 10),
(208, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76d1262.46493702.png', 11),
(209, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76d21a4.71624417.png', 12),
(210, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76d3239.36273646.png', 13),
(211, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76d4578.14538441.png', 14),
(212, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76d5593.76300892.png', 15),
(213, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76d6445.51470641.png', 16),
(214, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76d7439.43753679.png', 17),
(215, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76d8319.17404631.png', 18),
(216, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76d91d8.73550651.png', 19),
(217, 18, 'database/karya/Hinamatsuri/Chapter_1/chapter_img_68586bf76da038.99110363.png', 20),
(218, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6beb69.29954100.png', 1),
(219, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6bfdb2.73219091.png', 2),
(220, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6c0f33.19529076.png', 3),
(221, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6c1ff2.69944722.png', 4),
(222, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6c2fd8.41297332.png', 5),
(223, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6c4ae3.37358927.png', 6),
(224, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6c5a47.71225409.png', 7),
(225, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6c6904.26591364.png', 8),
(226, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6c7813.90145210.png', 9),
(227, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6c8712.56028143.png', 10),
(228, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6c95a1.79422655.png', 11),
(229, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6ca405.79113394.png', 12),
(230, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6cb297.04754666.png', 13),
(231, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6cc315.22985925.png', 14),
(232, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6cd1b2.19643511.png', 15),
(233, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6ce0a2.96420036.png', 16),
(234, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6cf048.80022458.png', 17),
(235, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6cfec3.85463306.png', 18),
(236, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6d0d22.05249282.png', 19),
(237, 19, 'database/karya/Fullmetal_Alchemist/Chapter_1/chapter_img_68586d9c6d1bd1.74359258.png', 20),
(238, 20, 'database/karya/Bochi_the_Rocks/Chapter_1/chapter_img_68586ef1ca1891.39575263.jpg', 1),
(239, 20, 'database/karya/Bochi_the_Rocks/Chapter_1/chapter_img_68586ef1ca2ad1.94073070.jpg', 2),
(240, 20, 'database/karya/Bochi_the_Rocks/Chapter_1/chapter_img_68586ef1ca3a97.51199428.jpg', 3),
(241, 20, 'database/karya/Bochi_the_Rocks/Chapter_1/chapter_img_68586ef1ca4a93.93444215.jpg', 4),
(242, 20, 'database/karya/Bochi_the_Rocks/Chapter_1/chapter_img_68586ef1ca5b70.95217723.jpg', 5),
(243, 20, 'database/karya/Bochi_the_Rocks/Chapter_1/chapter_img_68586ef1ca76d6.63855992.jpg', 6),
(244, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f46a5f4.47568343.png', 1),
(245, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f46bbd7.01302463.png', 2),
(246, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f46cf13.07515986.png', 3),
(247, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f46e061.05859992.png', 4),
(248, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f46f0d1.35312579.png', 5),
(249, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f470ab3.82019383.png', 6),
(250, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f471c08.96916524.png', 7),
(251, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f472cd9.80581203.png', 8),
(252, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f473df5.82586601.png', 9),
(253, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f474f96.12777637.png', 10),
(254, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f476120.82683618.png', 11),
(255, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f4771a7.72881698.png', 12),
(256, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f478418.52398445.png', 13),
(257, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f4794e8.87252829.png', 14),
(258, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f47a6c0.80300332.png', 15),
(259, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f47bf98.31408478.png', 16),
(260, 21, 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Chapter_1/chapter_img_6858713f47d103.65192306.png', 17),
(261, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b877e4.75682571.jpg', 1),
(262, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b89149.11484774.jpg', 2),
(263, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b8a7b6.05491609.jpg', 3),
(264, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b8bc76.07330729.jpg', 4),
(265, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b8cf03.03703686.jpg', 5),
(266, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b8ea43.23021803.jpg', 6),
(267, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b8fdc2.29262397.jpg', 7),
(268, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b90fe4.30386623.jpg', 8),
(269, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b92156.18772988.jpg', 9),
(270, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b93206.53631442.jpg', 10),
(271, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b94369.95880206.jpg', 11),
(272, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b954f9.89399389.jpg', 12),
(273, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b96555.34277390.jpg', 13),
(274, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b97798.75187075.jpg', 14),
(275, 22, 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Chapter_1/chapter_img_68587163b98a82.22626876.jpg', 15),
(276, 23, 'database/karya/Made_in_Abyss/Chapter_1/chapter_img_685875d1c190c5.90246010.jpg', 1),
(277, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f985bd29.50242116.jpg', 1),
(278, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f985da49.21481725.jpg', 2),
(279, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f988dfa9.38185172.jpg', 3),
(280, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f988fd91.38118279.jpg', 4),
(281, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f98913c0.47819624.jpg', 5),
(282, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f9893470.74568662.jpg', 6),
(283, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f9894c91.95303822.jpg', 7),
(284, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f98963d2.75713628.jpg', 8),
(285, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f98979d5.31314633.jpg', 9),
(286, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f9898fc5.14432470.jpg', 10),
(287, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f989a7f9.58370214.jpg', 11),
(288, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f989be21.02533241.jpg', 12),
(289, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f989d393.28739566.jpg', 13),
(290, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f989e937.35586105.jpg', 14),
(291, 24, 'database/karya/Kaguya-sama_Love_Is_War/Chapter_1/chapter_img_685875f989fdb2.79462613.jpg', 15);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `community_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `is_exclusive` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'True jika hanya untuk user premium',
  `member_count` int(11) DEFAULT 0,
  `thread_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `communities`
--

INSERT INTO `communities` (`community_id`, `creator_id`, `name`, `description`, `banner_url`, `is_exclusive`, `member_count`, `thread_count`, `created_at`) VALUES
(1, 2, 'Keluarga Besar Omink Kece', 'Grup diskusi resmi untuk semua karya dari Omink. Spoiler diizinkan, ayo diskusi bareng!', 'database/banners/community_banner_1.jpg', 0, 2, 0, '2025-06-24 13:50:59'),
(2, 3, 'Chiz Readers Club', 'Tempat berkumpul para pembaca setia karya-karya Chiz. Share teori dan fan-art kalian di sini!', 'database/banners/community_banner_2.jpg', 0, 0, 1, '2025-06-24 13:50:59');

-- --------------------------------------------------------

--
-- Table structure for table `community_members`
--

CREATE TABLE `community_members` (
  `membership_id` int(11) NOT NULL,
  `community_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `join_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_members`
--

INSERT INTO `community_members` (`membership_id`, `community_id`, `user_id`, `join_date`) VALUES
(1, 1, 2, '2025-06-24 13:50:59'),
(2, 1, 1, '2025-06-24 13:50:59'),
(3, 2, 3, '2025-06-24 13:50:59'),
(4, 2, 1, '2025-06-24 13:50:59'),
(5, 2, 2, '2025-06-24 14:30:58'),
(6, 2, 5, '2025-06-24 15:50:30'),
(7, 1, 5, '2025-06-24 16:01:47'),
(8, 1, 6, '2025-06-25 04:23:27');

-- --------------------------------------------------------

--
-- Table structure for table `community_threads`
--

CREATE TABLE `community_threads` (
  `thread_id` int(11) NOT NULL,
  `community_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `reply_count` int(11) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_threads`
--

INSERT INTO `community_threads` (`thread_id`, `community_id`, `user_id`, `title`, `content`, `reply_count`, `view_count`, `created_at`) VALUES
(1, 1, 1, 'Teori Ending Karya Omink yang Terbaru!', 'Guys, menurut gue endingnya nanti si karakter utama bakal ngorbanin diri buat nyelametin dunia. Tapi ada plot twist, ternyata dia gak mati, cuma pindah dimensi. Gimana menurut kalian?', 0, 0, '2025-06-24 13:50:59'),
(2, 1, 2, '[PENGUMUMAN] Chapter Baru Akan Rilis Lebih Cepat!', 'Halo semua, terima kasih atas dukungannya! Sebagai hadiah, chapter selanjutnya akan rilis 2 hari lebih awal dari jadwal. Ditunggu ya!', 0, 0, '2025-06-24 13:50:59'),
(3, 2, 1, 'Karakter Favorit Kalian di Karya Chiz Siapa?', 'Kalau aku sih suka banget sama side character yang tukang makan itu, kocak banget orangnya. Kalau kalian siapa?', 0, 0, '2025-06-24 13:50:59');

-- --------------------------------------------------------

--
-- Table structure for table `episodes`
--

CREATE TABLE `episodes` (
  `episode_id` int(11) NOT NULL,
  `work_id` int(11) NOT NULL,
  `episode_number` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `release_date` datetime NOT NULL,
  `comments_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `episodes`
--

INSERT INTO `episodes` (`episode_id`, `work_id`, `episode_number`, `title`, `is_premium`, `release_date`, `comments_enabled`, `created_at`) VALUES
(10, 3, 1, 'Chapter 1: You and I', 0, '2025-06-23 04:10:00', 1, '2025-06-22 20:10:39'),
(11, 4, 1, 'Chapter 1: Tentang Kamu', 0, '2025-06-23 04:29:00', 1, '2025-06-22 20:29:39'),
(12, 5, 1, 'Chapter 1: Bocil', 0, '2025-06-23 04:33:00', 1, '2025-06-22 20:34:05'),
(13, 5, 2, 'Chapter 1: Bocil', 0, '2025-06-23 04:33:00', 1, '2025-06-22 20:34:10'),
(14, 5, 3, 'Chapter 1: Bocil', 0, '2025-06-23 04:33:00', 1, '2025-06-22 20:34:17'),
(15, 5, 4, 'Chapter 1: Bocil', 0, '2025-06-23 04:33:00', 1, '2025-06-22 20:34:21'),
(16, 5, 5, 'Chapter 1: Bocil', 0, '2025-06-23 04:33:00', 1, '2025-06-22 20:34:29'),
(17, 9, 1, 'Chapter 1: Maaf', 0, '2025-06-23 04:45:00', 1, '2025-06-22 20:45:54'),
(18, 8, 1, 'Chapter 1: Sungguh', 0, '2025-06-23 04:47:00', 1, '2025-06-22 20:47:51'),
(19, 7, 1, 'Chapter 1: I ms u', 0, '2025-06-23 04:54:00', 1, '2025-06-22 20:54:52'),
(20, 6, 1, 'Chapter 1: Impian', 0, '2025-06-23 04:59:00', 1, '2025-06-22 21:00:33'),
(21, 13, 1, 'Chapter 1: About you 1975', 0, '2025-06-23 05:09:00', 1, '2025-06-22 21:10:23'),
(22, 12, 1, 'Chapter 1: Im miss you, Im sorry', 0, '2025-06-23 05:10:00', 1, '2025-06-22 21:10:59'),
(23, 11, 1, 'Chapter 1: You and I', 0, '2025-06-23 05:30:00', 1, '2025-06-22 21:29:53'),
(24, 10, 1, 'Chapter: Happy with you', 0, '2025-06-23 05:32:00', 1, '2025-06-22 21:30:33');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating_value` decimal(2,1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `reported_content_id` int(11) NOT NULL,
  `reported_content_type` enum('user','work','episode','comment','thread') NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','resolved','ignored') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thread_replies`
--

CREATE TABLE `thread_replies` (
  `reply_id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_reply_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thread_replies`
--

INSERT INTO `thread_replies` (`reply_id`, `thread_id`, `user_id`, `parent_reply_id`, `content`, `created_at`) VALUES
(1, 1, 2, NULL, 'Wah, teori yang menarik! Bisa jadi, bisa jadi. Ditunggu aja kelanjutannya ya, hehe.', '2025-06-24 13:50:59'),
(2, 1, 1, 1, 'Ditunggu banget, Kak Omink! Makasih udah dibaca teorinya!', '2025-06-24 13:50:59'),
(3, 3, 3, NULL, 'Makasih udah suka sama karya saya! Kalau saya pribadi suka sama karakter antagonisnya, lebih menantang untuk ditulis.', '2025-06-24 13:50:59'),
(4, 3, 1, 3, 'Wah, kreatornya sendiri yang balas! Keren banget, Kak Chiz!', '2025-06-24 13:50:59');

-- --------------------------------------------------------

--
-- Table structure for table `thread_reply_likes`
--

CREATE TABLE `thread_reply_likes` (
  `like_id` int(11) NOT NULL,
  `reply_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('topup_koin','unlock_episode','premium_subscription') NOT NULL,
  `amount_koin` int(11) DEFAULT 0,
  `amount_rp` decimal(10,2) DEFAULT 0.00,
  `related_id` int(11) DEFAULT NULL,
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'completed',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT 'pp.jpg',
  `password` varchar(255) NOT NULL,
  `role` enum('reader','author','admin') NOT NULL DEFAULT 'reader',
  `gender` enum('Male','Female') NOT NULL,
  `creator` tinyint(1) DEFAULT 0,
  `verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Status verifikasi untuk role author',
  `country` char(2) NOT NULL,
  `coin` int(11) DEFAULT 0,
  `thread_count` int(11) DEFAULT 0,
  `reply_count` int(11) DEFAULT 0,
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `premium_expiry_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `age`, `email`, `photo`, `password`, `role`, `gender`, `creator`, `verified`, `country`, `coin`, `thread_count`, `reply_count`, `is_premium`, `premium_expiry_date`, `created_at`) VALUES
(1, 'Chris', 'Christ Ahadi', 20, 'christahadi@gmail.com', 'pp.jpg', '$2y$10$Y7HDeA6dujAgbV/GyMdL3ug9NvRNOCuw4J3CEwtzAUPGA8xh58E1q', 'reader', 'Male', 0, 0, 'ID', 0, 0, 0, 0, NULL, '2025-06-12 13:50:26'),
(2, 'Omink', 'Omink Kece', 20, 'OminkKece@gmail.com', 'pp.jpg', '$2y$10$uXvLpP60y1ct3Qi7CX277ulOCRzNU1EJDkoVfCrmKl3w3BYjbW8Eu', 'author', 'Male', 1, 1, 'ID', 0, 0, 0, 0, NULL, '2025-06-22 12:19:35'),
(3, 'chiz', 'Christian Hadi', 20, 'christa@gmail.com', 'pp.jpg', '$2y$10$MRzAnjI96zRcqKqqk2.t/ei3KZoyZbms0d1Wi1QBCZPBkWcWOH7IO', 'author', 'Male', 1, 1, 'ID', 0, 0, 0, 0, NULL, '2025-06-23 04:14:08'),
(4, 'admin', 'Admin User', 0, 'admin@onverse.com', 'pp.jpg', '$2y$10$Y7HDeA6dujAgbV/GyMdL3ug9NvRNOCuw4J3CEwtzAUPGA8xh58E1q', 'admin', 'Male', 0, 1, '', 0, 0, 0, 0, NULL, '2025-06-23 08:26:46'),
(5, 'pwz', 'Pawas Aselole', 20, 'pwz@gmail.com', 'pp.jpg', '$2y$10$vLuWCO3eQibmYqWm.XDD5umQn6DBmVzVGSredhjsiSX1p86OtHjXy', 'reader', 'Male', 0, 0, 'ID', 0, 0, 0, 0, NULL, '2025-06-24 23:49:54'),
(6, 'chizz', 'Chri', 20, 'christi@gmail.com', 'pp.jpg', '$2y$10$59z.AeXadxeBOtLzeCjM8.GQ5zV2hhMdmD3anwaJX8QfCTLLRiNiS', 'author', 'Male', 1, 1, 'JP', 0, 0, 0, 0, NULL, '2025-06-25 12:15:26'),
(7, 'userr', 'new user', 19, 'userr@gmail.com', 'pp.jpg', '$2y$10$7czST3NvHPsSsIW7SXy5pua6lfY41XH9qz0nA00I7Wbz4OrdiiJSy', 'author', 'Male', 1, 0, 'ID', 0, 0, 0, 0, NULL, '2025-07-01 09:56:54');

-- --------------------------------------------------------

--
-- Table structure for table `works`
--

CREATE TABLE `works` (
  `work_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `genre2` varchar(50) DEFAULT NULL,
  `status` enum('published','draft','hidden') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `works`
--

INSERT INTO `works` (`work_id`, `author_id`, `title`, `description`, `thumbnail_url`, `genre`, `genre2`, `status`, `created_at`) VALUES
(3, 2, 'Magic Academy’s Genius Blinker', 'Ketegangan dan persahabatan terjalin di antara para siswa dalam mengejar impian mereka.', 'database/karya/Magic_Academys_Genius_Blinker/Thumbnail/thumb_6858625ab1dc28.02567525.jpg', 'Action', 'Romance', 'published', '2025-06-22 20:06:50'),
(4, 3, 'As Deep as the SKy', 'Ceritanya seru, mungkin?!?', 'database/karya/As_Deep_as_the_SKy/Thumbnail/thumb_685865f8b3df11.31516209.jpeg', 'Mystery', 'Historical', 'published', '2025-06-22 20:22:16'),
(5, 3, 'Detective Conan', 'Anak ilang jadi detektif aslinya dewasa, ih serem.', 'database/karya/Detective_Conan/Thumbnail/thumb_685868a16143c4.09545512.jpeg', 'Mystery', 'Slice of Life', 'published', '2025-06-22 20:33:37'),
(6, 3, 'Bochi the Rocks', 'Musik, Gila, Cewek, YTTA', 'database/karya/Bochi_the_Rocks/Thumbnail/thumb_685869b19476d8.06233061.jpg', 'Music', 'Slice of Life', 'published', '2025-06-22 20:38:09'),
(7, 3, 'Fullmetal Alchemist', 'Metal GGWP, Im Sorry', 'database/karya/Fullmetal_Alchemist/Thumbnail/thumb_68586a1c7d2bd8.44944246.jpg', 'Adventure', 'Action', 'published', '2025-06-22 20:39:56'),
(8, 3, 'Hinamatsuri', 'Hinamatsuri (ヒナまつり) is a Japanese manga series written and illustrated by Masao Ohtake [ja]. It was serialized in Enterbrain\'s magazine Harta, formerly known as Fellows!, from June 2010 to July 2020. Its chapters were collected in 19 tankōbon volumes. The series is licensed by One Peace Books. An anime television series adaptation produced by Feel aired from April to June 2018.', 'database/karya/Hinamatsuri/Thumbnail/thumb_68586aab455ad5.31600753.jpeg', 'Comedy', 'Slice of Life', 'published', '2025-06-22 20:42:19'),
(9, 3, 'Hunter X Hunter', 'berfokus pada kisah di sebuah dunia yang dihuni oleh sekumpulan orang-orang dengan menjadi menjadi hunter handal. Di antaranya ada Gon Freecss, pemuda belia yang kehilangan sosok ayahnya sejak kecil. Gon sendiri akhirnya sangat bertekad untuk menjadi seorang hunter terbaik. Tekad tersebut berkaitan dengan cita-citanya. Gon bercita-cita menjadi hunter untuk menemukan sang ayah. Karena ingin bertemu dengan sang ayah.', 'database/karya/Hunter_X_Hunter/Thumbnail/thumb_68586b2fa8fca4.83465809.jpeg', 'Adventure', 'Action', 'published', '2025-06-22 20:44:31'),
(10, 2, 'Kaguya-sama: Love Is War', 'Kaguya-sama: Love Is War is a Japanese romantic comedy manga series written and illustrated by Aka Akasaka. The story revolves around two high school geniuses, Kaguya Shinomiya and Miyuki Shirogane, who engage in a battle of wits to make the other confess their love first.', 'database/karya/Kaguya-sama_Love_Is_War/Thumbnail/thumb_685870258b18b1.59063873.jpg', 'Romance', 'Comedy', 'published', '2025-06-22 21:05:41'),
(11, 2, 'Made in Abyss', 'Made in Abyss is a manga and anime series about a girl and a robot who explore a mysterious hole in the earth.', 'database/karya/Made_in_Abyss/Thumbnail/thumb_685870639589e2.22940661.jpg', 'Adventure', 'Fantasy', 'published', '2025-06-22 21:06:43'),
(12, 2, 'Otonari no Tenshi-sama ni Itsunomanika Dame Ningen ni Sareteita Ken after the rain', 'Komik Sesat ini eeeeeeeee', 'database/karya/Otonari_no_Tenshi-sama_ni_Itsunomanika_Dame_Ningen_ni_Sareteita_Ken_after_the_rain/Thumbnail/thumb_6858709c971e32.32264257.jpg', 'Romance', 'Thriller', 'published', '2025-06-22 21:07:40'),
(13, 2, 'The Girl I Like Forgot Her Glasses', 'I love u, im sorry', 'database/karya/The_Girl_I_Like_Forgot_Her_Glasses/Thumbnail/thumb_6858711d857f65.61722754.jpg', 'Romance', 'Drama', 'published', '2025-06-22 21:09:49'),
(14, 6, 'Omink Cat Drama', 'Omink ogogogogo', 'database/karya/Omink_Cat_Drama/Thumbnail/thumb_685b7806f06690.02136942.jpg', 'Fantasy', 'Drama', 'draft', '2025-06-25 04:16:06'),
(15, 7, 'contoh', 'contoh1', 'database/karya/contoh/Thumbnail/thumb_6863419e373c54.08258893.jpeg', 'Action', 'Romance', 'draft', '2025-07-01 02:02:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chapter_images`
--
ALTER TABLE `chapter_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `episode_id` (`episode_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `episode_id` (`episode_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`community_id`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `idx_community_popularity` (`member_count`,`thread_count`);

--
-- Indexes for table `community_members`
--
ALTER TABLE `community_members`
  ADD PRIMARY KEY (`membership_id`),
  ADD UNIQUE KEY `unique_member` (`community_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `community_threads`
--
ALTER TABLE `community_threads`
  ADD PRIMARY KEY (`thread_id`),
  ADD KEY `community_id` (`community_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `episodes`
--
ALTER TABLE `episodes`
  ADD PRIMARY KEY (`episode_id`),
  ADD KEY `work_id` (`work_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `episode_id` (`episode_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `reporter_id` (`reporter_id`);

--
-- Indexes for table `thread_replies`
--
ALTER TABLE `thread_replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `thread_id` (`thread_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_reply_id` (`parent_reply_id`);

--
-- Indexes for table `thread_reply_likes`
--
ALTER TABLE `thread_reply_likes`
  ADD PRIMARY KEY (`like_id`),
  ADD UNIQUE KEY `unique_like` (`reply_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `works`
--
ALTER TABLE `works`
  ADD PRIMARY KEY (`work_id`),
  ADD KEY `author_id` (`author_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chapter_images`
--
ALTER TABLE `chapter_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `community_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `community_members`
--
ALTER TABLE `community_members`
  MODIFY `membership_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `community_threads`
--
ALTER TABLE `community_threads`
  MODIFY `thread_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `episodes`
--
ALTER TABLE `episodes`
  MODIFY `episode_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thread_replies`
--
ALTER TABLE `thread_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `thread_reply_likes`
--
ALTER TABLE `thread_reply_likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `works`
--
ALTER TABLE `works`
  MODIFY `work_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chapter_images`
--
ALTER TABLE `chapter_images`
  ADD CONSTRAINT `chapter_images_ibfk_1` FOREIGN KEY (`episode_id`) REFERENCES `episodes` (`episode_id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`episode_id`) REFERENCES `episodes` (`episode_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `communities`
--
ALTER TABLE `communities`
  ADD CONSTRAINT `communities_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_members`
--
ALTER TABLE `community_members`
  ADD CONSTRAINT `community_members_ibfk_1` FOREIGN KEY (`community_id`) REFERENCES `communities` (`community_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_threads`
--
ALTER TABLE `community_threads`
  ADD CONSTRAINT `community_threads_ibfk_1` FOREIGN KEY (`community_id`) REFERENCES `communities` (`community_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_threads_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `episodes`
--
ALTER TABLE `episodes`
  ADD CONSTRAINT `episodes_ibfk_1` FOREIGN KEY (`work_id`) REFERENCES `works` (`work_id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`episode_id`) REFERENCES `episodes` (`episode_id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `thread_replies`
--
ALTER TABLE `thread_replies`
  ADD CONSTRAINT `thread_replies_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `community_threads` (`thread_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `thread_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `thread_replies_ibfk_3` FOREIGN KEY (`parent_reply_id`) REFERENCES `thread_replies` (`reply_id`) ON DELETE CASCADE;

--
-- Constraints for table `thread_reply_likes`
--
ALTER TABLE `thread_reply_likes`
  ADD CONSTRAINT `thread_reply_likes_ibfk_1` FOREIGN KEY (`reply_id`) REFERENCES `thread_replies` (`reply_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `thread_reply_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `works`
--
ALTER TABLE `works`
  ADD CONSTRAINT `works_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
