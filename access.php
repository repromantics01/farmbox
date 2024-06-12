<?php
    $servername = "localhost";
    $username = "root";
    $password = "";

    $db_name = "farmbox";

    function create_table($mysqli, $command) {
        if ($mysqli->query($command) === FALSE) {
            $db_state = 0;
            echo "Error creating table: " . $conn->error;
        }
    }

    $db_state = 1;
    $mysqli = new mysqli($servername, $username, $password);
    if ($db_state == 1) {
        $mysqli = new mysqli($servername, $username, $password);
        if ($mysqli->connect_error) {
        $db_state = 0;
        echo "Connection failed: " . $mysqli->connect_error;
    }
    # Create new Database
    $command = "CREATE DATABASE IF NOT EXISTS $db_name;";
        if ($mysqli->query($command) === FALSE) {
            $db_state = 0;
            echo "Error creating database: " . $mysqli->error;
        }
    }

    if ($db_state == 1) {
        $mysqli = new mysqli($servername, $username, $password, $db_name);
        $command = "CREATE TABLE IF NOT EXISTS `user` (" .
        "`user_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
        "`first_name` VARCHAR(128) NOT NULL, " .
        "`middle_name` VARCHAR(128), " .
        "`last_name` VARCHAR(128) NOT NULL, " .
        "`name_suffix` VARCHAR(128), " .
        "`username` VARCHAR(16), " .
        "`password` VARCHAR(128), " .
        "`email_address` VARCHAR(255), " .
        "`mobile_number` VARCHAR(11), " .
        "`date_of_birth` DATE, " .
        "`full_address` VARCHAR(512), " .
        "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP);";
       
        create_table($mysqli, $command);
        $command = "CREATE TABLE IF NOT EXISTS `subscription_plan` (" .
            "`subscription_plan_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
            "`subscription_name` VARCHAR(128), " .
            "`subscription_description` VARCHAR(256), " .
            "`price_per_box` DOUBLE, " .
            "`is_archived` BOOLEAN, " .
            "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP);";

         create_table($mysqli, $command);
        $command = "CREATE TABLE IF NOT EXISTS `subscription` (" .
            "`subscription_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
            "`user_id` INT UNSIGNED, " .
            "`subscription_plan_id` INT UNSIGNED, " .
            "`subscription_duration` DATE, " .
            "`subsription_start_date` DATE, " .
            "`subscription_end_date` DATE, " .
            "`subscription_status` INT, " .
            "`payment_type` VARCHAR(512), " .
            "`billing_amount` DOUBLE," .
            "`billing_address` VARCHAR(512)," .
            "`delivery_address` VARCHAR(512)," .
            "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ," .
            "CONSTRAINT fk_user_subscription FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`), " .
            "CONSTRAINT fk_subscription_plan FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plan`(`subscription_plan_id`));";

        create_table($mysqli, $command);
        $command = "CREATE TABLE IF NOT EXISTS `supplier` (" .
            "`supplier_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
            "`user_id` INT UNSIGNED NOT NULL, " .
            "`supplier_name` VARCHAR(255), " .
            "`supplier_business_address` VARCHAR(512), " .
            "`supplier_contact_number` VARCHAR(11), " .
            "`supplier_email_address` VARCHAR(255), " .
            "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
            "CONSTRAINT fk_user_supplier FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`));";

        create_table($mysqli, $command);
        $command = "CREATE TABLE IF NOT EXISTS `sales` (" .
            "`sales_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
            "`subscription_id` INT UNSIGNED NOT NULL, " .
            "`total_amount_paid` DOUBLE, " .
            "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
            "CONSTRAINT fk_subscription_sales FOREIGN KEY (`subscription_id`) REFERENCES `subscription`(`subscription_id`));";

        create_table($mysqli, $command);
        $command = "CREATE TABLE IF NOT EXISTS `unit_of_measure` (" .
            "`uom_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
            "`uom_name` VARCHAR(128), " .
            "`uom_short_code` VARCHAR(10), " .
            "`uom_description` VARCHAR(128), " .
            "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP);";

        create_table($mysqli, $command);
        $command = "CREATE TABLE IF NOT EXISTS `marketplace` (" .
            "`marketplace_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
            "`supplier_id` INT UNSIGNED NOT NULL, " .
            "`product_id` INT UNSIGNED NOT NULL, " .
            "`uom_id` INT UNSIGNED NOT NULL, " .
            "`quantity_available` FLOAT(2), " .
            "`price_per_unit` VARCHAR(512), " .
            "`supplier_contact_number` DOUBLE, " .
            "`date_of_harvest`DATETIME, " .
            "`date_of_delivery` DATETIME, " .
            "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
            "CONSTRAINT fk_supplier_marketplace FOREIGN KEY (`supplier_id`) REFERENCES `supplier`(`supplier_id`)," .
            "CONSTRAINT fk_product_marketplace FOREIGN KEY (`product_id`) REFERENCES `product`(`product_id`), " .
            "CONSTRAINT fk_unit_of_measure_marketplace FOREIGN KEY (`uom_id`) REFERENCES `unit_of_measure`(`uom_id`));";

        create_table($mysqli, $command);
        $command = "CREATE TABLE IF NOT EXISTS `product` (" .
            "`product_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
            "`category_id` INT UNSIGNED NOT NULL, " .
            "`product_name` VARCHAR(256), " .
            "`product_description` VARCHAR(512), " .
            "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , " .
            "CONSTRAINT fk_category_product FOREIGN KEY (`category_id`) REFERENCES `category`(`category_id`));";

        create_table($mysqli, $command);
        $command = "CREATE TABLE IF NOT EXISTS category (" .
            "`category_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
            "`category_name` VARCHAR(256), " .
            "`category_description` VARCHAR(512), " .
            "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP);";

        create_table($mysqli, $command);
        $command = "CREATE TABLE IF NOT EXISTS `inventory` (" .
            "`inventory_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
            "`station_id` INT UNSIGNED NOT NULL, " .
            "`marketplace_id` INT UNSIGNED NOT NULL, " .
            "`quantity_recieved` FLOAT, " .
            "`price_per_unit` DOUBLE, " .
            "`total_payment` DOUBLE, " .
            "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
            "CONSTRAINT fk_station_inventory FOREIGN KEY (`station_id`) REFERENCES `station`(`station_id`)," .
            "CONSTRAINT fk_marketplace_inventory FOREIGN KEY (`marketplace_id`) REFERENCES `marketplace`(`marketplace_id`));";

        create_table($mysqli, $command);
        $command = "CREATE TABLE IF NOT EXISTS `order` (" .
            "`order_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
            "`subscription_id` INT UNSIGNED NOT NULL, " .
            "`date_of_packing` DATETIME, " .
            "`date_of_fulfillment` DATETIME, " .
            "`order_status` INT NOT NULL, " .
            "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
            "CONSTRAINT fk_subscription_order FOREIGN KEY (`subscription_id`) REFERENCES `subscription`(`subscription_id`));";

        create_table($mysqli, $command);
        $command = "CREATE TABLE IF NOT EXISTS `station` (" .
            "`station_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
            "`station_name` VARCHAR(128), " .
            "`station_business_address` VARCHAR(512), " .
            "`station_contact_number` VARCHAR(11), " .
            "`station_email_address` VARCHAR(255), " .
            "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP);";     

        create_table($mysqli, $command);
        $command = "CREATE TABLE IF NOT EXISTS `order_item` (" .
            "`order_item_id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
            "`order_id` INT UNSIGNED NOT NULL, " .
            "`inventory_id` INT UNSIGNED NOT NULL, " .
            "`uom_id` INT UNSIGNED NOT NULL, " .
            "`quantity_packed` FLOAT(2), " .
            "`price_per_unit` FLOAT(2), " .
            "`order_subtotal` DOUBLE, " .
            "`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
            "CONSTRAINT fk_order_order_item FOREIGN KEY (`order_id`) REFERENCES `order`(`order_id`)," .
            "CONSTRAINT fk_inventory_order_item FOREIGN KEY (`inventory_id`) REFERENCES `inventory`(`inventory_id`)," .
            "CONSTRAINT fk_unit_of_measure_order_item FOREIGN KEY (`uom_id`) REFERENCES `unit_of_measure`(`uom_id`));"; 
    }
    $mysqli->close();
?>