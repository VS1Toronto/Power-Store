-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2019 at 06:38 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `power_store`
--

	
CREATE DATABASE IF NOT EXISTS `power_store`;
USE `power_store`;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `ip_address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `main_categories`
--

CREATE TABLE IF NOT EXISTS `main_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `main_categories`
--

INSERT INTO `main_categories` (`category_id`, `category_name`) VALUES
(1, 'Electronics'),
(2, 'Crockery'),
(3, 'DVD'),
(4, 'Books'),
(5, 'Timepiece'),
(6, 'Menswear'),
(7, 'Womenswear'),
(8, 'Car');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(11,0) NOT NULL,
  `quantity` int(11) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  `product_image_1` varchar(100) NOT NULL,
  `product_image_2` varchar(100) NOT NULL,
  `product_image_3` varchar(100) NOT NULL,
  `product_image_4` varchar(100) NOT NULL,
  `product_feature_1` varchar(100) NOT NULL,
  `product_feature_2` varchar(100) NOT NULL,
  `product_feature_3` varchar(100) NOT NULL,
  `product_feature_4` varchar(100) NOT NULL,
  `product_feature_5` varchar(100) NOT NULL,
  `product_price` varchar(100) NOT NULL,
  `product_discount` varchar(100) NOT NULL,
  `product_discount_price` varchar(100) NOT NULL,
  `product_quantity` varchar(11) NOT NULL,
  `product_model` varchar(100) NOT NULL,
  `product_warranty` varchar(100) NOT NULL,
  `product_for_whome` varchar(100) NOT NULL,
  `product_keyword` varchar(100) NOT NULL,
  `product_added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category_id`, `sub_category_id`, `product_image_1`, `product_image_2`, `product_image_3`, `product_image_4`, `product_feature_1`, `product_feature_2`, `product_feature_3`, `product_feature_4`, `product_feature_5`, `product_price`, `product_discount`, `product_discount_price`, `product_quantity`, `product_model`, `product_warranty`, `product_for_whome`, `product_keyword`, `product_added_date`) VALUES
