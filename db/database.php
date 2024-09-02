<?php
class DatabaseHelper
{
    public mysqli $db;

    public function __construct($dbname, $servername = "localhost", $username = "root", $password = "", $port = 3306)
    {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function login(string $email, string $password)
    {
        $user = $this->getLoginInfo($email);

        if ($user && $user['password'] == $password) {
            return true;
        }

        return false;
    }

    public function getLoginInfo($email)
    {
        $query = "SELECT email, password
                    FROM EMPLOYEES
                    WHERE EMPLOYEES.email = ?
                    LIMIT 1;";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0)
            return $result->fetch_all(MYSQLI_ASSOC)[0]; // Return the first element of the array

        return false;
    }

    public function getEmployeeEssentialInfo($emailOrId)
    {
        $query = "SELECT employeeId, name, surname, email, isAdmin, isWaiter, isStorekeeper, isKitchenStaff
                    FROM EMPLOYEES
                    WHERE EMPLOYEES.email = ?
                        OR EMPLOYEES.employeeId = ?
                    LIMIT 1;";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $emailOrId, $emailOrId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC)[0]; // Return the first element of the array
    }

    public function getAllMenuProducts()
    {
        $query = "SELECT * FROM MENU_PRODUCTS ORDER BY name";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRecipe(int $menuProdId)
    {
        $query = "SELECT name as ingredientName, portionSize
                    FROM ingredients left join stocked_up_products as stocked
                        ON ingredientId = stocked.prodId
                    WHERE ingredients.menuProdId = ? ;";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $menuProdId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllStockedProducts()
    {
        $query = "SELECT * FROM STOCKED_UP_PRODUCTS ORDER BY name";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addNewTable(string $name, int $numPeople, string $creationTime = null)
    {
        // If no creation time is provided, set it to the current datetime
        if ($creationTime === null) {
            $creationTime = date('Y-m-d H:i:s'); // Current date and time in 'YYYY-MM-DD HH:MM:SS' format
        }

        $query = "INSERT INTO TABLES (creationTimestamp, name, seats) VALUES (?, ?, ?);";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param('ssi', $creationTime, $name, $numPeople);
        return $stmt->execute();
    }

    public function getTable(int $tableId)
    {
        $query = "SELECT * FROM TABLES WHERE tableId = ? LIMIT 1";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param("i", $tableId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getTables(string $date = null)
    {
        // If no date is provided, use today's date in 'Y-m-d' format
        if ($date === null) {
            $date = date('Y-m-d');
        }

        $query = "SELECT 
                        *,
                        ROW_NUMBER() OVER (PARTITION BY DATE(creationTimestamp) ORDER BY creationTimestamp) as numOfDay
                    FROM 
                        TABLES 
                    WHERE 
                        TABLES.creationTimestamp LIKE ? 
                    ORDER BY 
                        TABLES.creationTimestamp DESC;";
        $stmt = $this->db->prepare($query);

        $date .= "%"; // Create pattern
        $stmt->bind_param("s", $date);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getReservationsOnDay(string $date = null)
    {
        // If no date is provided, use today's date in 'Y-m-d' format
        if ($date === null) {
            $date = date('Y-m-d');
        }

        $query = "
            SELECT r.*,
            t.name as tableName
            FROM RESERVATIONS r
            LEFT JOIN TABLES t ON r.tableId = t.tableId
            WHERE r.dateAndTime LIKE ?
            ORDER BY r.dateAndTime;
        ";
        $stmt = $this->db->prepare($query);

        $date .= "%"; // Create pattern
        $stmt->bind_param("s", $date);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCustomerOrders(bool $inPreparation, bool $delivered)
    {
        $query = "SELECT 
                        TABLES.name AS tableName,
                        TABLES.tableId,
                        EMPLOYEES.name AS waiterName, 
                        EMPLOYEES.surname AS waiterSurname, 
                        ord.timestamp, 
                        ord.inPreparation, 
                        ord.delivered,
                        ord.orderNum,
                        ROW_NUMBER() OVER (PARTITION BY DATE(ord.timestamp) ORDER BY ord.timestamp) as numOfDay
                    FROM 
                        CUSTOMER_ORDERS AS ord
                        LEFT JOIN TABLES ON ord.tableId = TABLES.tableId
                        LEFT JOIN EMPLOYEES ON ord.waiterId = EMPLOYEES.employeeId
                    WHERE 
                        ord.timestamp LIKE ?
                        AND ord.inPreparation = ?
                        AND ord.delivered = ?
                    ORDER BY 
                        ord.timestamp;";

        $stmt = $this->db->prepare($query);

        $today = date('Y-m-d');
        $today .= "%";

        $stmt->bind_param("sii", $today, $inPreparation, $delivered);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function createNewCustomerOrder(int $tableId, int $waiterId)
    {
        // Obtain the order number (corresponds to the number of order in the table + 1)
        $query = "
                SELECT IFNULL(MAX(orderNum), 0) + 1 AS next_order_num
                FROM CUSTOMER_ORDERS
                WHERE tableId = ?
                FOR UPDATE";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $tableId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $next_order_num = $row['next_order_num'];
        $stmt->close();

        // Create new customer order
        $query = "
                INSERT INTO CUSTOMER_ORDERS (tableId, orderNum, waiterId)
                VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iii", $tableId, $next_order_num, $waiterId);
        $stmt->execute();

        return $next_order_num;
    }


    private function calculateFinalPrice($menuProdId, array $variationIds)
    {
        $query = "
            SELECT price 
            FROM MENU_PRODUCTS 
            WHERE prodId = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $menuProdId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $basePrice = $row['price'];

        $variationPrice = 0;
        if (!empty($variationIds)) {
            $placeholders = implode(',', array_fill(0, count($variationIds), '?'));
            $query = "
                SELECT SUM(additionalPrice) as total_variation_price
                FROM VARIATIONS
                WHERE variationId IN ($placeholders)
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($variationIds)), ...$variationIds);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $variationPrice = $row['total_variation_price'];
        }

        return $basePrice + $variationPrice;
    }

    public function addProductToCustomerOrder($menuProdId, $tableId, $orderNum, $quantity, array $variationIds)
    {
        $finalPrice = $this->calculateFinalPrice($menuProdId, $variationIds); // Sum menu product's price to the variations' prices
        // Step 1: Check if the product already exists in the table with the same variations
        $existingProductQuery = "
                SELECT pit.orderedProdId
                FROM PRODUCTS_IN_TABLE pit
                LEFT JOIN ADDITIONAL_REQUESTS ar ON pit.orderedProdId = ar.orderedProdId 
                    AND pit.menuProdId = ar.menuProdId 
                    AND pit.tableId = ar.tableId
                WHERE pit.menuProdId = ? 
                    AND pit.tableId = ?
                    AND pit.hasVariation = ?
                GROUP BY pit.orderedProdId
                HAVING 
                    COUNT(DISTINCT ar.variationId) = ? 
                    AND SUM(CASE WHEN ar.variationId IN (0" .
            (!empty($variationIds) ? ',' . implode(',', array_fill(0, count($variationIds), '?')) : '') .
            ") THEN 1 ELSE 0 END) = ?";
        $numVariations = count($variationIds);
        $stmt = $this->db->prepare($existingProductQuery);
        $hasVariation = !empty($variationIds);
        $params = array_merge([$menuProdId, $tableId, $hasVariation, $numVariations], $variationIds, [$numVariations]);
        $stmt->bind_param(
            str_repeat('i', count($params)),
            ...$params
        );

        $stmt->execute();
        $result = $stmt->get_result(); // Id product somebody already ordered it in the same table or else empty array;

        if ($result->num_rows > 0) {
            // Product exists, update quantity
            $row = $result->fetch_assoc();
            $orderedProdId = $row['orderedProdId'];

            $updateQuery = "
                    UPDATE PRODUCTS_IN_TABLE 
                    SET quantity = quantity + ?
                    WHERE orderedProdId = ? 
                        AND menuProdId = ? 
                        AND tableId = ?
                ";

            $stmt = $this->db->prepare($updateQuery);
            $stmt->bind_param("iiii", $quantity, $orderedProdId, $menuProdId, $tableId);
            $stmt->execute();
        } else {
            // Product doesn't exist, insert new
            $insertProductQuery = "
                    INSERT INTO PRODUCTS_IN_TABLE (menuProdId, tableId, quantity, finalPrice, hasVariation, numPaid)
                    VALUES (?, ?, ?, ?, ?, 0)
                ";
            $stmt = $this->db->prepare($insertProductQuery);
            $stmt->bind_param("iiidi", $menuProdId, $tableId, $quantity, $finalPrice, $hasVariation);
            $stmt->execute();
            $orderedProdId = $this->db->insert_id;
            // Insert variations if any
            if (!empty($variationIds)) {
                $insertVariationQuery = "
                        INSERT INTO ADDITIONAL_REQUESTS (variationId, tableId, orderedProdId, menuProdId)
                        VALUES (?, ?, ?, ?)
                    ";

                $stmt = $this->db->prepare($insertVariationQuery);
                foreach ($variationIds as $variationId) {
                    $stmt->bind_param("iiii", $variationId, $tableId, $orderedProdId, $menuProdId);
                    $stmt->execute();
                }
            }
        }

        // Step 2: Update or insert into ORDINATIONS
        $ordinationQuery = "
                INSERT INTO ORDINATIONS (orderNum, menuProdId, tableId, orderedProdId, quantity)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
            ";

        $stmt = $this->db->prepare($ordinationQuery);
        $stmt->bind_param("iiiii", $orderNum, $menuProdId, $tableId, $orderedProdId, $quantity);
        $stmt->execute();
    }

    public function searchVariations(string $variationName)
    {
        $stmt = $this->db->prepare("SELECT * FROM VARIATIONS WHERE additionalRequest LIKE ?");

        $namePattern = $variationName . "%";
        $stmt->bind_param("s", $namePattern);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function searchMenuProduct($prodName)
    {
        $startsWithPattern = $prodName . "%";
        $containsPattern = "%" . $prodName . "%";

        $query = "
            (SELECT * FROM MENU_PRODUCTS WHERE name LIKE ?)
            UNION
            (SELECT * FROM MENU_PRODUCTS WHERE name LIKE ?)
            UNION
            (SELECT * FROM MENU_PRODUCTS WHERE category LIKE ?)
            UNION
            (SELECT * FROM MENU_PRODUCTS WHERE subcategory LIKE ?)
        ";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("ssss", $startsWithPattern, $containsPattern, $containsPattern, $containsPattern);

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function searchStockedProduct($prodName)
    {
        $startsWithPattern = $prodName . "%";
        $containsPattern = "%" . $prodName . "%";

        $query = "
            (SELECT * FROM STOCKED_UP_PRODUCTS WHERE name LIKE ?)
            UNION
            (SELECT * FROM STOCKED_UP_PRODUCTS WHERE name LIKE ?)
            UNION
            (SELECT * FROM STOCKED_UP_PRODUCTS WHERE category LIKE ?)
            UNION
            (SELECT * FROM STOCKED_UP_PRODUCTS WHERE subcategory LIKE ?)
        ";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("ssss", $startsWithPattern, $containsPattern, $containsPattern, $containsPattern);

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }


    private function getVariations(int $orderedProdId, int $tableId, $menuProdId)
    {
        $query = "
            SELECT  v.additionalRequest as name, v.additionalPrice 
            FROM ADDITIONAL_REQUESTS ar
                LEFT JOIN VARIATIONS v ON ar.variationId = v.variationId
            WHERE ar.tableId = ?
                AND ar.orderedProdId = ?
                AND ar.menuProdId = ?
        ";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("iii", $tableId, $orderedProdId, $menuProdId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCustomerOrderItems($orderNum, $tableId)
    {
        $query = "
            SELECT o.*, mp.name, pit.finalPrice
            FROM ORDINATIONS o
                RIGHT JOIN MENU_PRODUCTS mp ON o.menuProdId = mp.prodId
                LEFT JOIN PRODUCTS_IN_TABLE pit ON
                    o.orderedProdId = pit.orderedProdId 
                    AND o.menuProdId = pit.menuProdId 
                    AND o.tableId = pit.tableId
            WHERE o.tableId = ? 
                AND o.orderNum = ?;
        ";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param("ii", $tableId, $orderNum);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($products as &$product) {
            $product['variations'] = $this->getVariations($product['orderedProdId'], $product['tableId'], $product['menuProdId']);
        }

        return $products;
    }

    public function getUnpaidProducts($tableId)
    {
        $query = "
            SELECT pit.*, mp.name as prodName, t.name as tableName
            FROM PRODUCTS_IN_TABLE pit
                LEFT JOIN MENU_PRODUCTS mp ON pit.menuProdId = mp.prodId
                LEFT JOIN TABLES t ON pit.tableId = t.tableId
            WHERE pit.tableId = ?
                AND (pit.quantity - pit.numPaid) > 0;";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param("i", $tableId);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($products as &$product) {
            $product['variations'] = $this->getVariations($product['orderedProdId'], $product['tableId'], $product['menuProdId']);
        }

        return $products;
    }

    public function addNewReceipt($total, $paymentMethod, $givenMoney, $changeAmount, $tableId)
    {
        $query = "
            INSERT INTO RECEIPTS(total, paymentMethod, givenMoney, changeAmount, tableId) 
            VALUES (?, ?, ?, ?, ?);";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param("dsddi", $total, $paymentMethod, $givenMoney, $changeAmount, $tableId);
        $stmt->execute();
        return $this->db->insert_id;
    }

    public function addNewPaidProduct($orderedProdId, $menuProdId, $tableId, $receiptId, $quantity)
    {
        $query = "
            INSERT INTO PAID_PRODUCTS (orderedProdId, menuProdId, tableId, receiptId, quantity) 
            VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param("iiiii", $orderedProdId, $menuProdId, $tableId, $receiptId, $quantity);
        $result = $stmt->execute();

        $query = "
            UPDATE PRODUCTS_IN_TABLE 
            SET numPaid = numPaid + ?
            WHERE orderedProdId = ?
                AND menuProdId = ?
                AND tableId = ?";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param("iiii", $quantity, $orderedProdId, $menuProdId, $tableId);
        return $result && $stmt->execute();
    }

    public function getSalesInfo($startDate, $endDate)
    {
        $query = "
        SELECT 
            DATE(dateAndTime) as receiptDate, 
            SUM(total) as totalSum,
            COUNT(*) as totalPayments
        FROM 
            RECEIPTS
        WHERE 
            DATE(dateAndTime) >= ?
            AND DATE(dateAndTime) <= ?
        GROUP BY 
            DATE(dateAndTime)
        ORDER BY 
            receiptDate ASC;";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getStockCostsInfo($startDate, $endDate)
    {
        $query = "
        SELECT 
            DATE(creationTimestamp) as orderDate, 
            SUM(estimatedCost) as totalSum,
            COUNT(*) as totalPayments
        FROM 
            STOCK_ORDERS
        WHERE 
            DATE(creationTimestamp) >= ?
            AND DATE(creationTimestamp) <= ?
        GROUP BY 
            DATE(creationTimestamp)
        ORDER BY 
            orderDate ASC;";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getServicesInfo($startDate, $endDate)
    {
        $query = "
            SELECT 
                DATE(creationTimestamp) as serviceDate, 
                SUM(seats) as peopleServed,
                COUNT(*) as tablesServed
            FROM 
                TABLES
            WHERE 
                DATE(creationTimestamp) >= ?
                AND DATE(creationTimestamp) <= ?
            GROUP BY 
                serviceDate
            ORDER BY 
                serviceDate ASC;
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addNewEmployee(
        $email,
        $password,
        $cf,
        $name,
        $surname,
        $birthday,
        $hiringDate,
        $isWaiter = 0,
        $isStorekeeper = 0,
        $isKitchenStaff = 0,
        $isAdmin = 0,
        $city = null,
        $zipCode = null,
        $streetName = null,
        $streetNumber = null
    ) {
        $cf = strtoupper($cf);
        $query = "
            INSERT INTO EMPLOYEES(email, password, cf, name, surname, birthday, hiringDate,
                 isWaiter, isStorekeeper, isKitchenStaff, isAdmin, 
                 city, zipCode, streetName, streetNumber) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssssssiiiissss", $email, $password, $cf, $name, $surname, $birthday, $hiringDate, $isWaiter, $isStorekeeper, $isKitchenStaff, $isAdmin, $city, $zipCode, $streetName, $streetNumber);
        return $stmt->execute();
    }

    public function deleteEmployee($employeeId)
    {
        $query = "DELETE FROM EMPLOYEES WHERE employeeId = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $employeeId);
        return $stmt->execute();
    }

    private function getTypesAsString($array)
    {
        $arrayType = array_map(
            function ($val) {
                if (is_bool($val))
                    return "b";
                elseif (is_int($val))
                    return "i";
                elseif (is_double($val) || is_float($val))
                    return "d";
                else
                    return "s";
            },
            $array
        );

        return implode("", $arrayType);
    }

    public function updateEmployee(int $employeeId, array $updateInfo)
    {
        $updateInfo = array_filter($updateInfo, fn($e) => isset ($e) && $e != '');
        $query = "UPDATE EMPLOYEES SET " . implode(" = ?, ", array_keys($updateInfo)) . " = ? WHERE employeeId = ?;";
        $stmt = $this->db->prepare($query);
        $values = array_values($updateInfo);
        array_push($values, $employeeId);
        $stmt->bind_param($this->getTypesAsString($updateInfo) . "i", ...$values);
        return $stmt->execute();
    }

    public function getEmployees()
    {
        $query = "SELECT * FROM EMPLOYEES";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addNewMenuProduct($name, $category, $subcategory, $description, $price, $imgFile = null)
    {
        $query = "
            INSERT INTO MENU_PRODUCTS
                (name, category, subcategory, description, price";
        $query .= !empty($imgFile) ? ", imgFile" : ""; // Add param imgFile if not empty
        $query .= ") VALUES (?, ?, ?, ?, ?"; // Next part of the query
        $query .= !empty($imgFile) ? ", ?);" : ");"; // Add param imgFile if not empty
        $paramTypes = "ssssd"; // Types to add as argument in the prepared stmt
        $paramTypes .= !empty($imgFile) ? "s" : ""; // Add type of imgFile if not empty
        $params = array_filter([$name, $category, $subcategory, $description, $price, $imgFile], fn($e) => !is_null($e)); // If img is null array filter removes it

        $stmt = $this->db->prepare($query);
        $stmt->bind_param($paramTypes, ...$params);
        return $stmt->execute();
    }

    public function addNewStockedProduct($name, $category, $subcategory, $availability, $imgFile = null)
    {
        $query = "
            INSERT INTO STOCKED_UP_PRODUCTS
                (name, category, subcategory, availability";
        $query .= !empty($imgFile) ? ", imgFile" : ""; // Add param imgFile if not empty
        $query .= ") VALUES (?, ?, ?, ?"; // Next part of the query
        $query .= !empty($imgFile) ? ", ?);" : ");"; // Add param imgFile if not empty
        $paramTypes = "sssi"; // Types to add as argument in the prepared stmt
        $paramTypes .= !empty($imgFile) ? "s" : ""; // Add type of imgFile if not empty
        $params = array_filter([$name, $category, $subcategory, $availability, $imgFile], fn($e) => !is_null($e)); // If img is null array filter removes it

        $stmt = $this->db->prepare($query);
        $stmt->bind_param($paramTypes, ...$params);
        return $stmt->execute();
    }

    private function updateProduct($query, $prodId, array $updateInfo)
    {
        $stmt = $this->db->prepare($query);
        $values = array_values($updateInfo);
        array_push($values, $prodId);
        $stmt->bind_param($this->getTypesAsString($updateInfo) . "i", ...$values);
        return $stmt->execute();
    }

    public function updateMenuProduct(int $menuProdId, array $updateInfo)
    {
        $updateInfo = array_filter($updateInfo);
        $query = "UPDATE MENU_PRODUCTS SET " . implode(" = ?, ", array_keys($updateInfo)) . " = ? WHERE prodId = ?;";
        return $this->updateProduct($query, $menuProdId, $updateInfo);
    }

    public function updateStockedProduct(int $stockedProdId, array $updateInfo)
    {
        $updateInfo = array_filter($updateInfo);
        $query = "UPDATE STOCKED_UP_PRODUCTS SET " . implode(" = ?, ", array_keys($updateInfo)) . " = ? WHERE prodId = ?;";
        return $this->updateProduct($query, $stockedProdId, $updateInfo);
    }

    public function deleteProduct($query, $prodId)
    {
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $prodId);
        return $stmt->execute();
    }

    public function deleteMenuProduct($menuProdId)
    {
        $query = "DELETE FROM MENU_PRODUCTS WHERE prodId = ?";
        return $this->deleteProduct($query, $menuProdId);
    }

    public function deleteStockedProduct($stockedProdId)
    {
        $query = "DELETE FROM STOCKED_UP_PRODUCTS WHERE prodId = ?";
        return $this->deleteProduct($query, $stockedProdId);
    }

    public function getSuppliersForProd(int $prodId)
    {
        $query = "SELECT companyName, cost FROM supply_costs WHERE prodId = ? ORDER BY cost;";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $prodId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function createNewStockOrder(int $storekeeperId, $estimatedCost)
    {
        $query = "INSERT INTO STOCK_ORDERS (storekeeperId, estimatedCost) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("id", $storekeeperId, $estimatedCost);
        $result = $stmt->execute();
        return $result ? $this->db->insert_id : false;
    }

    public function addItemToStockOrder($prodId, $orderId, $supplierName, $quantity)
    {
        $query = "INSERT INTO SUPPLY_ITEMS(prodId, orderId, supplierName, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iisi", $prodId, $orderId, $supplierName, $quantity);
        $stmt->execute();

        return $this->db->insert_id;
    }

    public function getStockOrders()
    {
        $query = "
            SELECT *, 
            em.name as storekeeperName, 
            em.surname as storekeeperSurname 
            FROM STOCK_ORDERS st LEFT JOIN EMPLOYEES em 
                ON st.storekeeperId = em.employeeId";
        return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
    }

}
