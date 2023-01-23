<?php 
try {
    if(class_exists('User')) {
        class Utils {
    
            protected $usersId;
            protected $users;
            
            function __construct(protected $connection, protected $table_name) {
                $this->connection = $connection;
                $this->table_name = $table_name;
                $this->usersId = [];
    
                $sql = "SELECT id FROM $this->table_name";
    
                $result = $this->connection->query($sql);
    
                if($result->num_rows > 0) { 
                    while($row = $result->fetch_assoc()) {
                        $this->usersId[] = $row['id'];
                    }
                }
            }
    
            public function getUsersArray() {
                $this->users = [];
    
                foreach($this->usersId as &$id) {
                    $sql = "SELECT * FROM $this->table_name WHERE id='$id'";
    
                    $result = $this->connection->query($sql);
    
                    if($result->num_rows > 0) { 
                        while($row = $result->fetch_assoc()) {
    
                            $user = new User($this->connection, $row['id'], $row['firstname'], $row['surname'], $row['date_of_birth'], $row['gender'], $row['city_of_birth']);
    
                            $this->users[] = $user;
                        }
                    
                        
                    }
                }
            }
    
            public function removeUsers() {
                foreach($this->usersId as &$id) {
                    $sql = "DELETE FROM $this->table_name WHERE id='$id'";
    
                    $this->connection->query($sql);
                }   
            }
    
            public function __get($name) {
                return $this->$name;
            }
        }
    } else {
        throw new ErrorException('Сlass User does not exist<br/>');
    }
} catch(Exception $e) {
    echo $e->getMessage();
}

?>