<?php
class DatabaseHelper
{
    private $db;

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
}
