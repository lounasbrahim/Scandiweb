
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `products` (
    `id` VARCHAR(100) PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `brand` VARCHAR(100),
    `description` TEXT,
    `in_stock` TINYINT(1) DEFAULT 1,
    `category` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `prices` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `currency_label` VARCHAR(10) DEFAULT 'USD',
    `currency_symbol` VARCHAR(5) DEFAULT '$',
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `product_gallery` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` VARCHAR(100) NOT NULL,
    `image_url` TEXT NOT NULL,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `attributes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` VARCHAR(100) NOT NULL,
    `attribute_id` VARCHAR(100) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `type` VARCHAR(50) NOT NULL DEFAULT 'text',
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `attribute_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `attribute_id` INT NOT NULL,
    `item_id` VARCHAR(100) NOT NULL,
    `display_value` VARCHAR(255) NOT NULL,
    `value` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`attribute_id`) REFERENCES `attributes`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `items` TEXT NOT NULL,
    `total_price` DECIMAL(10,2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `categories` (`name`) VALUES
('all'),
('clothes'),
('tech');

INSERT IGNORE INTO `products` (`id`, `name`, `brand`, `description`, `in_stock`, `category`) VALUES
('huarache-x-stussy-le', 'Nike Air Huarache Le', 'Nike x Stussy', '<p>Great sneakers for everyday use!</p>', 1, 'clothes'),
('jacket-canada-goosee', 'Jacket', 'Canada Goose', '<p>Awesome winter jacket</p>', 1, 'clothes'),
('ps-5', 'PlayStation 5', 'Sony', '<p>A good gaming console. Plays games of PS4! Enjoy if you can buy it mwahahahaha</p>', 1, 'tech'),
('xbox-series-s', 'Xbox Series S 512GB', 'Microsoft', '<div><ul><li><span>Hardware-beschleunigtes Raytracing macht dein Spiel noch realistischer</span></li><li><span>Spiele Games mit bis zu 120 Bilder pro Sekunde</span></li><li><span>Minimiere Ladezeiten mit einer speziell entwickelten 512GB NVMe SSD und wechsle mit Quick Resume nahtlos zwischen mehreren Spielen.</span></li><li><span>Xbox Smart Delivery stellt sicher, dass du die beste Version deines Spiels spielst, egal, auf welcher Konsole du spielst</span></li><li><span>Spiele deine Xbox One-Spiele auf deiner Xbox Series S weiter. Deine Fortschritte, Erfolge und Freundesliste werden automatisch auf das neue System übertragen.</span></li><li><span>Erwecke deine Spiele und Filme mit innovativem 3D Raumklang zum Leben</span></li><li><span>Der brandneue Xbox Wireless Controller zeichnet sich durch höchste Präzision, eine neue Share-Taste und verbesserte Ergonomie aus</span></li><li><span>Ultra-niedrige Latenz verbessert die Reaktionszeit von Controller zum Fernseher</span></li><li><span>Verwende dein Xbox One-Gaming-Zubehör -einschließlich Controller, Headsets und mehr</span></li><li><span>Erweitere deinen Speicher mit der Seagate 1 TB-Erweiterungskarte für Xbox Series X (separat erhältlich) und streame 4K-Videos von Disney+, Netflix, Amazon, Microsoft Movies &amp; TV und mehr</span></li></ul></div>', 0, 'tech'),
('apple-imac-2021', 'iMac 2021', 'Apple', 'The new iMac!', 1, 'tech'),
('apple-iphone-12-pro', 'iPhone 12 Pro', 'Apple', 'This is iPhone 12. Nothing else to say.', 1, 'tech'),
('apple-airpods-pro', 'AirPods Pro', 'Apple', '<h3>Magic like you\'ve never heard</h3><p>AirPods Pro have been designed to deliver Active Noise Cancellation for immersive sound, Transparency mode so you can hear your surroundings, and a customizable fit for all-day comfort.</p><h3>Active Noise Cancellation</h3><p>Incredibly light noise-cancelling headphones, AirPods Pro block out your environment so you can focus on what you\'re listening to.</p><h3>Transparency mode</h3><p>Switch to Transparency mode and AirPods Pro let the outside sound in, allowing you to hear and connect to your surroundings.</p><h3>All-new design</h3><p>AirPods Pro offer a more customizable fit with three sizes of flexible silicone tips to choose from.</p><h3>Amazing audio quality</h3><p>A custom-built high-excursion, low-distortion driver delivers powerful bass.</p><h3>Even more magical</h3><p>The Apple-designed H1 chip delivers incredibly low audio latency.</p>', 0, 'tech'),
('apple-airtag', 'AirTag', 'Apple', '<h1>Lose your knack for losing things.</h1><p>AirTag is an easy way to keep track of your stuff. Attach one to your keys, slip another one in your backpack. And just like that, they\'re on your radar in the Find My app. AirTag has your back.</p>', 1, 'tech');

INSERT IGNORE INTO `prices` (`product_id`, `amount`, `currency_label`, `currency_symbol`) VALUES
('huarache-x-stussy-le', 144.69, 'USD', '$'),
('jacket-canada-goosee', 518.47, 'USD', '$'),
('ps-5', 844.02, 'USD', '$'),
('xbox-series-s', 333.99, 'USD', '$'),
('apple-imac-2021', 1688.03, 'USD', '$'),
('apple-iphone-12-pro', 1000.76, 'USD', '$'),
('apple-airpods-pro', 300.23, 'USD', '$'),
('apple-airtag', 120.57, 'USD', '$');

INSERT IGNORE INTO `product_gallery` (`product_id`, `image_url`) VALUES
('huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_2_720x.jpg?v=1612816087'),
('huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_1_720x.jpg?v=1612816087'),
('huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_3_720x.jpg?v=1612816087'),
('huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_5_720x.jpg?v=1612816087'),
('huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_4_720x.jpg?v=1612816087'),
('jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016105/product-image/2409L_61.jpg'),
('jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016107/product-image/2409L_61_a.jpg'),
('jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016108/product-image/2409L_61_b.jpg'),
('jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016109/product-image/2409L_61_c.jpg'),
('jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016110/product-image/2409L_61_d.jpg'),
('jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_1333,c_scale,f_auto,q_auto:best/v1634058169/product-image/2409L_61_o.png'),
('jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_1333,c_scale,f_auto,q_auto:best/v1634058159/product-image/2409L_61_p.png'),
('ps-5', 'https://images-na.ssl-images-amazon.com/images/I/510VSJ9mWDL._SL1262_.jpg'),
('ps-5', 'https://images-na.ssl-images-amazon.com/images/I/610%2B69ZsKCL._SL1500_.jpg'),
('ps-5', 'https://images-na.ssl-images-amazon.com/images/I/51iPoFwQT3L._SL1230_.jpg'),
('ps-5', 'https://images-na.ssl-images-amazon.com/images/I/61qbqFcvoNL._SL1500_.jpg'),
('ps-5', 'https://images-na.ssl-images-amazon.com/images/I/51HCjA3rqYL._SL1230_.jpg'),
('xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/71vPCX0bS-L._SL1500_.jpg'),
('xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/71q7JTbRTpL._SL1500_.jpg'),
('xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/71iQ4HGHtsL._SL1500_.jpg'),
('xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/61IYrCrBzxL._SL1500_.jpg'),
('xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/61RnXmpAmIL._SL1500_.jpg'),
('apple-imac-2021', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/imac-24-blue-selection-hero-202104?wid=904&hei=840&fmt=jpeg&qlt=80&.v=1617492405000'),
('apple-iphone-12-pro', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-12-pro-family-hero?wid=940&hei=1112&fmt=jpeg&qlt=80&.v=1604021663000'),
('apple-airpods-pro', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MWP22?wid=572&hei=572&fmt=jpeg&qlt=95&.v=1591634795000'),
('apple-airtag', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/airtag-double-select-202104?wid=445&hei=370&fmt=jpeg&qlt=95&.v=1617761672000');

INSERT IGNORE INTO `attributes` (`product_id`, `attribute_id`, `name`, `type`) VALUES
('huarache-x-stussy-le', 'Size', 'Size', 'text'),
('jacket-canada-goosee', 'Size', 'Size', 'text'),
('ps-5', 'Color', 'Color', 'swatch'),
('ps-5', 'Capacity', 'Capacity', 'text'),
('xbox-series-s', 'Color', 'Color', 'swatch'),
('xbox-series-s', 'Capacity', 'Capacity', 'text'),
('apple-imac-2021', 'Capacity', 'Capacity', 'text'),
('apple-imac-2021', 'With USB 3 ports', 'With USB 3 ports', 'text'),
('apple-imac-2021', 'Touch ID in keyboard', 'Touch ID in keyboard', 'text'),
('apple-iphone-12-pro', 'Capacity', 'Capacity', 'text'),
('apple-iphone-12-pro', 'Color', 'Color', 'swatch');

INSERT IGNORE INTO `attribute_items` (`attribute_id`, `item_id`, `display_value`, `value`) VALUES
(1, '40', '40', '40'),
(1, '41', '41', '41'),
(1, '42', '42', '42'),
(1, '43', '43', '43'),
(2, 'Small', 'Small', 'S'),
(2, 'Medium', 'Medium', 'M'),
(2, 'Large', 'Large', 'L'),
(2, 'Extra Large', 'Extra Large', 'XL'),
(3, 'Green', 'Green', '#44FF03'),
(3, 'Cyan', 'Cyan', '#03FFF7'),
(3, 'Blue', 'Blue', '#030BFF'),
(3, 'Black', 'Black', '#000000'),
(3, 'White', 'White', '#FFFFFF'),
(4, '512G', '512G', '512G'),
(4, '1T', '1T', '1T'),
(5, 'Green', 'Green', '#44FF03'),
(5, 'Cyan', 'Cyan', '#03FFF7'),
(5, 'Blue', 'Blue', '#030BFF'),
(5, 'Black', 'Black', '#000000'),
(5, 'White', 'White', '#FFFFFF'),
(6, '512G', '512G', '512G'),
(6, '1T', '1T', '1T'),
(7, '256GB', '256GB', '256GB'),
(7, '512GB', '512GB', '512GB'),
(8, 'Yes', 'Yes', 'Yes'),
(8, 'No', 'No', 'No'),
(9, 'Yes', 'Yes', 'Yes'),
(9, 'No', 'No', 'No'),
(10, '512G', '512G', '512G'),
(10, '1T', '1T', '1T'),
(11, 'Green', 'Green', '#44FF03'),
(11, 'Cyan', 'Cyan', '#03FFF7'),
(11, 'Blue', 'Blue', '#030BFF'),
(11, 'Black', 'Black', '#000000'),
(11, 'White', 'White', '#FFFFFF');
