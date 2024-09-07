<?php
class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $servername = "localhost"; // Adjust as needed
        $username = "your_username"; // Adjust as needed
        $password = "your_password"; // Adjust as needed
        $dbname = "event_management"; // Adjust as needed

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function insert($table, $data)
    {
        $conn = $this->getConnection();
        $columns = implode(", ", array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";

        if ($conn->query($sql) === TRUE) {
            return $conn->insert_id;
        } else {
            return false;
        }
    }

    public function update($table, $data, $where)
    {
        $conn = $this->getConnection();
        $setClause = "";
        foreach ($data as $key => $value) {
            $setClause .= "$key = '$value', ";
        }
        $setClause = rtrim($setClause, ", ");
        $sql = "UPDATE $table SET $setClause WHERE $where";

        return $conn->query($sql);
    }

    public function delete($table, $where)
    {
        $conn = $this->getConnection();
        $sql = "DELETE FROM $table WHERE $where";

        return $conn->query($sql);
    }

    public function search($table, $columns, $where = "")
    {
        $conn = $this->getConnection();
        $columnsStr = implode(", ", $columns);
        $sql = "SELECT $columnsStr FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $result = $conn->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }
}