<?php
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';


require_once "User.php";
require_once "utils.php";

const table_name = "users";


class Database
{
    protected $connection;
    protected $db_name = "";

    public function __construct($db_host = "localhost", $db_user = "root", $db_password = "root", $charset = "utf8") {
        $this->connection = new mysqli($db_host, $db_user, $db_password);

        if ($this->connection->connect_error) {
            die("Failed to connect to MySQL - " . $this->connection->connect_error . "<br/>");
        }

        $this->connection->set_charset($charset);
    }

    public function createDatabase($db_name = "test_database") {
        $this->db_name = $db_name;

        $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
        $succes = "Database created successfully";
        $unsucces = "Failed to created database";

        $this->displayQueryResult($sql, $succes, $unsucces);
    }

    public function removeDatabase() {
        $sql = "DROP DATABASE IF EXISTS $this->db_name";
        $succes = "Database removed successfully";
        $unsucces = "Failed to removed database";

        $this->displayQueryResult($sql, $succes, $unsucces);
    }

    public function createTable($table_name) {
        $this->connection->select_db($this->db_name);

        $sql = "CREATE TABLE IF NOT EXISTS $table_name(
            id VARCHAR(255) NOT NULL,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL, 
            dateOfBirth DATE NOT NULL,
            gender TINYINT(1) NOT NULL,
            cityOfBirth VARCHAR(255) NOT NULL,
            PRIMARY KEY (id)    
        );";
        $succes = "Table $table_name created successfully";
        $unsucces = "Failed to created table";

        $this->displayQueryResult($sql, $succes, $unsucces);
    }

    public function displayTableData($table_name) {
        $sql = "SELECT * FROM $table_name;";
        $result = $this->connection->query($sql);

        $style = "text-align: center; padding: 10px";

        if ($result->num_rows > 0) {

            echo "<br/>
            <div class='container'>
                <div class='row justify-content-center'>
                    <table border='2px solid #000'>
                    <head>
                    <tr>
                        <th style='$style'>id</th>
                        <th style='$style'>firstname</th>
                        <th style='$style'>lastname</th>
                        <th style='$style'>date of birth</th>
                        <th style='$style'>gender</th>
                        <th style='$style'>city of birth</th>
                    </tr>
                    </head>
                    <tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td style='$style'>" . $row["id"] . "</td>" .
                            "<td style='$style'>" . $row["firstname"] . "</td>" .
                            "<td style='$style'>" . $row["lastname"] . "</td>" .
                            "<td style='$style'>" . $row["dateOfBirth"] . "</td>" .
                            "<td style='$style'>" . $row["gender"] . "</td>" .
                            "<td style='$style'>" . $row["cityOfBirth"] . "</td>
                            </tr>";
                    }
                    echo "</tbody>
                    </table>
                </div>
            </div>";
        } else {
            echo "Table is empty<br/>";
        }
    }

    public function removeUser($table_name, $id) {
        $sql = "DELETE FROM $table_name WHERE id='$id'";
        $succes = "User with id $this->id removed";
        $unsucces = "Failed to remove user";

        $sql1 = "SELECT * FROM $table_name;";
        $result = $this->connection->query($sql1);
        $condition = false;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                global $condition;

                if ($row["id"] === $id) $condition = true;
            }
        }
        if ($condition) {
            $this->displayQueryResult($sql, $succes, $unsucces);
        } else {
            echo "<br/>" . $unsucces . ", there is no such id $id";
        }
    }

    private function displayQueryResult($sql, $succes, $unsucces) {
        if ($this->connection->query($sql)) {
            echo "<p style='padding-left: 10px'>$succes</p>";
        } else {
            echo "<p style='padding-left: 10px'>$unsucces: $this->connection->error";
        }
    }


    public function __get($name) {
        return $this->$name;
    }
}

$database = new Database();
$database->createDatabase();
$database->createTable(table_name);
$database->displayTableData(table_name);
// $database->removeDatabase();

$user = new User(
    $database->connection,
    uniqid(),
    "Vasja",
    "Pupkin",
    "2000-01-01",
    1,
    "Minsk"
);

// $user->addUser(table_name);
// $user->getUserAge("2000-01-01");
// $user->getUserGender(0);

$utils = new Utils($database->connection, table_name);
$utils->getUsersArray();
// $utils->removeUsers();
// print_r($utils->users);

// $database->removeUser(table_name, '1');

// $user->addUser(table_name);
// $user->removeUser(table_name);
