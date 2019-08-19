CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `parent_id` int(11) NOT NULL,
  `level` tinyint(1) NOT NULL,
  `create_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `original_title` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `source` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `time` datetime NOT NULL,
  `description` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `content` text COLLATE utf8mb4_bin,
  `content_text` text COLLATE utf8mb4_bin,
  `category_id` int(11) NOT NULL,
  `create_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;