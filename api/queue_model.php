<?php
/**
 * Description of queue
 *
 * @author anan
 */
class Queue {
    // database connection and table name
    private $conn;
    private $table_name = "queue";
    
    // object properties
    public $id;
    public $firstName;
    public $lastName;
    public $organization;
    public $type;
    public $service;
    public $queuedDate;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    /**
     * 
     * @return list
     */
    function getQueues($filter){

        // select all query
        $query = "SELECT
                    id, firstName, lastName, organization, type, service, queuedDate 
                FROM
                    " . $this->table_name . " 
                WHERE  (DATE_FORMAT(queuedDate, '%Y-%m-%d') = CURDATE())
                       AND
                        (type = '".$filter."' OR '".$filter. "' = '')
                ORDER BY
                    queuedDate DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
    
    /**
     * 
     * @return boolean
     */
    function create(){

        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    firstName=:firstName, lastName=:lastName, organization=:organization, 
                    type=:type, service=:service, queuedDate=:queuedDate ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->firstName =      $this->prepareString($this->firstName);
        $this->lastName =       $this->prepareString($this->lastName); 
        $this->organization =   $this->prepareString($this->organization);
        $this->type =           htmlspecialchars(strip_tags($this->type));
        $this->service =        htmlspecialchars(strip_tags($this->service));
        $this->queuedDate =     htmlspecialchars(strip_tags($this->queuedDate));

        // bind values
        $stmt->bindParam(":firstName", $this->firstName);
        $stmt->bindParam(":lastName", $this->lastName);
        $stmt->bindParam(":organization", $this->organization);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":service", $this->service);
        $stmt->bindParam(":queuedDate", $this->queuedDate);

        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }else{
            return false;
        }
    }
    private function prepareString($value) {
        return ($value."" =="")? null:  htmlspecialchars(strip_tags($value));
    }
}
