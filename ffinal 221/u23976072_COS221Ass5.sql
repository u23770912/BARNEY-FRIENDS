-- phpMyAdmin SQL Dump
-- version 5.0.4deb2~bpo10+1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 27, 2025 at 11:13 PM
-- Server version: 10.3.39-MariaDB-0+deb10u2
-- PHP Version: 7.3.31-1~deb10u7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u23976072_COS221Ass5`
--

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`brand_id`, `brand_name`) VALUES
(1, 'Nike'),
(2, 'Adidas'),
(3, 'Puma'),
(4, 'New Balance'),
(5, 'Reebok'),
(6, 'ASICS'),
(7, 'Under Armour'),
(8, 'Converse'),
(9, 'Vans'),
(10, 'Saucony');

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`img_id`, `product_id`, `url_1`, `url_2`, `url_3`) VALUES
(1, 1011, 'https://static.nike.com/a/images/f_auto/dpr_1.3,cs_srgb/w_956,c_limit/0ba2a1de-224d-4f0d-af29-dbdd0a7e5c68/nike-react.jpg', 'https://static.nike.com/a/images/t_PDP_864_v1/f_auto,b_rgb:f5f5f5/f9095711-654a-4cb3-9315-ebda9e79e2fa/custom-nike-pegasus-trail-5-by-you.png', 'https://static.nike.com/a/images/t_PDP_864_v1/f_auto,b_rgb:f5f5f5/86c99dbd-6c34-4fc9-90aa-e33ca4528586/custom-nike-pegasus-trail-5-by-you.png'),
(2, 1012, 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/4b214d0bffbf4732a6ddaf56016aa09b_9366/IE2280_01_standard.jpg', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/29fdc911e05a48b9b056af56016b58eb_9366/IE2280_02_standard_hover.jpg', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/78ecb1d0574b434591faaf56016c5f9f_9366/IE2280_04_standard.jpg'),
(3, 1013, 'https://images.puma.com/image/upload/f_auto,q_auto,w_600,b_rgb:FAFAFA/global/images/401352/02/sv01/fnd/ZAF/fmt/png?sw=480&q=60', 'https://images.puma.com/image/upload/f_auto,q_auto,w_600,b_rgb:FAFAFA/global/images/401352/02/fnd/ZAF/fmt/png', 'https://images.puma.com/image/upload/f_auto,q_auto,w_600,b_rgb:FAFAFA/global/images/401352/02/sv04/fnd/ZAF/fmt/png'),
(4, 1014, 'https://nb.scene7.com/is/image/NB/m1080lm_nb_05_i?$pdpflexf22x$&qlt=70&fmt=webp&wid=880&hei=880', 'https://nb.scene7.com/is/image/NB/m1080lm_nb_05_i?$dw_detail_main_lg$&bgc=f1f1f1&layer=1&bgcolor=f1f1f1&blendMode=mult&scale=10&wid=1600&hei=1600', 'https://nb.scene7.com/is/image/NB/m1080lm_nb_07_i?$dw_detail_main_lg$&bgc=f1f1f1&layer=1&bgcolor=f1f1f1&blendMode=mult&scale=10&wid=1600&hei=1600'),
(5, 1015, 'https://www.reebok.co.za/cdn/shop/files/PR7963BI3187_100074115_NANO_Unknwn_Training_Shoes_SZ4.webp?v=1728573357&width=533', 'https://www.reebok.co.za/cdn/shop/files/PR7962BI3596_100074781_Nano_X3_Froning_Training_Shoes_SZ4.webp?v=1728573344&width=720', 'https://www.reebok.co.za/cdn/shop/files/PR7962BI3597_100074781_Nano_X3_Froning_Training_Shoes_SZ4.webp?v=1728573346&width=1080'),
(6, 1016, 'https://images.asics.com/is/image/asics/1013A138_750_SR_RT_GLB?$sfcc-product$', 'https://images.asics.com/is/image/asics/1011B004_020_SB_FR_GLB?$zoom$', 'https://images.asics.com/is/image/asics/1011B004_020_SB_FL_GLB?$zoom$'),
(7, 1017, 'https://underarmour.scene7.com/is/image/Underarmour/3027637-101_A?rp=standard-30pad|gridTileDesktop&scl=1&fmt=jpg&qlt=50&resMode=sharp2&cache=on,on&bgc=F0F0F0&wid=512&hei=640&size=472,600', 'https://underarmour.scene7.com/is/image/Underarmour/3028498-280_A?rp=standard-30pad%7CpdpMainDesktop&scl=0.5&fmt=jpg&qlt=85&resMode=sharp2&cache=on%2Con&bgc=f0f0f0&wid=1836&hei=1950&size=850%2C850', 'https://underarmour.scene7.com/is/image/Underarmour/3028498-280_TOE?rp=standard-30pad%7CpdpMainDesktop&scl=0.5&fmt=jpg&qlt=85&resMode=sharp2&cache=on%2Con&bgc=f0f0f0&wid=1836&hei=1950&size=850%2C850'),
(8, 1018, 'https://www.converse.co.za/api/catalog/product/a/1/a14713c_a_08x1.jpg?width=279&height=348&store=converse&image-type=small_image', 'https://www.converse.co.za/api/catalog/product/a/1/a14713c_c_08x1.jpg', 'https://www.converse.co.za/api/catalog/product/a/1/a14713c_e_08x1.jpg'),
(9, 1019, 'https://cdn.shopify.com/s/files/1/0802/5836/7772/files/VN0A2Z33JVY-ALT1_002b5d04-52ef-43a6-8f54-06d96c2d68f0.jpg?width=500', 'https://cdn.shopify.com/s/files/1/0802/5836/7772/files/VN0A2Z33JVY-ALT1_002b5d04-52ef-43a6-8f54-06d96c2d68f0.jpg?v=1730113336', 'https://cdn.shopify.com/s/files/1/0802/5836/7772/files/VN0A2Z33JVY-ALT6.jpg?v=1719916263'),
(10, 1020, 'https://www.sportsclearance.co.za/cdn/shop/products/S10619-65_1_1024x1024@2x.jpg?v=1662380858', 'https://www.sportsclearance.co.za/cdn/shop/files/Screenshot2024-11-04at12.13.20_1024x1024@2x.png?v=1730715266', 'https://www.sportsclearance.co.za/cdn/shop/files/Screenshot2024-11-04at12.13.32_1024x1024@2x.png?v=1730715266'),
(11, 1001, 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/6911008e-3e46-41e2-9c5a-b048f473fed4/WMNS+AIR+MAX+90.png', 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/73c4644f-9817-4547-9594-4593196e49b1/WMNS+AIR+MAX+90.png', 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/1588ae40-add7-4b87-866a-d91fbb1e5135/WMNS+AIR+MAX+90.png'),
(12, 1002, 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/372f465c319f43ccb55506ced7242362_9366/IH2636_HM1.jpg', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/7caaad91e96b41768f2c5e9dd2351531_9366/IH2636_HM5.jpg', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/e5891a14b43a4f479a2f36026b14e204_9366/IH2636_HM9.jpg'),
(13, 1003, 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_750,h_750/global/371570/01/sv01/fnd/PNA/fmt/png/RS-X%C2%B3-Puzzle-Men\'s-Sneakers', 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_750,h_750/global/371570/01/fnd/PNA/fmt/png/RS-X%C2%B3-Puzzle-Men\'s-Sneakers', 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_750,h_750/global/371570/01/sv04/fnd/PNA/fmt/png/RS-X%C2%B3-Puzzle-Men\'s-Sneakers'),
(14, 1004, 'https://nb.scene7.com/is/image/NB/u574rcc_nb_02_i?$pdpflexf22x$&qlt=70&fmt=webp&wid=880&hei=880', 'https://nb.scene7.com/is/image/NB/u574rcc_nb_04_i?$pdpflexf22x$&qlt=70&fmt=webp&wid=880&hei=880', 'https://nb.scene7.com/is/image/NB/u574rcc_nb_03_i?$pdpflexf22x$&qlt=70&fmt=webp&wid=880&hei=880'),
(15, 1005, 'https://www.reebok.co.za/cdn/shop/files/PR7925BI3400_100074451_Club_C_85_Shoes_SZ4.webp?v=1728572836&width=1080', 'https://www.reebok.co.za/cdn/shop/files/PR7925BI3401_100074451_Club_C_85_Shoes_SZ4.webp?v=1728572838&width=1080', 'https://www.reebok.co.za/cdn/shop/files/PR7925BI3404_100074451_Club_C_85_Shoes_SZ4.webp?v=1728572845&width=1080'),
(16, 1006, 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSQmqUDHp_V19DCcUpwEaFNrx0lKtPP6J4t8GVdfHBMMOJu9BoRA0MXUR_OTuA5PNXGbNXh27xhOmO4Oe-344KE_sp6NGe1k4YuYQAialXZ_XI0zIYsULhaqA', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcTL75sI6I4S485MMnSe2ft3oiNW4ibXy-8K7c0GmCYsjo2RXfz_n8OV6PCbSc4YzduiLmqFQajTbn-3wAHl6syY3kKD8ja9XZZ0wFcxt3lDLkvqYwPCkYm9', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcS9rHhzif2NfkGIPVEnedTNzRkDBOr5GI8R69VU-BHq5UKMMO1hzDOqoumVuCHIUlpiVvHvgfn6rjPPWaZBKxE_md1EaLntg4rKsLzeUjwt'),
(17, 1007, 'https://cdn.shopify.com/s/files/1/0267/2315/6143/files/s7.3027595-108_DEFAULT_1000x1000.png.webp?v=1736344799&width=2000&crop=center', 'https://cdn.shopify.com/s/files/1/0267/2315/6143/files/s7.3027595-108_A_1000x1000.png.webp?v=1736344799&width=2000&crop=center', 'https://cdn.shopify.com/s/files/1/0267/2315/6143/files/s7.3027595-108_PAIR_1000x1000.png.webp?v=1736344799&width=2000&crop=center'),
(18, 1008, 'https://www.converse.co.za/api/catalog/product/m/9/m9160_a_08x1.png', 'https://www.converse.co.za/api/catalog/product/m/9/m9160_d_08x1.png', 'https://www.converse.co.za/api/catalog/product/m/9/m9160_e_08x1.png'),
(19, 1009, 'https://cdn.shopify.com/s/files/1/0802/5836/7772/files/VN0A2Z42NVY-HERO.jpg?width=500', 'https://cdn.shopify.com/s/files/1/0802/5836/7772/files/VN0A2Z42NVY-ALT1.jpg?width=500', 'https://cdn.shopify.com/s/files/1/0802/5836/7772/files/VN0A2Z42NVY-ALT2.jpg?width=500'),
(20, 1010, 'https://m.media-amazon.com/images/I/61sjmid75UL._AC_SY575_.jpg', 'https://m.media-amazon.com/images/I/61g3lUa0H9L._AC_SY575_.jpg', 'https://m.media-amazon.com/images/I/51J9e549I6L._AC_SY575_.jpg'),
(21, 1021, 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/57558712-5ebe-4abb-9984-879f9e896b4c/W+AIR+FORCE+1+%2707+FLYEASE.png', 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/b39413f0-19c5-4721-8889-86e8156c4047/W+AIR+FORCE+1+%2707+FLYEASE.png', 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/cc064523-78aa-4f06-b131-ef3943225168/W+AIR+FORCE+1+%2707+FLYEASE.png'),
(23, 1022, 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco,u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/f0ecd816-0bad-42ab-a76f-28c494a3534e/AIR+JORDAN+1+MID+%28GS%29.png', 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco,u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/5bf5c984-21aa-42be-8082-014198178c04/AIR+JORDAN+1+MID+%28GS%29.png', 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco,u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/b0b8b1bb-652e-4976-b54b-afbf51f12fdc/AIR+JORDAN+1+MID+%28GS%29.png'),
(24, 1023, 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/2d1bc853-734c-48be-94e7-b2b214d18723/NIKE+PEGASUS+41+CM.png', 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/1f4f8768-2e84-45ba-86af-08efb0a90d78/NIKE+PEGASUS+41+CM.png', 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/376b0480-1250-4db2-af80-62e711d3a0e3/NIKE+PEGASUS+41+CM.png'),
(25, 1024, 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/78b3d4866e764dabb25b9edc68dfc6fc_9366/JH7402_01_00_standard.jpg', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/3e9bda9c53244684b6e703ebaefa1aa5_9366/JH7402_02_standard_hover.jpg', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/eb0b196dccd84557bc41ee5e4bb7fe8c_9366/JH7402_03_standard.jpg'),
(26, 1025, 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/27fd9ef6d65d46a8ac6ce82f7276c81d_9366/JR9994_01_00_standard.jpg', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/4ad38fa09e5040ab9d9d55df69d7b6f3_9366/JR9994_02_standard.jpg', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/8e8c19984fca4bc7b9e2a9af6f469bfb_9366/JR9994_03_standard.jpg'),
(27, 1026, 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/cf6cbbd3378f473f9aff0f1a63449b9d_9366/IH0410_01_00_standard.jpg', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/65dafb256c314626b6a1ba27b2bbfce8_9366/IH0410_02_standard_hover.jpg', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/a3f0eba87e754f73b72460b16b7d320f_9366/IH0410_03_standard.jpg'),
(28, 1027, 'https://cdn-images.farfetch-contents.com/19/64/95/72/19649572_43861005_2048.jpg', 'https://cdn-images.farfetch-contents.com/19/64/95/72/19649572_43856998_2048.jpg', 'https://cdn-images.farfetch-contents.com/19/64/95/72/19649572_43859863_2048.jpg'),
(29, 1028, 'https://cdn-images.farfetch-contents.com/22/91/69/16/22916916_53794110_2048.jpg', 'https://cdn-images.farfetch-contents.com/22/91/69/16/22916916_53794114_2048.jpg', 'https://cdn-images.farfetch-contents.com/22/91/69/16/22916916_53794122_2048.jpg'),
(30, 1029, 'https://shineon.co.nz/cdn/shop/products/373871_01_sv03_4000x.jpg?v=1642037081', 'https://shineon.co.nz/cdn/shop/products/373871_01_sv04_4000x.jpg?v=1642036665', 'https://shineon.co.nz/cdn/shop/products/373871_01_bv_4000x.jpg?v=1642036665'),
(31, 1030, 'https://imagedelivery.net/2DfovxNet9Syc-4xYpcsGg/966e354e-6863-4eb7-08ad-3e106b50e500/product', 'https://imagedelivery.net/2DfovxNet9Syc-4xYpcsGg/2f4b8120-4df9-42b8-5970-3d00ce878700/product', 'https://imagedelivery.net/2DfovxNet9Syc-4xYpcsGg/6e63f9fc-ff69-4737-5f93-27ebdba4d600/product'),
(32, 1031, 'https://nb.scene7.com/is/image/NB/u327vsa_nb_02_i?$pdpflexf22x$&qlt=70&fmt=webp&wid=880&hei=880', 'https://nb.scene7.com/is/image/NB/u327vsa_nb_03_i?$pdpflexf22x$&qlt=70&fmt=webp&wid=880&hei=880', 'https://nb.scene7.com/is/image/NB/u327vsa_nb_04_i?$dw_detail_main_lg$&bgc=f1f1f1&layer=1&bgcolor=f1f1f1&blendMode=mult&scale=10&wid=1600&hei=1600'),
(33, 1032, 'https://nb.scene7.com/is/image/NB/bb550lem_nb_02_i?$dw_detail_main_lg$&bgc=f1f1f1&layer=1&bgcolor=f1f1f1&blendMode=mult&scale=10&wid=1600&hei=1600', 'https://nb.scene7.com/is/image/NB/bb550lem_nb_03_i?$dw_detail_main_lg$&bgc=f1f1f1&layer=1&bgcolor=f1f1f1&blendMode=mult&scale=10&wid=1600&hei=1600', 'https://nb.scene7.com/is/image/NB/bb550lem_nb_07_i?$dw_detail_main_lg$&bgc=f1f1f1&layer=1&bgcolor=f1f1f1&blendMode=mult&scale=10&wid=1600&hei=1600'),
(34, 1033, 'https://www.reebok.co.za/cdn/shop/files/PR8804BI4633_GY0961_Classic_Leather_Shoes_SZ4.webp?v=1728580534&width=1080', 'https://www.reebok.co.za/cdn/shop/files/PR8804BI4635_GY0961_Classic_Leather_Shoes_SZ4.webp?v=1728580537&width=720', 'https://www.reebok.co.za/cdn/shop/files/PR8804BI4636_GY0961_Classic_Leather_Shoes_SZ4.webp?v=1728580540&width=1080'),
(35, 1034, 'https://www.reebok.co.za/cdn/shop/files/PR7946BI3500_100074670_Zig_Kinetica_2_5_Shoes_SZ4.webp?v=1728573123&width=1080', 'https://www.reebok.co.za/cdn/shop/files/PR7946BI3501_100074670_Zig_Kinetica_2_5_Shoes_SZ4.webp?v=1728573125&width=1080', 'https://www.reebok.co.za/cdn/shop/files/PR7946BI3502_100074670_Zig_Kinetica_2_5_Shoes_SZ4.webp?v=1728573127&width=1080'),
(36, 1035, 'https://www.reebok.co.za/cdn/shop/files/PR8178BI14247_100206735_Floatzig_1_Womens_Running_Shoes_SZ4.webp?v=1728575478&width=1080', 'https://www.reebok.co.za/cdn/shop/files/PR8178BI14239_100206735_Floatzig_1_Womens_Running_Shoes_SZ4.webp?v=1728575480&width=1080', 'https://www.reebok.co.za/cdn/shop/files/PR8178BI14238_100206735_Floatzig_1_Womens_Running_Shoes_SZ4.webp?v=1728575482&width=1080'),
(37, 1036, 'https://images.asics.com/is/image/asics/1201A762_250_SR_RT_GLB?$zoom$', 'https://images.asics.com/is/image/asics/1201A762_250_SR_LT_GLB?$zoom$', 'https://images.asics.com/is/image/asics/1201A762_250_SB_FL_GLB?$zoom$'),
(38, 1037, 'https://images.asics.com/is/image/asics/1203A305_001_SR_RT_GLB?$zoom$', 'https://images.asics.com/is/image/asics/1203A305_001_SR_LT_GLB?$zoom$', 'https://images.asics.com/is/image/asics/1203A305_001_SB_FR_GLB?$zoom$'),
(39, 1038, 'https://images.asics.com/is/image/asics/1012B681_500_SR_RT_GLB-1?$sfcc-product$', 'https://images.asics.com/is/image/asics/1012B681_500_SB_FR_GLB-1?$zoom$', 'https://images.asics.com/is/image/asics/1012B681_500_SR_LT_GLB-1?$zoom$'),
(40, 1039, 'https://underarmour.scene7.com/is/image/Underarmour/3023543-002_DEFAULT?rp=standard-30pad|pdpZoomDesktop&scl=0.50&fmt=jpg&qlt=85&resMode=sharp2&cache=on,on&bgc=f0f0f0&wid=1836&hei=1950&size=850,850', 'https://underarmour.scene7.com/is/image/Underarmour/3023543-002_A?rp=standard-30pad|pdpZoomDesktop&scl=0.50&fmt=jpg&qlt=85&resMode=sharp2&cache=on,on&bgc=f0f0f0&wid=1836&hei=1950&size=850,850', 'https://underarmour.scene7.com/is/image/Underarmour/3023543-002_TOE?rp=standard-30pad|pdpZoomDesktop&scl=0.50&fmt=jpg&qlt=85&resMode=sharp2&cache=on,on&bgc=f0f0f0&wid=1836&hei=1950&size=850,850'),
(41, 1040, 'https://cdn.shopify.com/s/files/1/0267/2315/6143/files/s7.3026175-001_DEFAULT_1000x1000.png.webp?v=1690923186&width=2000&crop=center', 'https://cdn.shopify.com/s/files/1/0267/2315/6143/files/s7.3026175-001_A_1000x1000.png.webp?v=1690923186&width=2000&crop=center', 'https://cdn.shopify.com/s/files/1/0267/2315/6143/files/s7.3026175-001_PAIR_1000x1000.png.webp?v=1690923185&width=2000&crop=center'),
(42, 1041, 'https://cdn.shopify.com/s/files/1/0267/2315/6143/files/s7.3026034-001_DEFAULT_1000x1000.png.webp?v=1721119419&width=2000&crop=center', 'https://cdn.shopify.com/s/files/1/0267/2315/6143/files/s7.3026034-001_A_1000x1000.png.webp?v=1721119419&width=2000&crop=center', 'https://cdn.shopify.com/s/files/1/0267/2315/6143/files/s7.3026034-001_PAIR_1000x1000.png.webp?v=1721119419&width=2000&crop=center'),
(43, 1042, 'https://www.converse.co.za/api/catalog/product/a/1/a11752c_a_08x1.jpg', 'https://www.converse.co.za/api/catalog/product/a/1/a11752c_c_08x1.jpg', 'https://www.converse.co.za/api/catalog/product/a/1/a11752c_e_08x1.jpg'),
(44, 1043, 'https://www.converse.co.za/api/catalog/product/a/1/a12598c_a_08x1.jpg', 'https://www.converse.co.za/api/catalog/product/a/1/a12598c_c_08x1.jpg', 'https://www.converse.co.za/api/catalog/product/a/1/a12598c_e_08x1.jpg'),
(45, 1044, 'https://www.converse.co.za/api/catalog/product/1/6/164225c_l_08x1.jpg', 'https://www.converse.co.za/api/catalog/product/1/6/164225c_a_08x1.jpg', 'https://www.converse.co.za/api/catalog/product/1/6/164225c_c_08x1.jpg'),
(46, 1045, 'https://cdn.shopify.com/s/files/1/0802/5836/7772/files/VN000EYECHR-HERO.jpg?v=1718876943', 'https://cdn.shopify.com/s/files/1/0802/5836/7772/files/VN000EYECHR-ALT1.jpg?v=1718876956', 'https://cdn.shopify.com/s/files/1/0802/5836/7772/files/VN000EYECHR-ALT2.jpg?v=1718876960'),
(47, 1046, 'https://www.sportsclearance.co.za/cdn/shop/files/S20756-36_1_1024x1024@2x.jpg?v=1737973821', 'https://www.sportsclearance.co.za/cdn/shop/files/S20756-36_2_1024x1024@2x.jpg?v=1737973821', 'https://www.sportsclearance.co.za/cdn/shop/files/S20756-36_3_1024x1024@2x.jpg?v=1737973821'),
(48, 1047, 'https://s7d4.scene7.com/is/image/WolverineWorldWide/S21000-107_1?$dw-pdp-primary$', 'https://s7d4.scene7.com/is/image/WolverineWorldWide/S21000-107_2?$dw-hi-res$', 'https://s7d4.scene7.com/is/image/WolverineWorldWide/S21000-107_3?$dw-hi-res$'),
(49, 1048, 'https://cdn.shopify.com/s/files/1/0802/5836/7772/files/VN000D7YBM8-HERO.jpg?v=1736804820', 'https://cdn.shopify.com/s/files/1/0802/5836/7772/files/VN000D7YBM8-ALT1.jpg?v=1736804820', 'https://cdn.shopify.com/s/files/1/0802/5836/7772/files/VN000D7YBM8-ALT2.jpg?v=1736804820'),
(50, 1049, 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/d01ef37b-c14a-4edd-8787-534f5732294c/NIKE+DUNK+LOW+RETRO.png', 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/3d1463d0-0fae-438e-b174-13cd6786cb86/NIKE+DUNK+LOW+RETRO.png', 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/76faaa42-1ecc-4e50-9731-b1f6137e005d/NIKE+DUNK+LOW+RETRO.png'),
(51, 1050, 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/09c5ea6df1bd4be6baaaac5e003e7047_9366/FY7756_01_00_standard.jpg', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/ff82213b88c74ac5a0cbac5e004bd8e3_9366/FY7756_02_standard_hover.jpg', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/4cb5876afbae4250a787ac5e00365cd3_9366/FY7756_03_standard.jpg');

--
-- Dumping data for table `price`
--

INSERT INTO `price` (`price_id`, `product_id`, `retailer_id`, `price`, `availability`) VALUES
(1, 1001, 1, '3461.85', 9),
(2, 1001, 2, '2445.35', 2),
(3, 1001, 3, '2751.27', 19),
(4, 1002, 1, '1506.59', 9),
(5, 1002, 2, '1650.83', 0),
(6, 1002, 3, '1815.79', 19),
(7, 1003, 1, '1874.21', 10),
(8, 1003, 2, '3048.43', 19),
(9, 1003, 3, '2908.29', 6),
(10, 1004, 1, '1730.44', 17),
(11, 1004, 2, '2081.16', 18),
(12, 1004, 3, '2202.16', 11),
(13, 1005, 1, '1521.22', 9),
(14, 1005, 2, '3002.08', 18),
(15, 1005, 3, '2353.42', 14),
(16, 1006, 1, '2846.19', 13),
(17, 1006, 2, '2212.65', 20),
(18, 1006, 3, '3068.76', 13),
(19, 1007, 1, '3302.83', 20),
(20, 1007, 2, '3209.54', 0),
(21, 1007, 3, '2092.31', 0),
(22, 1008, 1, '2030.01', 0),
(23, 1008, 2, '3130.34', 1),
(24, 1008, 3, '1712.89', 19),
(25, 1009, 1, '2894.38', 13),
(26, 1009, 2, '2464.10', 3),
(27, 1009, 3, '3173.27', 11),
(28, 1010, 1, '2923.16', 19),
(29, 1010, 2, '2528.31', 0),
(30, 1010, 3, '3077.15', 6),
(31, 1011, 1, '1886.52', 15),
(32, 1011, 2, '2866.90', 0),
(33, 1011, 3, '2367.03', 0),
(34, 1012, 1, '2339.93', 4),
(35, 1012, 2, '2834.65', 0),
(36, 1012, 3, '3359.52', 9),
(37, 1013, 1, '1522.03', 19),
(38, 1013, 2, '2784.88', 18),
(39, 1013, 3, '3105.51', 14),
(40, 1014, 1, '2624.97', 0),
(41, 1014, 2, '2041.83', 17),
(42, 1014, 3, '2783.64', 0),
(43, 1015, 1, '2632.89', 0),
(44, 1015, 2, '2069.89', 1),
(45, 1015, 3, '3441.22', 4),
(46, 1016, 1, '2375.50', 0),
(47, 1016, 2, '1722.91', 14),
(48, 1016, 3, '2676.37', 0),
(49, 1017, 1, '2431.65', 6),
(50, 1017, 2, '1589.42', 7),
(51, 1017, 3, '2517.28', 0),
(52, 1018, 1, '2704.16', 2),
(53, 1018, 2, '2325.69', 0),
(54, 1018, 3, '1855.89', 5),
(55, 1019, 1, '3323.60', 10),
(56, 1019, 2, '2927.22', 15),
(57, 1019, 3, '2740.43', 13),
(58, 1020, 1, '2262.42', 6),
(59, 1020, 2, '2198.09', 2),
(60, 1020, 3, '2596.44', 0),
(61, 1021, 1, '3499.99', 8),
(62, 1021, 2, '3399.99', 12),
(63, 1021, 3, '3599.99', 10),
(64, 1022, 1, '4299.99', 5),
(65, 1022, 2, '4199.99', 7),
(66, 1022, 3, '4399.99', 6),
(67, 1023, 1, '3299.99', 9),
(68, 1023, 2, '3199.99', 11),
(69, 1023, 3, '3399.99', 8),
(70, 1024, 1, '2299.99', 15),
(71, 1024, 2, '2199.99', 18),
(72, 1024, 3, '2399.99', 12),
(73, 1025, 1, '2499.99', 14),
(74, 1025, 2, '2399.99', 16),
(75, 1025, 3, '2599.99', 13),
(76, 1026, 1, '2799.99', 9),
(77, 1026, 2, '2699.99', 11),
(78, 1026, 3, '2899.99', 8),
(79, 1027, 1, '2399.99', 7),
(80, 1027, 2, '2299.99', 10),
(81, 1027, 3, '2499.99', 8),
(82, 1028, 1, '2199.99', 12),
(83, 1028, 2, '2099.99', 15),
(84, 1028, 3, '2299.99', 10),
(85, 1029, 1, '1999.99', 9),
(86, 1029, 2, '1899.99', 13),
(87, 1029, 3, '2099.99', 11),
(88, 1030, 1, '3699.99', 6),
(89, 1030, 2, '3599.99', 8),
(90, 1030, 3, '3799.99', 7),
(91, 1031, 1, '2499.99', 14),
(92, 1031, 2, '2399.99', 17),
(93, 1031, 3, '2599.99', 12),
(94, 1032, 1, '2799.99', 9),
(95, 1032, 2, '2699.99', 12),
(96, 1032, 3, '2899.99', 10),
(97, 1033, 1, '1999.99', 15),
(98, 1033, 2, '1899.99', 18),
(99, 1033, 3, '2099.99', 13),
(100, 1034, 1, '2399.99', 8),
(101, 1034, 2, '2299.99', 11),
(102, 1034, 3, '2499.99', 9),
(103, 1035, 1, '2199.99', 12),
(104, 1035, 2, '2099.99', 15),
(105, 1035, 3, '2299.99', 10),
(106, 1036, 1, '2599.99', 9),
(107, 1036, 2, '2499.99', 12),
(108, 1036, 3, '2699.99', 10),
(109, 1037, 1, '3299.99', 7),
(110, 1037, 2, '3199.99', 9),
(111, 1037, 3, '3399.99', 8),
(112, 1038, 1, '1999.99', 14),
(113, 1038, 2, '1899.99', 17),
(114, 1038, 3, '2099.99', 12),
(115, 1039, 1, '2799.99', 8),
(116, 1039, 2, '2699.99', 11),
(117, 1039, 3, '2899.99', 9),
(118, 1040, 1, '2299.99', 13),
(119, 1040, 2, '2199.99', 16),
(120, 1040, 3, '2399.99', 11),
(121, 1041, 1, '3499.99', 7),
(122, 1041, 2, '3399.99', 9),
(123, 1041, 3, '3599.99', 8),
(124, 1042, 1, '1899.99', 15),
(125, 1042, 2, '1799.99', 18),
(126, 1042, 3, '1999.99', 13),
(127, 1043, 1, '2399.99', 9),
(128, 1043, 2, '2299.99', 12),
(129, 1043, 3, '2499.99', 10),
(130, 1044, 1, '1699.99', 14),
(131, 1044, 2, '1599.99', 17),
(132, 1044, 3, '1799.99', 12),
(133, 1045, 1, '1499.99', 16),
(134, 1045, 2, '1399.99', 19),
(135, 1045, 3, '1599.99', 14),
(136, 1046, 1, '3299.99', 8),
(137, 1046, 2, '3199.99', 11),
(138, 1046, 3, '3399.99', 9),
(139, 1047, 1, '2799.99', 12),
(140, 1047, 2, '2699.99', 15),
(141, 1047, 3, '2899.99', 10),
(142, 1048, 1, '1299.99', 17),
(143, 1048, 2, '1199.99', 20),
(144, 1048, 3, '1399.99', 15),
(145, 1049, 1, '3999.99', 6),
(146, 1049, 2, '3899.99', 8),
(147, 1049, 3, '4099.99', 7),
(148, 1050, 1, '2699.99', 11),
(149, 1050, 2, '2599.99', 14),
(150, 1050, 3, '2799.99', 10);

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `brand_id`, `description`, `availability`, `retailer_id`) VALUES
(1001, 1, 'Air Max 90', 1, 1),
(1002, 2, 'Ultraboost 21', 1, 2),
(1003, 3, 'RS-X³', 30, 3),
(1004, 4, '574 Classic', 1, 1),
(1005, 5, 'Club C 85', 1, 2),
(1006, 6, 'Gel-Kayano 28', 14, 3),
(1007, 7, 'HOVR Phantom 2', 1, 1),
(1008, 8, 'Chuck Taylor All Star', 1, 2),
(1009, 9, 'Old Skool', 1, 3),
(1010, 10, 'Jazz Original', 1, 1),
(1011, 1, 'React Infinity Run Flyknit', 1, 2),
(1012, 2, 'NMD_R1 Primeblue', 1, 3),
(1013, 3, 'Suede Classic', 1, 1),
(1014, 4, 'Fresh Foam 1080v11', 1, 2),
(1015, 5, 'Nano X3', 1, 3),
(1016, 6, 'Gel-Nimbus 23', 1, 1),
(1017, 7, 'Curry 8', 1, 2),
(1018, 8, 'One Star', 1, 3),
(1019, 9, 'Sk8-Hi', 17, 1),
(1020, 10, 'Kinvara 12', 1, 2),
(1021, 1, 'Air Force 1', 1, 1),
(1022, 1, 'Air Jordan 1', 1, 2),
(1023, 1, 'Pegasus 38', 1, 3),
(1024, 2, 'Stan Smith', 1, 1),
(1025, 2, 'Superstar', 1, 2),
(1026, 2, 'Ozweego', 1, 3),
(1027, 3, 'RS-Fast', 1, 1),
(1028, 3, 'Future Rider', 1, 2),
(1029, 3, 'Cali Sport', 1, 3),
(1030, 4, '990v5', 1, 1),
(1031, 4, '327', 1, 2),
(1032, 4, '550', 1, 3),
(1033, 5, 'Classic Leather', 1, 1),
(1034, 5, 'Zig Kinetica', 1, 2),
(1035, 5, 'Floatride Energy 3', 1, 3),
(1036, 6, 'Gel-Lyte III', 1, 1),
(1037, 6, 'Gel-Quantum 360', 1, 2),
(1038, 6, 'Gel-Contend 7', 1, 3),
(1039, 7, 'HOVR Sonic 4', 1, 1),
(1040, 7, 'Charged Assert 9', 1, 2),
(1041, 7, 'Project Rock 3', 1, 3),
(1042, 8, 'Chuck 70', 1, 1),
(1043, 8, 'Run Star Hike', 1, 2),
(1044, 8, 'Jack Purcell', 1, 3),
(1045, 9, 'Slip-On', 1, 1),
(1046, 10, 'Endorphin Speed 3', 1, 1),
(1047, 10, 'Ride 15', 1, 2),
(1048, 9, 'Authentic', 1, 3),
(1049, 1, 'Dunk Low', 1, 2),
(1050, 2, 'Forum Low', 1, 1);

--
-- Dumping data for table `retailer`
--

INSERT INTO `retailer` (`retailer_id`, `retailer_name`) VALUES
(1, 'Shoe City'),
(2, 'Sportscene'),
(3, 'courtorder');

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `product_id`, `user_id`, `text`, `rating`, `review_date`) VALUES
(2, 1001, 1, 'Absolutely loved this product', 5, '2025-05-25'),
(3, 1001, 1, 'Horrible', 1, '2025-05-25'),
(4, 1001, 15, 'over hyped', 2, '2025-05-26'),
(9, 1002, 15, 'good fit', 1, '2025-05-26'),
(10, 1002, 15, 'Uncomfortable to wear', 4, '2025-05-26'),
(11, 1011, 15, 'Been running in these for a mont. Best purchase made I have made thus far', 5, '2025-05-26'),
(12, 1012, 15, 'Great for running', 5, '2025-05-26'),
(13, 1001, 15, 'Great shoe', 5, '2025-05-26'),
(14, 1004, 15, 'Comfortable fit, but the cleaning process is terrible', 3, '2025-05-27'),
(15, 1002, 21, 'Great Product', 4, '2025-05-27');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `password`, `salt`, `api_key`) VALUES
(1, 'Luyanda', 'Ndlovu', 'luyanda.ndlovu4@gmail.com', '4be80a0b4aa92cffedbfbf26b3e8bd479db15559a0c118f2fd9d6e224dfd7102', 'ac05594192fb835ad7d5e63bb0603dcd', 'c125745760ae31f957f68e4f6fe957ff'),
(2, 'John', 'Doe', 'john@example.com', '0f54ec7c6bbb261db1e08b9d3dad18be6b33d0c26c478fdce828a245a9d57b2d', '1efd2894be0c90ab533bcb6534058fd8', '245787f5448947383a3fe3f26590123e'),
(3, 'Alice', 'Wong', 'alice@example.com', '438fa73a9233c90dba04f4dd1e89530acd3335b62490c41d6090a346753f79ed', '4a64e56630139c0297a0759a77f6b5d0', 'a4fb9979f2d972565a406c624f62d3d8'),
(4, 'Jabu', 'Mavusa', 'Jabu@mail.com', 'cd52c1048fe15beded6b5c34318f96dc09a885a7763de86649732ad7bd4b9e96', '98f4ebb53735dfe1728097e278c7b473', '16ff7fb1f219cecf4e0df883b7037e0e'),
(5, 'Tshepo', 'Doe', 'Tshepo@example.com', 'bf064e9397ee953d36bbb5647b87c6cfda7fba5051a6e71f110c16cfb53d5077', '8104d07be3824aef9b9fb3a05b018da6', '565503e1119307ee1c80a515cdad122f'),
(6, 'Thabang', 'Zuma', 'Thabang@Zuma.com', '2d6b97700394197b32e048ce2318b72311f5f36e6d8ce487da1d7d3f21ff5c6a', '5e578c58202bee6dd83c4181828165fb', '04353816bb8ad2091cb75e244ded6fd7'),
(7, 'Zakes', 'Bantwini', 'ZakesBantwini@gmail.com', '6b504a95885d906eae15b82d5c337b40d35dd93349f3e62ca0cf25f33ac581ed', '0572699f59ddabe1dc97bf10b61af252', '202342aec8b005e797644dd2c37e5e36'),
(8, 'Stacey', 'Bossman', 'StaceyBossman@gmail.com', 'f899e9a0c23801e6432857d4d8e55b10491a930ab295e4bfb26857508b2fa6f3', '844da735677261b30f30f68c93f0192f', '126ab95c33bc686b08ff6aa638275d81'),
(9, 'njabulo', 'magesa', 'njabs@mail.com', '8d0b69ce5abedca256e5637bb0da2fd005e5b018a7e5693b6d97c2d1b3b08545', '277f2a721fd25a7f2f552231a5709cf7', '5d4b887512f2efca7d38c02de110c301'),
(10, 'Bozza', 'Chadwick', 'BozzaChadwick@gmail.com', '9b7949a7f06ee6eab044658162471fb26256770086c2132f83ae5a270fd4e560', 'b0a08d247a04a1ae10acd5ab50533813', '6b45561a0c8b47717ed822974fb10b78'),
(11, 'Xander', 'Smit', 'XanderSmit@icloud.com', '1141a0d5925d574c6461ad2e08de09bfad7eb2d47edecf5a0a31083a4612ba20', 'b2f108bbdbb8f62a7d9d1318296554e6', 'b537596d8cf386d9dbe6553db8cdcaa4'),
(12, 'Siya', 'Nkosi', 'SiyaNkosi@icloud.com', '98ebe5463da67935837c00f508f67335956facc9888ec1b695f1742f095ace88', '44ce904ccd7fe991517bad20f110118c', '8b0298a0ba9cf1abee5b6a7fadd9c7cc'),
(14, 'tshiamo', 'magesa', 'maten@mail.com', '4a4169a6d38c945d2e3b31f9ebe22c9cc7d9254bfa642684609c844faf350a8f', 'aca51795af15ea8d9644a8d93f380529', '3d0614f1e4723969c511ca0e469ff6e1'),
(15, 'Zakes', 'Bantwini', 'MarchantGrootboom@gmail.com', '5d16141dba2c368d3d3375aab9d5732647ac297cd24fdf07685b088cdaf76d7a', '7a3df21e3cc2217c4befa74669cda9fa', '4e449ce2d7237fe856532b911bfa978a'),
(16, 'tshiamo', 'magesa', 'matenten@mail.com', '9fc49c7cfb65df65201d36ab75152fc76e34b9f46851c8be633965c77fbfd4f5', 'a24deb222e7c5b59b4106a3a2c0cfa53', 'eb0be9be23b4d8e99195b3d732459f62'),
(17, 'tshiamo', 'magesa', 'matententen@mail.com', '49ef49437d525597f8d3a7f21d519d76a46134aad1ab85271e00bc63261c7955', '96fa9aa407940eda4eb5de6e13bc6413', '601bfd2fb4f5de0d528abf07c5cb7673'),
(18, 'Betty', 'Flame', 'BettyFlame@gmail.com', 'ea0c928b9dab81241ec74b0665d3732248f7820433db16b08dce36a6fa8eae08', '51d2e9cffefe7e866975680e28478cb8', '34ab125d5b69b589a842103b22e9623d'),
(19, 'Jabu', 'Mavula', 'jabzini@gmail.com', '28e67ada11b39e8c39ae2c24a3586f8c648730ae25a3b95e60183513541ac8ef', '9b9285207a048467c903c1e0e9076615', 'c1ed8c78fc9ef12412191a7263bd1d7a'),
(20, 'Luyanda', 'Ndlovu', 'tony@starkindustries.com', '80b0810f3d61ce777a92ec962a8fb4ca2be657808cb1a9c4023e30f2b2b55e6a', '81dd777fc09014bad8d18d89605d60e2', '8e6aaaaf4ee4541be93c32ec159f1be4'),
(21, 'Siya', 'Smit', 'siya@smit.com', '88b604826208f8bfe579a9720adf605aedd9576a8fff647fb2a3beafb2a812ce', '986bf84d8736ba2c79414fb71842ad91', 'e1fb1383422fb3850403da2bdb0fa12d');

--
-- Dumping data for table `user_preferences`
--

INSERT INTO `user_preferences` (`user_id`, `brand_id`, `price_range`, `count`) VALUES
(15, 1, 'mid', 2),
(15, 2, 'mid', 1),
(15, 5, 'mid', 1),
(15, 7, 'mid', 1);

--
-- Dumping data for table `user_preferences`
--

INSERT INTO `user_preferences` (`user_id`, `brand_id`, `price_range`, `count`) VALUES
(1012, 1, '2025-05-24 12:02:19'),
(1013, 2, '2025-05-24 12:02:46'),
(1014, 2, '2025-05-24 12:03:11'),
(1003, 18, '2025-05-26 11:48:51'),
(1008, 18, '2025-05-26 13:58:38'),
(1001, 20, '2025-05-26 17:42:46'),
(1002, 21, '2025-05-27 15:42:14');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
