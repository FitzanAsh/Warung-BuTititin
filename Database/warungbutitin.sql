-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for warungbutitin
CREATE DATABASE IF NOT EXISTS `warungbutitin` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `warungbutitin`;

-- Dumping structure for table warungbutitin.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `cart_ibfk_1` (`user_id`),
  KEY `cart_ibfk_2` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table warungbutitin.cart: ~0 rows (approximately)
DELETE FROM `cart`;

-- Dumping structure for table warungbutitin.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table warungbutitin.categories: ~2 rows (approximately)
DELETE FROM `categories`;
INSERT INTO `categories` (`id`, `name`) VALUES
	(1, 'Makanan'),
	(2, 'Minuman');

-- Dumping structure for table warungbutitin.hubungikami
CREATE TABLE IF NOT EXISTS `hubungikami` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pesan` text NOT NULL,
  `waktu_submit` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table warungbutitin.hubungikami: ~2 rows (approximately)
DELETE FROM `hubungikami`;
INSERT INTO `hubungikami` (`id`, `nama`, `email`, `pesan`, `waktu_submit`) VALUES
	(1, 'Ronny', 'ronibakri@gmail.com', 'Enak', '2023-11-27 13:35:00'),
	(2, 'Windy', 'windyy7551@gmail.com', 'Makanan disini Enak', '2023-11-27 13:38:39');

-- Dumping structure for table warungbutitin.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('Belum Dibaca','Dibaca') DEFAULT 'Belum Dibaca',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_pesanan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_ibfk_1` (`order_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table warungbutitin.notifications: ~2 rows (approximately)
DELETE FROM `notifications`;
INSERT INTO `notifications` (`id`, `order_id`, `message`, `status`, `created_at`, `status_pesanan`) VALUES
	(18, 38, 'Lontong Sayur (Jumlah: 2, Harga Total: 14000.00)', 'Belum Dibaca', '2024-12-11 09:25:10', 'Pesanan Diproses'),
	(19, 38, 'Lontong Sayur (Jumlah: 2, Harga Total: 14000.00)', 'Belum Dibaca', '2024-12-11 09:25:38', 'Pesanan Dikirim');

-- Dumping structure for table warungbutitin.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `nomor_telepon` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `status` enum('Pesanan Baru','Pesanan Diproses','Pesanan Dikirim','Pesanan Sampai') DEFAULT 'Pesanan Baru',
  `processed_by` enum('User','Admin') NOT NULL,
  `waktu_pemesanan` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table warungbutitin.orders: ~1 rows (approximately)
DELETE FROM `orders`;
INSERT INTO `orders` (`id`, `user_id`, `username`, `nomor_telepon`, `alamat`, `status`, `processed_by`, `waktu_pemesanan`) VALUES
	(38, 5, 'Fitzan Ashari', '081280149631', 'Jln. Pancing, Psr. 4, No. 72\nKel. Mabar Hilir, Kec. Medan Deli', 'Pesanan Dikirim', 'Admin', '2024-12-11 09:05:39');

-- Dumping structure for table warungbutitin.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_ibfk_1` (`order_id`),
  KEY `order_items_ibfk_2` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table warungbutitin.order_items: ~1 rows (approximately)
DELETE FROM `order_items`;
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `jumlah`, `harga`) VALUES
	(24, 38, 9, 2, 7000.00);

