<?php 
// namespace user;

use stdClass;

class User {
    function __construct(
        protected $connection,
        protected $id,
        protected $firstname,
        protected $surname,
        protected $date_of_birth,
        protected $gender, 
        protected $city_of_birth
    ) {
        $this->connection = $connection;
        $this->firstname = $firstname;
        $this->surname = $surname;
        $this->date_of_birth = $date_of_birth;
        $this->gender = $gender;
        $this->city_of_birth = $city_of_birth; 
    }
    
    protected static function createStdClass() {
        $obj = new stdClass();

        $obj->connection = '';
        $obj->firstname = '';
        $obj->surname = '';
        $obj->date_of_birth = '';
        $obj->gender = '';
        $obj->city_of_birth = '';

        return $obj;
    } 

    public static function getUserAge($date_of_birth) {
        $current_date = date("j, n, Y");
        
        $date_in_array = explode("-", $date_of_birth);
        $current_date_in_array = explode(",", $current_date);
        settype($current_date_in_array[2], "int");
        settype($date_in_array[0], "int");

        $age = $current_date_in_array[2] - $date_in_array[0];

        if($current_date_in_array[2] > $date_in_array[0]) {

            $month_of_birth = $date_in_array[1];
            $number_of_birth_month = str_replace("0", "", $month_of_birth);
            settype($number_of_birth_month, "int");

            $day_of_birth = $date_in_array[2];
            $number_of_birth_day = str_replace("0", "", $day_of_birth);
            settype($day_of_birth, "int");

            settype($current_date_in_array[1], "int");

            if($current_date_in_array[1] < $number_of_birth_month) {
                $age -= 1;
            }
            
            settype($current_date_in_array[0], "int");

            if($current_date_in_array[1] === $number_of_birth_month && $number_of_birth_day > $current_date_in_array[0]) {
                $age -= 1;
            }  
        }

        $obj = static::createStdClass();
        $obj->age = $age;

        return $obj;
    }

    public static function getUserGender($index) {
        $gender = match($index) {
            0 => 'male<br/>',
            1 => 'female<br/>',
            default => 'Gender is not defined<br/>',
        };

        $obj = static::createStdClass();
        $obj->gender = $gender;

        return $obj;
    }

    public function addUser($table_name) {
        $sql = "INSERT INTO $table_name (id, firstname, lastname, dateOfBirth, gender, cityOfBirth) 
        VALUES ('$this->id', '$this->firstname', '$this->surname', '$this->date_of_birth', $this->gender, '$this->city_of_birth')";
        $succes = "User added";
        $unsucces = "Failed to add user";

        $this->displayQueryResult($sql, $succes, $unsucces);
    }

    public function removeUser($table_name) {
        $sql = "DELETE FROM $table_name WHERE id='$this->id'";
        $succes = "User with id $this->id removed";
        $unsucces = "Failed to remove user";
    
        $this->displayQueryResult($sql, $succes, $unsucces);
    }

    private function displayQueryResult($sql, $succes, $unsucces) {
        if ($this->connection->query($sql)) {
            echo "<p style='padding-left: 10px'>$succes</p>";
        } else {
            echo "<p style='padding-left: 10px'>$unsucces: $this->connection->error";
        }
    }
}