(1, 'Emergency Lights', 1, 1, '1_emergency_light.png', '2_emergency_light.png', '3_emergency_light.png', '4_emergency_light.png', '3 AA batteries required', 'Cool white', 'Glass', 'LED', '2 Watts', '22.99', '20', '18.392', '1998', 'B01CUOZNI2', '1 year', '', 'emergency,light,glass,battery', '2019-08-20 19:06:43'),
(2, 'Lamborghini Murcielago', 8, 2, '1_lamborghin.png', '2_lamborghin.png', '3_lamborghin.png', '4_lamborghin.png', '211 MPH', '6.5L V12', '640 Brake Horsepower', 'MPG 9 City / 14 Highway', 'manual / e-gear semo automatic', '288984.99', '', '', '10000', 'LP640', '1 year', '', 'lamborghini,supercar,italy', '2019-09-04 16:12:15'),
(3, 'LED bulbs', 1, 3, '1_LED_bulbs.png', '2_LED_bulbs.png', '3_LED_bulbs.png', '4_LED_bulbs.png', 'Bayonet light bulbs', '100W equivalent', 'Cool white', 'Non dimmable', 'Energy saving light bulbs', '16.89', '20', '13.512', '5000', 'LH-XPB22-014-6000K-4', '1 year', '', 'bulb,energy,saving,bayonet', '2019-09-06 02:08:48'),
(4, 'Hair Dryer', 1, 4, '1_hair_dryer.png', '2_hair_dryer.png', '3_hair_dryer.png', '4_hair_dryer.png', '2300 Watts', 'Three heat settings', 'Two speed settings', 'Cool shot feature', '3.5 meter cord', '29.32', '', '', '1000', 'D5215', '1 year', '', 'hair,dryer,watt,watts', '2019-09-06 02:07:54'),
(5, 'Hair Straightener', 1, 5, '1_hair_straightener.png', '2_hair_straightener.png', '3_hair_straightener.png', '4_hair_straightener.png', '1.5 inch width plate', '7 heat settings', '20 seconds to reach 200 celcius', 'Ceramic heat technology ensures even temperature', 'Embroidered luxury carry case', '142.94', '40', '85.764', '1000', 'B00454UDBS', '1 year', '', 'hair,straightener', '2019-09-06 02:08:53'),
(6, 'Trimmer', 1, 6, '1_trimmer.png', '2_trimmer.png', '3_trimmer.png', '4_trimmer.png', 'Self Sharpening Steel Blades', '5 attachments beard/hair/ear etc', 'Fully washable', 'USB charge', 'LED display', '34.99', '', '', '1000', 'B07F15249V', '1 year', '', 'trimmer,hair,beard,moustache', '2019-09-06 02:09:06'),
(7, 'Decorative Lights', 1, 7, '1_decorative_lights.png', '2_decorative_lights.png', '3_decorative_lights.png', '4_decorative_lights.png', 'String lights', '8 Modes Dimable', 'Water resistant', 'Outdoor or indoor', '31 volts', '12.99', '', '', '500', 'B07NZ31DH5', '1 year', '', 'LED,string,decorative,light,lights', '2019-09-06 02:10:00'),
(8, 'Computer', 1, 8, '1_computer.png', '2_computer.png', '3_computer.png', '4_computer.png', 'AMD A10-9700', '3,8 GHz quad core', 'Radeon R7 graphics', '1 TB HDD', '8GB DDR4', '299.99', '10', '269.991', '1000', 'AD-6400-55-8-vp7', '1 year', '', 'computer.tower,gaming,amd,radeon', '2019-09-06 02:09:56'),
(9, 'Laptop', 1, 9, '1_hp_15_corei3_laptop.png', '2_hands_free.png', '3_hard_disk.png', '4_graphics_card.png', 'Intel Core? i3-7020U Processor', 'RAM: 4 GB / Storage: 1 TB HDD', 'Full HD Display', 'Battery life: Up to 13 hours', '2.04 Kg', '497.57', '', '', '1000', '4BA34EA', '1 year', '', 'laptop,hp,intel,i3,HD', '2019-09-06 02:09:52'),
(10, 'Tablet', 1, 10, '1_tablet.png', '2_tablet.png', '3_tablet.png', '4_tablet.png', 'OS:Android', '10\" HD display', 'Snapdragon Quad core processor', 'Dual front Dolby speakers', '531 g', '109.99', '', '', '1000', 'B07MWCVXV1', '1 year', '', 'tablet,android,snapdragon,quad,core', '2019-09-06 02:09:46'),
(11, 'Mobile', 1, 11, '1_mobile.png', '2_mobile.png', '3_mobile.png', '4_mobile.png', 'Android 9 Pie smatphone', 'Qualcomm Snapdragon 710 mobile platform', '4GB RAM with 64GB storage', '6.18â€™â€™ Full HD+ edge-to-edge PureDisplay', '20 MP front camera', '279.99', '', '', '2000', '11PNXS01A03', '1 year', '', 'cell,mobile,snapdragon,android', '2019-09-06 02:09:37'),
(12, 'Elisa Cups Saucer Set', 2, 12, '1_cups_and_saucer.png', '2_cups_and_saucer.png', '3_cups_and_saucer.png', '4_cups_and_saucer.png', '24 Piece Porcelain', 'Color - Ivory white China', 'Temperature resistant', 'Safe in dishwasher', 'Made in China of Fine Stoneware Ceramic', '29.99', '20', '23.992', '200', 'FR-ELI-CPS*2', '1 year', '', 'cups,saucer,crockery,dining,tea,coffee', '2019-08-29 13:32:34'),
(13, 'Iron', 1, 13, '1_iron.png', '2_iron.png', '3_iron.png', '4_iron.png', '2400 Watts', 'Steam output 45 grams a minute', '170 gram steam boost to penetrate fabrics', 'Vertical steaming for fabrics', '3 meter cord', '74.99', '20', '59.992', '1000', 'GC4567/86', '1 year', '', 'iron,ironing,steam,fabric', '2019-09-06 02:09:33'),
(14, 'Denby Cups Saucer Set ', 2, 12, '5_cups_and_saucer.png', '6_cups_and_saucer.png', '7_cups_and_saucer.png', '8_cups_and_saucer.png', '12 piece dinner set', 'Hand Crafted', 'Glazed to enhance durability', 'Oven, microwave, dish washer and freezer safe', 'Material stoneware', '75.99', '20', '60.792', '200', 'B0752SBWWG', '1 year', '', 'cups,saucer,crockery,dining,tea,coffee', '2019-09-06 02:16:15'),
(15, 'Stainless Steel Watch', 5, 14, '1_stainless_watch.png', '2_stainless_watch.png', '3_stainless_watch.png', '4_stainless_watch.png', 'Quartz Watxh', 'Stainless Steel', 'Multi-eye movement', 'Case Thickness 9.5mm', 'Grey Dial', '194.65', '', '', '500', '	1513596', '2 year', 'men', 'watch,quartz,stainless,dial', '2019-09-06 02:18:29'),
(16, 'Mens Black Belt', 6, 15, '1_mens_belt_one.png', '2_mens_belt_one.png', '3_mens_belt_one.png', '4_mens_belt_one.png', 'Genuine Leather', 'Black Leather', 'Ratchet Belt', 'Silver Color Buckle', '3.5cm ^ 130cm', '9.49', '', '', '1000', 'LEU-2469', '1 year', 'men', 'belt,black,leather', '2019-09-06 02:18:08'),
(17, 'Mens Black Belt', 6, 15, '1_mens_belt_two.png', '2_mens_belt_two.png', '3_mens_belt_two.png', '4_mens_belt_two.png', 'Genuine Leather', 'Black Leather', 'Ratchet Belt', 'Silver Geometric Buckle', '3.5cm ^ 130cm', '12.95', '', '', '1000', 'SN012', '1 year', 'men', 'belt,black,leather', '2019-09-06 02:18:35'),
(18, 'Mens Black Belt', 6, 15, '1_mens_belt_three.png', '2_mens_belt_three.png', '3_mens_belt_one.png', '4_mens_belt_three.png', 'Genuine Leather', 'Black Leather', 'Ratchet Belt', 'Silver Geometric Buckle', '3.5cm ^ 130cm', '8.99', '', '', '1000', 'LEU-2646283', '1 year', 'men', 'belt,black,leather', '2019-09-06 02:18:40'),
(19, 'Cufflinks', 6, 16, '1_cufflinks_one.png', '2_cufflinks_one.png', '3_cufflinks_one.png', '4_cufflinks_one.png', 'Silver plain cufflinks', 'Rectangular', 'Crafted in 925 Sterling Silver', 'Includes giftbox', 'International guarantee certificate', '111.99', '', '', '200', '0.74,1091', '1 year', 'men', 'cuff,cufflink,cufflinks', '2019-09-06 02:19:07'),
(20, 'Palm Beach Dinner Set', 2, 12, '1_cup_saucer_three.png', '2_cup_saucer_three.png', '3_cup_saucer_three.png', '4_cup_saucer_three.png', '12 piece porcelain', 'Rice varnish effect', '4 dinner, 4 desset, 4 bowls', 'Bowl capacity 550ml', 'Vintage look', '69.99', '', '', '198', '217600', '1 year', '', 'cups,saucer,crockery,dining,tea,coffee', '2019-09-06 02:16:21'),
(21, 'Analogue Quartz Watch', 5, 14, '1_womens_watch_one.png', '2_womens_watch_one.png', '3_womens_watch_one.png', '4_womens_watch_one.png', 'Analogue Quartz', 'Band Material: Steel', 'Case Thickness: 7 mm; Case Size: 30 mm', 'Band Width: 12 mm; Inner Circumference: 175+/- 5 mm', 'Packed in Skagen Gift Box', '103.99', '', '', '500', 'SKW2749', '1 year', 'women', 'analogue,quartz,stainless,steel', '2019-09-06 02:19:15'),
(22, 'Black Scarf', 7, 17, '1_womans_scarf_one.png', '2_womans_scarf_one.png', '3_womans_scarf_one.png', '4_womans_scarf_one.png', 'Black Material: 30% Cashmere, 70% Cotton', '70% Cotton, 30% Cashmere', 'Large size: \"71\" *25\" (180cm*64cm)', 'Weightï¼š9.1 Ouncesï¼ˆ260gï¼‰', 'Gift bag packing', '14.99', '', '', '200', 'GESCA001', '1 year', 'women', 'black,scarf,cotton,cashmere', '2019-09-06 02:20:05'),
(23, 'Beige Scarf', 7, 17, '1_womens_scarf_two.png', '2_womens_scarf_two.png', '3_womens_scarf_two.png', '4_womens_scarf_two.png', 'Cashmere feeling and super comfortable. ', 'Environmentally friendly material', 'Weight 259 g', 'Scarf Dimensions: 200cm x 70cm', 'Boxed Dimensions 28 x 18.4 x 3.6 cm', '8.99', '', '', '0', 'B07JGP8JFM', '1 year', 'women', 'beige,scarf,scarfes', '2019-09-06 02:20:09'),
(24, 'Diamond Crystal Earrings', 7, 18, '1_earrings_one.png', '2_earrings_one.png', '3_earrings_one.png', '4_earrings_one.png', 'Sparkling Diamond White crystal', '925 Sterling Silver', 'Hypoallergenic & Nickle Free Jewellery', 'Dimensions 6mm x 6mm.', 'Luxury white jewellery box', '19.99', '', '', '200', 'pzzz7040', '1 year', 'women', 'stud,crystal,earring,earrings,silver', '2019-09-06 02:19:44'),
(25, 'Military Watch', 5, 14, '1_military_watch_1.png', '2_military_watch_1.png', '3_military_watch_1.png', '4_military_watch_1.png', 'Analogue and digital watch', 'German high hardness mineral glass', 'Japanese movement and battery', 'Stainless steel case', 'High quality genuine leather', '24.99', '', '', '500', 'JS-8051 blackpd hxhkblackmA', '1 year', 'men', 'military,stainless,steel,analogue,digital,watch', '2019-09-06 02:19:23'),
(26, 'Ferrari F50', 8, 19, '1_ferrari_f50_one.png', '2_ferrari_f50_one.png', '3_ferrari_f50_one.png', '4_ferrari_f50_one.png', '202 MPH', 'Naturally aspirated 65 degree 60 valve V12', '520 ps (513 bhp / 382 kw) @ 8000 rpm', 'MPG 7 City / 10 Highway', 'Transmission 6 speed manual', '1799950.99', '', '', '10000', 'F50', '1 year', '', 'car,cars,ferrari,supercar,supercars', '2019-09-06 02:20:39'),
(27, 'Fenyr SuperSport', 8, 20, '1_fenyr_supersport.png', '2_fenyr_supersport.png', '3_fenyr_supersport.png', '4_fenyr_supersport.png', '245 MPH', '3.8 L twin-turbocharged flat-six', '900 horse power', 'MPG 14.1 City / 28.5 Highway', 'Transmission	7-speed PDK', '1559827.99', '', '', '10000', '440781', '1 year', '', 'car,cars,fenyr,supersport,,hypercars', '2019-09-06 02:20:43'),
(28, 'The Expanse', 3, 21, '1_expanse_season_1.png', '2_expanse_season_1.png', '3_expanse_season_1.png', '4_expanse_season_1.png', 'Sci-fi', 'PAL', 'Region 2', 'Box Set', 'Release Date 13 August 2018', '14.99', '', '', '2000', 'B07DQXYPS7', '1 year', '', 'dvd,television,sci-fi,expanse', '2019-09-06 02:21:15'),
(29, 'Dark Matter', 3, 21, '1_dark_matter_season_1.png', '2_dark_matter_season_1.png', '3_dark_matter_season_1.png', '4_dark_matter_season_1.png', 'Sci-fi', 'PAL', 'Region 2', 'Box Set', 'Release Date 11 September 2017', '11.99', '', '', '2000', 'B0119MC3SY', '1 year', '', 'dvd,television,sci-fi,dark,matter', '2019-09-06 02:21:18'),
(30, 'Killjoys', 3, 21, '1_killjoys_season_1.png', '2_killjoys_season_1.png', '3_killjoys_season_1.png', '4_killjoys_season_1.png', 'Sci-fi', 'PAL', 'Region 2', 'Box Set', 'Release Date 27 June 2016', '13.45', '', '', '2000', 'B01BTV8KQY', '1 year', '', 'dvd,television,sci-fi,killjoys', '2019-09-06 02:21:22'),
(31, 'Brave New World', 4, 22, '1_brave_new_world.png', '2_brave_new_world.png', '3_brave_new_world.png', '4_brave_new_world.png', 'Author - Aldous Huxley', 'Paperback', 'New Edition', 'Publisher - Flamingo', 'Published 24 June 1977', '4.99', '', '', '200', 'B0073D7956', '', '', 'book,paperback,classic,science,fiction', '2019-09-06 02:21:51'),
(32, 'I Cyborg', 4, 23, '1_i_cyborg.png', '2_i_cyborg.png', '3_i_cyborg.png', '4_i_cyborg.png', 'Author - Kevin Warwick', 'Paperback', '320 pages', 'Publisher - Century', 'Published - 1 August 2002', '12.19', '', '', '200', 'ISBN-13: 978-0712669887', '', '', 'book,paperback,science', '2019-09-06 02:21:55'),
(33, 'The Singularity Is Nere', 4, 24, '1_singularity.png', '2_singularity.png', '3_singularity.png', '4_singularity.png', 'Author - Ray Kurzweil', 'Paperback', '683 Pages', 'Publisher - Duckworth', 'Published 9 March 2006', '15.23', '', '', '200', 'ISBN-13: 978-0715635612', '', '', 'book,paperback,science,futurist', '2019-09-06 02:21:59');

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE IF NOT EXISTS `slider` (
  `slider_id` int(11) NOT NULL,
  `slider_image_1` varchar(100) NOT NULL,
  `slider_image_2` varchar(100) NOT NULL,
  `slider_image_3` varchar(100) NOT NULL,
  `slider_image_4` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`slider_id`, `slider_image_1`, `slider_image_2`, `slider_image_3`, `slider_image_4`) VALUES