-- Dumping structure for table warungbutitin.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table warungbutitin.products: ~10 rows (approximately)
DELETE FROM `products`;
INSERT INTO `products` (`id`, `category_id`, `name`, `price`, `description`, `image`) VALUES
	(7, 1, 'Ayam Bakar', 20000.00, 'Ayam Bakar adalah sajian yang memikat selera di pagi hari dengan cita rasa yang menggoda. Terletak di sudut warung yang ramai, aroma harum dari ayam bakar yang sedang dipanggang langsung menyapa setiap pengunjung yang memasuki tempat ini.\r\n\r\nAyam yang dipilih adalah potongan-potongan daging ayam segar yang telah direndam dalam bumbu rempah khas. Saat dipanggang, sentuhan apik dari bara api memberikan cita rasa khas yang meresap hingga ke dalam daging, menciptakan lapisan kulit yang garing dan bumbu yang meresap.', 'ayam.jpg'),
	(8, 2, 'Jus jeruk', 8000.00, 'Jus Jeruk segar adalah minuman yang memancarkan keceriaan dan kesegaran dari setiap tetesannya. Dibuat dengan menggunakan jeruk segar yang matang, jus ini menawarkan kombinasi cita rasa manis dan asam yang menyegarkan, menciptakan sensasi penyegaran yang tak tertandingi.\r\n\r\nWarna kuning cerah jus jeruk memancarkan kehidupan dan energi, mencerminkan keaslian bahan-bahan alami yang digunakan. Aroma segar jeruk yang terasa begitu kuat begitu botolnya dibuka, seolah membawa kita langsung ke kebun jeruk yang subur.\r\n\r\nSetiap tegukan jus jeruk menghadirkan kenikmatan rasa jeruk yang khas, dengan keasaman yang seimbang dan kelembutan rasa manis yang menggoda lidah. Minuman ini tidak hanya menyegarkan, tetapi juga kaya akan vitamin C dan nutrisi penting lainnya, menjadikannya pilihan sehat yang cocok untuk memulai hari atau mengisi energi di tengah aktivitas.', 'jus.jpg'),
	(9, 1, 'Lontong Sayur', 7000.00, 'Lontong Sayur adalah hidangan khas Indonesia yang menggabungkan kelezatan lontong dan sayuran segar dalam kuah santan yang gurih. Lontong, nasi yang dikukus dalam anyaman daun pisang, memberikan tekstur yang kenyal dan menyerap kuah dengan sempurna, menciptakan perpaduan cita rasa yang unik.\r\n\r\nKuah santan yang kaya rempah, dibuat dari campuran bumbu-bumbu tradisional seperti serai, daun jeruk, jahe, dan kelapa parut, memberikan hidangan ini kelezatan yang khas. Setiap sendok kuah menciptakan sensasi rasa pedas, gurih, dan sedikit manis yang meresap dalam setiap lapisan nasi.\r\n\r\nSayuran segar seperti taoge (tauge), kacang panjang, dan daun melinjo, ditambahkan untuk memberikan kecrispyan dan nutrisi yang seimbang. Seringkali, hidangan ini juga disajikan dengan telur rebus, emping (kerupuk melinjo), dan sambal sebagai pelengkap.', 'Lontong.jpg'),
	(10, 1, 'Serabi', 5000.00, 'Serabi Manis adalah sajian lezat khas Indonesia yang menyajikan kelezatan lempengan dadar berbentuk bulat dengan rasa manis yang menggoda. Serabi ini terbuat dari adonan tepung beras yang dicampur dengan kelapa parut, santan, gula, dan sedikit garam untuk menciptakan tekstur yang lembut dan kenyal.\r\n\r\nLempengan serabi ini kemudian dipanggang atau dikukus hingga matang dengan permukaan yang lembut dan sedikit kecoklatan. Hasilnya adalah kue dadar yang menggoda, dengan aroma wangi kelapa dan santan yang memikat.\r\n\r\nUntuk memberikan sentuhan manis ekstra, serabi biasanya disajikan dengan taburan kelapa parut yang telah dipanggang garing dan gula merah yang meleleh. Paduan antara rasa gurih kelapa, kelembutan adonan serabi, dan manisnya gula merah menciptakan harmoni rasa yang memanjakan lidah.', 'Serabi.jpg'),
	(11, 2, 'Teh Manis', 5000.00, 'Teh Manis adalah minuman yang merakyat dan menghangatkan hati, menyajikan keharmonisan antara teh yang kuat dan sentuhan manis yang memanjakan lidah. Dibuat dari daun teh yang segar atau melalui kantong teh, minuman ini meresap dalam budaya sehari-hari, menjadi teman setia di berbagai kesempatan.\r\n\r\nWarna coklat keemasan dari teh yang dihasilkan memberikan kesan kedamaian dan kehangatan. Aromanya yang khas, terutama jika dihidangkan panas, mengisi ruangan dengan harum yang mengundang untuk duduk dan menikmati setiap tegukan.\r\n\r\nKemudian, sentuhan manis yang ditambahkan, baik dalam bentuk gula pasir atau sirup gula, menambah dimensi rasa yang memuaskan. Tingkat keasaman dan kepahitan teh yang diimbangi oleh rasa manis memberikan keselarasan cita rasa yang membuat minuman ini begitu disukai.\r\n\r\nTeh Manis dapat dinikmati baik dalam keadaan panas maupun dingin, sesuai selera dan kondisi cuaca. Di Indonesia, Teh Manis kerap dihidangkan dengan es, membuatnya sangat cocok sebagai minuman penyegar di tengah teriknya cuaca tropis.', 'Es Teh.jpg'),
	(12, 1, 'Nasi Gurih', 7000.00, '\r\nNasi Gurih adalah hidangan khas Indonesia yang memukau dengan keharuman dan kelezatannya. Dibuat dari nasi yang dimasak dengan tambahan bumbu rempah-rempah yang kaya, hidangan ini menghadirkan cita rasa yang khas dan memikat.\r\n\r\nNasi Gurih biasanya dimasak dengan tambahan santan, daun salam, serai, dan daun pandan, memberikan aroma harum yang menyeluruh. Warna kekuningan atau kecoklatan pada nasi menandakan bahwa rempah-rempah telah meresap dan menciptakan kelezatan yang menggoda.\r\n\r\nTidak hanya itu, Nasi Gurih sering pula diberi tambahan bahan seperti kacang, wijen, atau potongan-potongan telur, memberikan variasi tekstur dan cita rasa yang lebih kompleks. Hidangan ini bisa dihidangkan sebagai sajian utama atau sebagai pendamping hidangan lain.', 'Nasi Gurih.JPG'),
	(13, 1, 'Kue Lupis', 5000.00, 'Kue Lupis adalah sejenis kue tradisional Indonesia yang terbuat dari ketan yang dimasak dan kemudian dibungkus dengan daun pisang. Hidangan ini sering ditemui dalam berbagai acara tradisional, seperti perayaan keagamaan, pesta perkawinan, atau saat perayaan khusus.\r\n\r\nKetan yang digunakan untuk Kue Lupis dimasak hingga menjadi padat dan kenyal. Kemudian, ketan tersebut dibentuk seperti segitiga atau persegi panjang, dan diberi tusukan bambu atau lidi kayu di bagian tengahnya sebelum dibungkus dengan daun pisang yang telah dipanaskan atau dibakar sebentar untuk memberikan aroma khas.\r\n\r\nSelain ketan, Kue Lupis juga sering disajikan dengan pelengkap berupa serutan kelapa parut yang digulung dengan gula kelapa yang cair. Kombinasi antara ketan yang kenyal, rasa gurih kelapa, dan manisnya gula kelapa memberikan cita rasa yang unik dan memikat.', 'Kue Lupis.png'),
	(14, 2, 'Kopi', 6000.00, 'Kopi, minuman yang telah menjadi bagian tak terpisahkan dari kehidupan sehari-hari bagi banyak orang di seluruh dunia. Dibuat dari biji kopi yang disangrai dan diolah, kopi menawarkan pengalaman sensorik yang kaya, mulai dari aroma yang memikat hingga cita rasa yang kompleks.\r\n\r\nAroma kopi yang khas mulai dari harum manis, coklat, hingga aroma buah-buahan, tergantung pada jenis biji kopi, cara sangrai, dan proses penyeduhan. Keharuman kopi yang menyebar dari secangkir baru diseduh menjadi undangan yang sulit untuk ditolak, membangkitkan semangat dan membawa rasa nyaman.\r\n\r\nCita rasa kopi juga mencakup spektrum yang luas, mulai dari yang ringan dan fruity hingga yang penuh dan cenderung pahit. Jenis kopi seperti Arabika dan Robusta memiliki karakteristik rasa yang berbeda, menambahkan dimensi dan keunikan pada minuman ini.', 'kopi.jpg'),
	(15, 1, 'Bakwan', 2000.00, 'Bakwan Nikmat adalah keajaiban cita rasa yang memukau, menggabungkan kelezatan dan keberagaman bahan-bahan segar dalam setiap gigitannya. Dibuat dengan cermat dan penuh perhatian, setiap Bakwan menghadirkan pengalaman rasa yang tak terlupakan.\r\n\r\nTekstur luar Bakwan Nikmat menyuguhkan kecrispyan yang memanjakan lidah, sementara bagian dalamnya menampilkan kelembutan dan kenyal yang mengundang untuk dijelajahi. Rasa gurih yang meresap dari tepung terigu dan kombinasi harmonis dari tauge, wortel, daun bawang, dan daging ayam atau udang memberikan dimensi rasa yang mendalam.\r\n\r\nBumbu yang terpadu dengan sempurna tidak hanya menambah kelezatan, tetapi juga menciptakan sensasi rasa yang seimbang. Garam dan merica yang terukur dengan presisi memperkaya pengalaman rasa, memberikan sentuhan lembut dan pas di setiap gigitan.', 'Bakwan.jpg'),
	(18, 2, 'Es Cokelat', 12000.00, 'Minuman cokelat dingin yang dibuat dari bubuk cokelat premium dan susu segar, cocok untuk pencinta rasa manis.', 'es_cokelat.webp');

