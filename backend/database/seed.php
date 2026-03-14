<?php

$host = getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: 'localhost';
$db   = getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'railway';
$user = getenv('MYSQLUSER') ?: getenv('MYSQL_USER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: getenv('MYSQL_PASSWORD') ?: '';
$port = (int)(getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: 3306);

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $count = $pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'products'")->fetchColumn();
    if ($count > 0 && $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn() > 0) {
        echo "Already seeded, skipping.\n";
        exit(0);
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS `categories` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        UNIQUE KEY `unique_name` (`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `products` (
        `id` VARCHAR(100) PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `brand` VARCHAR(100),
        `description` TEXT,
        `in_stock` TINYINT(1) DEFAULT 1,
        `category` VARCHAR(50) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `prices` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `product_id` VARCHAR(100) NOT NULL,
        `amount` DECIMAL(10,2) NOT NULL,
        `currency_label` VARCHAR(10) DEFAULT 'USD',
        `currency_symbol` VARCHAR(5) DEFAULT '\$',
        FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `product_gallery` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `product_id` VARCHAR(100) NOT NULL,
        `image_url` TEXT NOT NULL,
        FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `attributes` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `product_id` VARCHAR(100) NOT NULL,
        `attribute_id` VARCHAR(100) NOT NULL,
        `name` VARCHAR(100) NOT NULL,
        `type` VARCHAR(50) NOT NULL DEFAULT 'text',
        FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `attribute_items` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `attribute_id` INT NOT NULL,
        `item_id` VARCHAR(100) NOT NULL,
        `display_value` VARCHAR(255) NOT NULL,
        `value` VARCHAR(255) NOT NULL,
        FOREIGN KEY (`attribute_id`) REFERENCES `attributes`(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `orders` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `items` TEXT NOT NULL,
        `total_price` DECIMAL(10,2) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $categories = ['all', 'clothes', 'tech'];
    $stmt = $pdo->prepare("INSERT IGNORE INTO `categories` (`name`) VALUES (?)");
    foreach ($categories as $cat) {
        $stmt->execute([$cat]);
    }

    $products = [
        ['huarache-x-stussy-le', 'Nike Air Huarache Le', 'Nike x Stussy', '<p>Great sneakers for everyday use!</p>', 1, 'clothes'],
        ['jacket-canada-goosee', 'Jacket', 'Canada Goose', '<p>Awesome winter jacket</p>', 1, 'clothes'],
        ['ps-5', 'PlayStation 5', 'Sony', '<p>A good gaming console. Plays games of PS4! Enjoy if you can buy it mwahahahaha</p>', 1, 'tech'],
        ['xbox-series-s', 'Xbox Series S 512GB', 'Microsoft', '<div><ul><li><span>Hardware-beschleunigtes Raytracing macht dein Spiel noch realistischer</span></li><li><span>Spiele Games mit bis zu 120 Bilder pro Sekunde</span></li><li><span>Minimiere Ladezeiten mit einer speziell entwickelten 512GB NVMe SSD und wechsle mit Quick Resume nahtlos zwischen mehreren Spielen.</span></li><li><span>Xbox Smart Delivery stellt sicher, dass du die beste Version deines Spiels spielst, egal, auf welcher Konsole du spielst</span></li><li><span>Spiele deine Xbox One-Spiele auf deiner Xbox Series S weiter. Deine Fortschritte, Erfolge und Freundesliste werden automatisch auf das neue System übertragen.</span></li><li><span>Erwecke deine Spiele und Filme mit innovativem 3D Raumklang zum Leben</span></li><li><span>Der brandneue Xbox Wireless Controller zeichnet sich durch höchste Präzision, eine neue Share-Taste und verbesserte Ergonomie aus</span></li><li><span>Ultra-niedrige Latenz verbessert die Reaktionszeit von Controller zum Fernseher</span></li><li><span>Verwende dein Xbox One-Gaming-Zubehör -einschließlich Controller, Headsets und mehr</span></li><li><span>Erweitere deinen Speicher mit der Seagate 1 TB-Erweiterungskarte für Xbox Series X (separat erhältlich) und streame 4K-Videos von Disney+, Netflix, Amazon, Microsoft Movies &amp; TV und mehr</span></li></ul></div>', 0, 'tech'],
        ['apple-imac-2021', 'iMac 2021', 'Apple', 'The new iMac!', 1, 'tech'],
        ['apple-iphone-12-pro', 'iPhone 12 Pro', 'Apple', 'This is iPhone 12. Nothing else to say.', 1, 'tech'],
        ['apple-airpods-pro', 'AirPods Pro', 'Apple', '<h3>Magic like you\'ve never heard</h3><p>AirPods Pro have been designed to deliver Active Noise Cancellation for immersive sound, Transparency mode so you can hear your surroundings, and a customizable fit for all-day comfort.</p>', 0, 'tech'],
        ['apple-airtag', 'AirTag', 'Apple', '<h1>Lose your knack for losing things.</h1><p>AirTag is an easy way to keep track of your stuff. Attach one to your keys, slip another one in your backpack. And just like that, they\'re on your radar in the Find My app.</p>', 1, 'tech'],
    ];
    $stmt = $pdo->prepare("INSERT IGNORE INTO `products` (`id`, `name`, `brand`, `description`, `in_stock`, `category`) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($products as $p) {
        $stmt->execute($p);
    }

    $prices = [
        ['huarache-x-stussy-le', 144.69],
        ['jacket-canada-goosee', 518.47],
        ['ps-5', 844.02],
        ['xbox-series-s', 333.99],
        ['apple-imac-2021', 1688.03],
        ['apple-iphone-12-pro', 1000.76],
        ['apple-airpods-pro', 300.23],
        ['apple-airtag', 120.57],
    ];
    $stmt = $pdo->prepare("INSERT IGNORE INTO `prices` (`product_id`, `amount`, `currency_label`, `currency_symbol`) VALUES (?, ?, 'USD', '\$')");
    foreach ($prices as $p) {
        $stmt->execute($p);
    }

    $gallery = [
        ['huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_2_720x.jpg?v=1612816087'],
        ['huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_1_720x.jpg?v=1612816087'],
        ['huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_3_720x.jpg?v=1612816087'],
        ['huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_5_720x.jpg?v=1612816087'],
        ['huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_4_720x.jpg?v=1612816087'],
        ['jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016105/product-image/2409L_61.jpg'],
        ['jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016107/product-image/2409L_61_a.jpg'],
        ['jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016108/product-image/2409L_61_b.jpg'],
        ['jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016109/product-image/2409L_61_c.jpg'],
        ['jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016110/product-image/2409L_61_d.jpg'],
        ['jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_1333,c_scale,f_auto,q_auto:best/v1634058169/product-image/2409L_61_o.png'],
        ['jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_1333,c_scale,f_auto,q_auto:best/v1634058159/product-image/2409L_61_p.png'],
        ['ps-5', 'https://images-na.ssl-images-amazon.com/images/I/510VSJ9mWDL._SL1262_.jpg'],
        ['ps-5', 'https://images-na.ssl-images-amazon.com/images/I/610%2B69ZsKCL._SL1500_.jpg'],
        ['ps-5', 'https://images-na.ssl-images-amazon.com/images/I/51iPoFwQT3L._SL1230_.jpg'],
        ['ps-5', 'https://images-na.ssl-images-amazon.com/images/I/61qbqFcvoNL._SL1500_.jpg'],
        ['ps-5', 'https://images-na.ssl-images-amazon.com/images/I/51HCjA3rqYL._SL1230_.jpg'],
        ['xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/71vPCX0bS-L._SL1500_.jpg'],
        ['xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/71q7JTbRTpL._SL1500_.jpg'],
        ['xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/71iQ4HGHtsL._SL1500_.jpg'],
        ['xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/61IYrCrBzxL._SL1500_.jpg'],
        ['xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/61RnXmpAmIL._SL1500_.jpg'],
        ['apple-imac-2021', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/imac-24-blue-selection-hero-202104?wid=904&hei=840&fmt=jpeg&qlt=80&.v=1617492405000'],
        ['apple-iphone-12-pro', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-12-pro-family-hero?wid=940&hei=1112&fmt=jpeg&qlt=80&.v=1604021663000'],
        ['apple-airpods-pro', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MWP22?wid=572&hei=572&fmt=jpeg&qlt=95&.v=1591634795000'],
        ['apple-airtag', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/airtag-double-select-202104?wid=445&hei=370&fmt=jpeg&qlt=95&.v=1617761672000'],
    ];
    $stmt = $pdo->prepare("INSERT IGNORE INTO `product_gallery` (`product_id`, `image_url`) VALUES (?, ?)");
    foreach ($gallery as $g) {
        $stmt->execute($g);
    }

    $attributes = [
        ['huarache-x-stussy-le', 'Size', 'Size', 'text'],
        ['jacket-canada-goosee', 'Size', 'Size', 'text'],
        ['ps-5', 'Color', 'Color', 'swatch'],
        ['ps-5', 'Capacity', 'Capacity', 'text'],
        ['xbox-series-s', 'Color', 'Color', 'swatch'],
        ['xbox-series-s', 'Capacity', 'Capacity', 'text'],
        ['apple-imac-2021', 'Capacity', 'Capacity', 'text'],
        ['apple-imac-2021', 'With USB 3 ports', 'With USB 3 ports', 'text'],
        ['apple-imac-2021', 'Touch ID in keyboard', 'Touch ID in keyboard', 'text'],
        ['apple-iphone-12-pro', 'Capacity', 'Capacity', 'text'],
        ['apple-iphone-12-pro', 'Color', 'Color', 'swatch'],
    ];
    $stmt = $pdo->prepare("INSERT IGNORE INTO `attributes` (`product_id`, `attribute_id`, `name`, `type`) VALUES (?, ?, ?, ?)");
    foreach ($attributes as $a) {
        $stmt->execute($a);
    }

    $attrRows = $pdo->query("SELECT id, product_id, name FROM attributes ORDER BY id")->fetchAll();
    $attrMap = [];
    foreach ($attrRows as $row) {
        $attrMap[$row['product_id'] . '|' . $row['name']] = $row['id'];
    }

    $attributeItems = [
        ['huarache-x-stussy-le|Size', [['40','40'],['41','41'],['42','42'],['43','43']]],
        ['jacket-canada-goosee|Size', [['Small','S'],['Medium','M'],['Large','L'],['Extra Large','XL']]],
        ['ps-5|Color', [['Green','#44FF03'],['Cyan','#03FFF7'],['Blue','#030BFF'],['Black','#000000'],['White','#FFFFFF']]],
        ['ps-5|Capacity', [['512G','512G'],['1T','1T']]],
        ['xbox-series-s|Color', [['Green','#44FF03'],['Cyan','#03FFF7'],['Blue','#030BFF'],['Black','#000000'],['White','#FFFFFF']]],
        ['xbox-series-s|Capacity', [['512G','512G'],['1T','1T']]],
        ['apple-imac-2021|Capacity', [['256GB','256GB'],['512GB','512GB']]],
        ['apple-imac-2021|With USB 3 ports', [['Yes','Yes'],['No','No']]],
        ['apple-imac-2021|Touch ID in keyboard', [['Yes','Yes'],['No','No']]],
        ['apple-iphone-12-pro|Capacity', [['512G','512G'],['1T','1T']]],
        ['apple-iphone-12-pro|Color', [['Green','#44FF03'],['Cyan','#03FFF7'],['Blue','#030BFF'],['Black','#000000'],['White','#FFFFFF']]],
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO `attribute_items` (`attribute_id`, `item_id`, `display_value`, `value`) VALUES (?, ?, ?, ?)");
    foreach ($attributeItems as [$key, $items]) {
        if (!isset($attrMap[$key])) continue;
        $attrId = $attrMap[$key];
        foreach ($items as [$display, $value]) {
            $stmt->execute([$attrId, $display, $display, $value]);
        }
    }

    echo "Schema and seed imported successfully.\n";
} catch (Exception $e) {
    echo "Seed failed: " . $e->getMessage() . "\n";
}