(1, '1.png', '2.png', '3.png', '4.png');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE IF NOT EXISTS `sub_categories` (
  `sub_category_id` int(11) NOT NULL,
  `sub_category_name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`sub_category_id`, `sub_category_name`, `category_id`) VALUES
(1, 'Emergency Lights', 1),
(2, 'Lamborghini', 8),
(3, 'LED Bulbs', 1),
(4, 'Hair Dryer', 1),
(5, 'Hair Straightener', 1),
(6, 'Trimmer', 1),
(7, 'Decorative Lights', 1),
(8, 'Computer', 1),
(9, 'Laptop', 1),
(10, 'Tablet', 1),
(11, 'Mobile', 1),
(12, 'Cups Saucer Set', 2),
(13, 'Iron', 1),
(14, 'Watch', 9),
(15, 'Belt', 10),
(16, 'Cufflinks', 10),
(17, 'Scarf', 11),
(18, 'Earrings', 11),
(19, 'Ferrari', 8),
(20, 'Fenyr', 8),
(21, 'Sci-fi', 3),
(22, 'Science Fiction', 4),
(23, 'Science', 4),
(24, 'Futurist', 4);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_address` varchar(100) NOT NULL,
  `user_pin_code` varchar(11) NOT NULL,
  `user_dob` varchar(50) NOT NULL,
  `user_phone` varchar(50) NOT NULL,
  `user_image` varchar(100) NOT NULL,
  `user_country` varchar(100) NOT NULL,
  `user_state` varchar(100) NOT NULL,
  `user_register_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_cart`
--

CREATE TABLE IF NOT EXISTS `user_cart` (
  `user_cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_wishlist`
--

CREATE TABLE IF NOT EXISTS `user_wishlist` (
  `user_wish_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `main_categories`
--
ALTER TABLE `main_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`slider_id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`sub_category_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD PRIMARY KEY (`user_cart_id`);

--
-- Indexes for table `user_wishlist`
--
ALTER TABLE `user_wishlist`
  ADD PRIMARY KEY (`user_wish_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `slider_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `sub_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_cart`
--
ALTER TABLE `user_cart`
  MODIFY `user_cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user_wishlist`
--
ALTER TABLE `user_wishlist`
  MODIFY `user_wish_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