-- Dumping structure for table warungbutitin.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `reviews_ibfk_1` (`product_id`),
  KEY `reviews_ibfk_2` (`user_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table warungbutitin.reviews: ~0 rows (approximately)
DELETE FROM `reviews`;

-- Dumping structure for table warungbutitin.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(10) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table warungbutitin.users: ~6 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `username`, `password`, `role`, `remember_token`) VALUES
	(1, 'admin1', '$2y$10$85Vpa4PAHI67oRKsfZ5zTOqQd4/EcClKbMoYuTqsP56uZATQJZ7VW', 'admin', '$2y$10$bKOiyiS/xcDPbbObEDOwyeyT0dmYfDjZkeIXUVM.7iNBGJF6lVCM2'),
	(2, 'admin2', '$2y$10$hHz8vxl5sN55uc0BZ7jEquimOwKLxsj9x1F31uYtExHJ/1rInPaI.', 'admin', NULL),
	(5, 'atha', '$2y$10$mohNSTeHktU1YzlTdS7JwOOaHlLDJhxPQ8PjtDtVeFgXZyo2kK8h2', 'user', '$2y$10$EFv5OIiihTk3iVgxttFOOuHcyBvkDbwxcWGDr7g.em6O0MXjVtPuK'),
	(25, 'rangga', '$2y$10$hoykCNjFicFkkhf/E6XhN.yQb/fAOuFVJUCZGfwHAp.hOFBO9U4/i', 'user', '$2y$10$3ZiFos2bU8xO8O0IQD6jjOQ9UlqsUQsqkJM9.F.ely/i7R6Xr0QsW'),
	(26, 'andre', '$2y$10$44qF/VJ9hbNeuH22I0kOTeuX8iT3x8PcGMZYwWFx9F2v4SPKRo9bK', 'user', '$2y$10$3jcs961nzodY8e6O1dNK2.0GsU5nILVyxbOAQer0hw7.uMzfXLkHe'),
	(27, 'Fitzan', '$2y$10$XyJlNQRVHD0WoLUMMnlYc.YDw6rUYWtP.ZroR1D/3noFi3jpTV9Ue', 'user', '$2y$10$prwy3Eig4lL0jIR36uR/cecVOt0X2Y6k1YrpBQ7MkQWr1Gw9TRroq');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
