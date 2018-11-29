-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2018 at 12:39 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `Ordring` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 show cats1 hide them',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0show comments 1 hide',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 show ads 1 hide'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `parent`, `Ordring`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(3, 'Games', 'Thisi s category games you can add only games here', 0, 3, 0, 0, 0),
(4, 'Computers', '', 0, 1, 0, 0, 0),
(5, 'Smart phones', 'Here You can add only smart phones and tablets', 0, 9, 1, 0, 1),
(8, 'Screens', 'Here you can find every thing you need about TV', 0, 4, 0, 0, 0),
(9, 'Hand Made', 'Here you can fine all hand made things', 0, 11, 0, 0, 0),
(10, 'Playstations', 'thjsff adasdad', 0, 15, 0, 0, 0),
(11, 'Electrics', 'dadad sdasds', 0, 15, 0, 0, 0),
(12, 'son cat', 'Here You can add only smart phones and tablets', 10, 3, 0, 0, 0),
(14, 'Samsung', 'This Category isn\'t allow', 5, 10, 0, 0, 0),
(15, 'Nokia', '', 5, 0, 0, 0, 0),
(16, 'Tablets', '', 5, 0, 0, 0, 0),
(17, 'Iphones', '', 5, 0, 0, 0, 0),
(18, 'Lenovo', 'Here You can add only smart phones and tablets', 5, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text CHARACTER SET utf32 NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `comment_date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `comment`, `status`, `comment_date`, `user_id`, `item_id`, `cat_id`) VALUES
(1, 'This a fantastic full website thanks for uploading this item ', 1, '2018-11-19', 1, 28, 3),
(2, 'This a fantastic full website thanks for uploading this itemThis a fantastic full website thanks for uploading this itemThis a fantastic full website thanks for uploading this itemThis a fantastic full website thanks for uploading this itemThis a fantastic full website thanks for uploading this itemThis a fantastic full website thanks for uploading this itemThis a fantastic full website thanks for uploading this item \r\ndasd asdas\r\ndas\r\nda\r\nsd\r\nasda sdasd asdasdas\r\ndas\r\ndasdasdasdasd', 1, '2018-11-20', 1, 28, 3),
(3, '', 1, '2018-11-21', 1, 29, 3),
(4, 'شسيشسيشسي', 1, '2018-11-21', 1, 29, 3),
(5, 'a', 1, '2018-11-21', 1, 29, 3),
(6, 'q', 1, '2018-11-21', 1, 29, 3),
(7, 'this is a new test comment for more don&#39;t press here :DDD', 1, '2018-11-21', 1, 29, 3),
(8, 'this is a new test comment for more don&#39;t press here :DDD', 1, '2018-11-21', 1, 29, 3),
(9, 'this is a new test comment for more don&#39;t press here :DDD', 1, '2018-11-21', 1, 29, 3),
(10, 'this is a new test comment for more don&#39;t press here :DDD', 1, '2018-11-21', 1, 29, 3),
(11, 'this is a new test comment for more don&#39;t press here :DDD', 1, '2018-11-21', 1, 29, 3),
(12, 'dasdasdadad asdad a s sdas dasas', 1, '2018-11-21', 1, 30, 3),
(13, 'sdfsdf&nbsp;sdfsdfas&nbsp;fasfa', 1, '2018-11-22', 30, 29, 3),
(14, 'Sold By  : member Added Date  : 2018-11-22', 1, '2018-11-22', 34, 28, 3);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Quantity` tinyint(4) NOT NULL,
  `approve` tinyint(4) NOT NULL DEFAULT '0',
  `tags` varchar(255) NOT NULL,
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `count_num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `image`, `Status`, `Rating`, `Quantity`, `approve`, `tags`, `Cat_ID`, `Member_ID`, `count_num`) VALUES
(27, 'ths is ', ' df sdf sdfsdf sdf sdfsdf', '10', '2018-11-25', 'caito', '', '1', 0, 10, 1, 'Nokia,bompow', 3, 34, 48),
(28, 'ths is test item a1sadasdsdsds', ' df sdf sdfsdf sdf sdfsdf', '10', '2018-11-22', 'caito', '', '1', 0, 10, 1, '', 3, 34, 133),
(29, 'Thisi s first Edited full item for more red the following post it&#39;s helpfull i guess .', ' <span class=\'h3 item_subtitle_info\'> Hello every body umm well i know it&#39;s only me who opens this site but it&#39;s ok </span>\r<br>I&#39;m gonna talk &#39;bout nothin&#39; I just wanna try the shity attributes i added to the text area such as this ordered list&nbsp;&nbsp;\r<br><ol>\r<br><li>Hey this is first line see you in the second one</li>\r<br><li>o wow this is the second one na see ya into the third one and last one at the same time lol</li>\r<br><li>finally you reached me let&#39;s do this again butusing unordered list are ya ready i think you are :D</li>\r<br></ol>&nbsp;&nbsp;\r<br>now this the un ordered lest let&#39;s try it hope you like it as i do :D&nbsp;&nbsp;<ul>\r<br><li>Damn this is me agian but na into unordred list the bullets looks bad a gun shot damn don&#39;t kill me i&#39;m in the second one</li>\r<br><li>uh it has no numbers how can i know where i&#39;m i now see ya soon</li>\r<br><li>woops wellcome again i used a map so that i think i&#39;m into the last one so see ya into a link a pretty link :D</li>\r<br></ul>&nbsp;&nbsp;\r<br>now what about trying the link attribute it&#39;s target blank that means the link appears in another site to not leave the articl\r<br><a href=\'index.php\' target=\'_blank\'>Hello again i&#39;m a link now wooooooha</a>\r<br>i don&#39;t wanna try a image but why not let&#39;s do it nowwwwwwwww\r<br><img class=\'img-responsive\' style=\'margin:0 auto;\' src=\'avatar-man.jpg\'> ', '100', '2018-11-21', 'Home', '', '1', 0, 99, 1, '', 3, 40, 156),
(30, 'vsdfsd', ' fsdf sdfs fsd fsdf sf sdf sdfs df sdfsdfsdfsdfsdf', '10', '2018-11-20', 'das das', '', '1', 0, 99, 1, '', 3, 1, 27),
(31, 'sdadas', 'dadasdadasdas dasdsd', '1000', '2018-11-20', 'dasdas', '', '1', 0, 10, 1, '', 3, 1, 26),
(32, 'asdasda', 'sdas asda&nbsp;&nbsp;asdas\r<br>da\r<br>sd\r<br>as\r<br>dasdas\r<br>das\r<br>d\r<br>asd\r<br>asdasd\r<br>as\r<br>das\r<br>d\r<br>as\r<br>das\r<br>d\r<br>asd\r<br>a\r<br>sd\r<br>as\r<br>da\r<br>sd\r<br>asd\r<br>as\r<br>d\r<br>asd', '10', '2018-11-20', 'China', '', '1', 0, 10, 1, '', 3, 1, 2),
(33, 'dasdasd', 'dasdadasdasdasdasd', '10', '2018-11-21', ' dasdasd ', '', '1', 0, 10, 1, '', 15, 1, 2),
(35, 'Thisi s first Edited full item for more red the following post it\'s helpfull i guess .', ' <span class=\'h3 item_subtitle_info\'> Hello every body umm well i know it\'s only me who opens this site but it\'s ok </span>\r\n<br>I\'m gonna talk \'bout nothin\' I just wanna try the shity attributes i added to the text area such as this ordered list  \r\n<br><ol>\r\n<br><li>Hey this is first line see you in the second one</li>\r\n<br><li>o wow this is the second one na see ya into the third one and last one at the same time lol</li>\r\n<br><li>finally you reached me let\'s do this again butusing unordered list are ya ready i think you are :D</li>\r\n<br></ol>  \r\n<br>now this the un ordered lest let\'s try it hope you like it as i do :D  <ul>\r\n<br><li>Damn this is me agian but na into unordred list the bullets looks bad a gun shot damn don\'t kill me i\'m in the second one</li>\r\n<br><li>uh it has no numbers how can i know where i\'m i now see ya soon</li>\r\n<br><li>woops wellcome again i used a map so that i think i\'m into the last one so see ya into a link a pretty link :D</li>\r\n<br></ul>  \r\n<br>now what about trying the link attribute it\'s target blank that means the link appears in another site to not leave the articl\r\n<br><a href=\'index.php\' target=\'_blank\'>Hello again i\'m a link now wooooooha</a>\r\n<br>i don\'t wanna try a image but why not let\'s do it nowwwwwwwww\r\n<br><img class=\'img-responsive\' style=\'margin:0 auto;\' src=\'avatar-man.jpg\'> ', '100', '2018-11-21', ' Home ', '', '1', 0, 99, 1, 'phones,Nokia,tags,me,you,more,action,stategey,race,games,lenovo,labtop,move,films', 10, 40, 4),
(37, 'juhjkhk', 'ugggggggghgjhg hgjghjvn hjvhgjghiutg igui iu', '10', '2018-11-21', 'hjgjhgj', '', '2', 0, 127, 1, '', 9, 40, 2),
(38, 'fgdfgdfgdfgd', 'gdfgdfgdfgdfgdf', '10', '2018-11-24', 'Cairo', '', '1', 0, 10, 1, '', 15, 1, 1),
(39, 'Nokia s16', 'dasda&nbsp;dasd&nbsp;asd&nbsp;asd&nbsp;cvzxvz&nbsp;vzxv<br/>zxvz<br/>vz<br/>xvz<br/>vz<br/>xv<br/>zx<br/>vz<br/>v<br/>zxvzx', '10', '2018-11-25', 'cairo', '', '1', 0, 99, 1, '', 15, 27, 9),
(41, 'fsdfsd', 'sddfsdf', '10', '2018-11-25', ' vcvxcxcvcv ', '', '2', 0, 10, 1, 'Nokia,bompow', 15, 1, 20),
(42, 'cvbcv', 'bcvbcvbcvbvbcvbcvbvcb', '10', '2018-11-25', 'jkhjk', '', '1', 0, 100, 1, 'hfdfdhdffh.gsfsd.fsd.fsd.fsdf.sd.f.sd.f.sd.f.sd.f,fsdfsdf sdfsd .fsdfsdfsdf', 15, 34, 5),
(43, 'kjlnhjlnj', 'kn,,bn,bmm,bm,bnm,bm,bm,bm,bm', '10', '2018-11-26', 'egypt', '', '1', 0, 10, 1, 'lenovo,phones,bom', 18, 30, 5),
(44, 'Nokia s600', 'This is nokia phone it&#39;s a very good phone with alot of requerments for more just visit us&nbsp;&nbsp;\r<br>Thnaks for reading this post hope you buy it&nbsp;&nbsp;\r<br>Thanks anyway for watching this ad', '10', '2018-11-27', 'Egypt', '4733003', '1', 0, 99, 0, 'Nokia , Phones , Cheap , Discount , Smartphones', 15, 1, 0),
(45, 'Nokia s600', 'This is nokia phone it&#39;s a very good phone with alot of requerments for more just visit us&nbsp;&nbsp;\r<br>Thnaks for reading this post hope you buy it&nbsp;&nbsp;\r<br>Thanks anyway for watching this ad', '10', '2018-11-27', 'Egypt', '4462416', '1', 0, 99, 0, 'Nokia , Phones , Cheap , Discount , Smartphones', 15, 1, 0),
(46, 'Nokia s600', 'This is nokia phone it&#39;s a very good phone with alot of requerments for more just visit us&nbsp;&nbsp;\r<br>Thnaks for reading this post hope you buy it&nbsp;&nbsp;\r<br>Thanks anyway for watching this ad', '10', '2018-11-27', 'Egypt', '4009151', '1', 0, 99, 0, 'Nokia , Phones , Cheap , Discount , Smartphones', 15, 1, 0),
(47, 'Nokia s600', 'This is nokia phone it&#39;s a very good phone with alot of requerments for more just visit us&nbsp;&nbsp;\r<br>Thnaks for reading this post hope you buy it&nbsp;&nbsp;\r<br>Thanks anyway for watching this ad', '10', '2018-11-27', 'Egypt', '3082790', '1', 0, 99, 0, 'Nokia , Phones , Cheap , Discount , Smartphones', 15, 1, 0),
(48, 'Nokia s600', 'This is nokia phone it&#39;s a very good phone with alot of requerments for more just visit us&nbsp;&nbsp;\r<br>Thnaks for reading this post hope you buy it&nbsp;&nbsp;\r<br>Thanks anyway for watching this ad', '10', '2018-11-27', 'Egypt', '3596696', '1', 0, 99, 0, 'Nokia , Phones , Cheap , Discount , Smartphones', 15, 1, 0),
(49, 'Nokia s600', 'This is nokia phone it&#39;s a very good phone with alot of requerments for more just visit us&nbsp;&nbsp;\r<br>Thnaks for reading this post hope you buy it&nbsp;&nbsp;\r<br>Thanks anyway for watching this ad', '10', '2018-11-27', 'Egypt', '9980255', '1', 0, 99, 0, 'Nokia , Phones , Cheap , Discount , Smartphones', 15, 1, 0),
(50, 'Nokia s600', 'This is nokia phone it&#39;s a very good phone with alot of requerments for more just visit us&nbsp;&nbsp;\r<br>Thnaks for reading this post hope you buy it&nbsp;&nbsp;\r<br>Thanks anyway for watching this ad', '10', '2018-11-27', 'Egypt', '8735345', '1', 0, 99, 0, 'Nokia , Phones , Cheap , Discount , Smartphones', 15, 1, 0),
(51, 'Nokia s600', 'This is nokia phone it&#39;s a very good phone with alot of requerments for more just visit us&nbsp;&nbsp;\r<br>Thnaks for reading this post hope you buy it&nbsp;&nbsp;\r<br>Thanks anyway for watching this ad', '10', '2018-11-27', 'Egypt', '4936403', '1', 0, 99, 0, 'Nokia , Phones , Cheap , Discount , Smartphones', 15, 1, 0),
(52, 'dasfdfsdfsfa', 'dasdasdasdasd', '10', '2018-11-27', 'vsdfsdfsd', '3181137l0uu.jpg', '2', 0, 10, 1, 'asds,adas,da,sd,as,d,as,da,s', 18, 1, 3),
(53, 'Apple iMac 21.5', '<ul>\r\n<br><li> This Certified Refurbished product is certified factory refurbished, shows limited or no wear, and includes all original accessories plus a 90-day warranty </li>\r\n<br><li> Chrome OS, Samsung Exynos 5250 Dual Core Processor  </li>\r\n<br><li> Display: 11.6\" LED HD 1366 x 768 16:9, Ports: HDMI, Headphone/MIC combo, 1 x USB 3.0 + 1 x USB 2.0, 3- in-1 (SD/SDHC/SDXC)  </li>\r\n<br><li> Memory: 2 GB DDR3L RAM, 16GB Solid State Drive  </li>\r\n<br><li> Built-in dual band Wi-Fi 802.11 a/b/g/n, Webcam, 3W Stereo Speaker  </li>\r\n<br></ul>    <span class=\'h3 item_subtitle_info\' style=\'display:block;\'> Warranty & Support </span>  Warranty, Labor:90 days limited warrantyAmazon.com Return Policy:You may return any new computer purchased from Amazon.com that is \"dead on arrival,\" arrives in damaged condition, or is still in unopened boxes, for a full refund within 30 days of purchase. Amazon.com reserves the right to test \"dead on arrival\" returns and impose a customer fee equal to 15 percent of the product sales price if the customer misrepresents the condition of the product. Any returned computer that is damaged through customer misuse, is missing parts, or is in unsellable condition due to customer tampering will result in the customer being charged a higher restocking fee based on the condition of the product. Amazon.com will not accept returns of any desktop or notebook computer more than 30 days after you receive the shipment. New, used, and refurbished products purchased from Marketplace vendors are subject to the returns policy of the individual vendor. ', '14515', '2018-11-28', '   USA   ', '330501661-LLdRCJ9L._SL1500_.jpg', '1', 0, 13, 1, 'Lenovo  , Apple , Labtop , Computer , Cheap , Discount', 18, 1, 0),
(54, 'Samsung Chromebook (Wi-Fi, 11.6-Inch) - Silver (Certified Refurbished)', '<ul><li> dasdasdasdasd</li>\r<br><li> dasdasdasdasdasd&nbsp;&nbsp;</li>\r<br><li> asdasdasdasdasdasdasddasdasd&nbsp;&nbsp;</li>\r<br></ul>&nbsp;&nbsp;', '10', '2018-11-28', 'Egypt', '514557461-LLdRCJ9L._SL1500_.jpg', '1', 0, 10, 1, 'Labtop,Nokia,Phones', 15, 34, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'The user ID',
  `Username` varchar(255) NOT NULL COMMENT 'The user name can&amp;#039;t use another name or name like it',
  `Password` varchar(255) NOT NULL COMMENT 'the user password',
  `Email` varchar(255) NOT NULL COMMENT 'The user Email',
  `FullName` varchar(255) NOT NULL COMMENT 'The user real name',
  `image` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT '0' COMMENT 'Admin 1 user 0',
  `RegStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'active 1 not active 0',
  `Sellerstates` int(11) NOT NULL DEFAULT '0' COMMENT 'good seller 1 normal 0',
  `Regdate` date NOT NULL COMMENT 'regestered date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `image`, `GroupID`, `RegStatus`, `Sellerstates`, `Regdate`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@shop.com', 'Admin page', '62373632013-12-11_OTRO_BARCELONA-CELTIC_14-Optimized.v1386863222.JPG', 1, 1, 1, '2018-10-30'),
