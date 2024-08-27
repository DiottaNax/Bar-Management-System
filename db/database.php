<?php
class DatabaseHelper
{
    private mysqli $db;

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

    public function getCustomerOrders(bool $inPreparation, bool $delivered)
    {
        $query = "SELECT 
                        TABLES.name AS tableName, 
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


    private function createNewCustomerOrder(int $tableId, int $waiterId)
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


    public function calculateFinalPrice($menuProdId, array $variationIds)
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

    public function addProductToCustomerOrder($waiterId, $menuProdId, $tableId, $quantity, array $variationIds)
    {
        $this->db->begin_transaction();

        try {
            $customerOrderId = $this->createNewCustomerOrder($tableId, $waiterId); // Create new customer order and save id
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
            $stmt->bind_param("iiiii", $customerOrderId, $menuProdId, $tableId, $orderedProdId, $quantity);
            $stmt->execute();

            $this->db->commit();
            return ['success' => true, 'message' => "product added successfully"];
        } catch (Exception $error) {
            $this->db->rollback();
            return ['success' => false, 'message' => $error->getMessage()];
        }
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
        $stmt = $this->db->prepare("SELECT * FROM MENU_PRODUCTS WHERE name LIKE ?");

        $namePattern = $prodName . "%";
        $stmt->bind_param("s", $namePattern);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