(27, 'Mario', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Mario@gmail.com', 'Super Mario', '', 0, 1, 0, '2018-11-07'),
(28, 'Kero', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Kero@gmail.com', 'Kero Kemo', '', 0, 1, 0, '2018-11-07'),
(29, 'Maria', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Maria@yahoo.com', 'Maria Angel', '', 0, 1, 0, '2018-11-07'),
(30, 'Lizza', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Lic@gmail.com', 'Lizza Mark', '79969907c53362783d4c1b0784e5bf904389ccb.jpg', 0, 1, 0, '2018-11-07'),
(34, 'member', '6467baa3b187373e3931422e2a8ef22f3e447d77', 'Phones_phones_bom@test.com', 'Daniel Jacoub', '', 0, 1, 0, '2018-11-09'),
(36, 'dasd', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'dsads@daws.com', 'dasdasd', '', 0, 0, 0, '2018-11-13'),
(37, 'adasd', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'dadad@da.com', 'dasdas', '', 0, 0, 0, '2018-11-13'),
(38, 'assss', '8485b7fc160095a71d32b767c1fb9b5237b5d4c4', 'aaa@ads.com', 'asss', '', 0, 0, 0, '2018-11-13'),
(39, 'Gonsobhy', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Gonsobhy@gmail.com', 'Gon Sobhy', '', 0, 0, 0, '2018-11-16'),
(40, 'troy', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'troy@gmail.com', 'Troy killer', '', 0, 1, 0, '2018-11-19'),
(41, 'admindasdasd', 'e9739a543d12889ab7ff7714e772ce24418ff81b', 'dasdasd@dasd.com', 'asdasdasdasd', '4023638414_05_2014_19_46_165457.jpg', 0, 1, 0, '2018-11-26'),
(42, 'adminsasaaaa', '28aba81679ded1edeb97afd3b7bfa7c04680d762', 'asdasdsd@dsd.com', 'dasdasd', '990762051fa62ca024c34f8eac1fdf1496689c1b.jpg', 0, 1, 0, '2018-11-26'),
(43, 'marco', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'marco@gmail.com', 'marco daneil', '', 0, 1, 0, '2018-11-27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `item_comment` (`item_id`),
  ADD KEY `user_comment` (`user_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `cat_1` (`Cat_ID`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `items_views` (`count_num`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The user ID', AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_comment` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`),
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
