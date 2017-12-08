<?php
/**
 * Description of api_controller
 *
 * @author anan
 */
include_once 'queue_model.php';
class API {
    
    private $db;
    private $queue;
    
    public function __construct($db_connection) {
        $this->db = $db_connection;
        $this->queue = new Queue($db_connection);
    }
    public function getAllQueue($params="") {

        
        // query products
        $stmt = $this->queue->getQueues($params);
        $num = $stmt->rowCount();        
   
        // check if more than 0 record found
        if($num>0){
        // queue array
        $queue_arr= [];
        $queue_arr["status"]="success";
        $queue_arr["data"]= [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);

            $queue_item=array(
                "id" => $id,
                "firstName" => $firstName,
                "lastName" => $lastName,
                "organization" => $organization,
                "type" => $type,
                "service" => $service,
                "queuedDate" => $queuedDate,
            );

            @array_push($queue_arr["data"], $queue_item);
        }
        echo json_encode($queue_arr);
        }else{
            echo json_encode(
                [
                    "status" => "success",
                    "data" => []
                ]
            );
        }
    }
    public function create() {
        // get posted data
        $data = file_get_contents("php://input");
        
        //Check if is JSON
        if(!$this->isJSON($data)){
            $this->responseError("Error, Data Format");
            exit();
        }
        
        $data = json_decode($data);
        
        //ValidATE DATE
        $message = "";
        if(!$this->validateData($data, $message)){
            $message = "Error, Validate data. " . $message;
            $this->responseError($message);
            exit();
        } 

        // set queue property values
        $this->queue->firstName = $data->firstName;
        $this->queue->lastName = $data->lastName;
        $this->queue->organization = $data->organization;
        $this->queue->type = $data->type;
        $this->queue->service = $data->service;
        $this->queue->queuedDate = date('Y-m-d H:i:s');

        // create the product
        if($this->queue->create()){
            $queue_item=[
                "id" => $this->queue->id,
                "firstName" => $this->queue->firstName,
                "lastName" => $this->queue->lastName,
                "organization" => $this->queue->organization,
                "type" => $this->queue->type,
                "service" => $this->queue->service,
                "queuedDate" => $this->queue->queuedDate,
            ];
            echo json_encode(
                [
                    "status" => "success",
                    "data" => $queue_item
                ]
            );
        }else{
            $this->responseError("Error create Data.");
        }
    }
    private function responseError($message){
        echo json_encode(
            [
                "status" => "error",
                "message" => $message
            ]
        );
    }
    
    private function isJSON($jsonString) {
        return is_string($jsonString) && is_array(json_decode($jsonString, true)) 
                && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
    private function validateData($data, &$message) {
        $message = "";
        
        //Check Type
        $temArray =["citizen", "anonymous"];
        if(!in_array(strtolower($data->type), $temArray)){
            $message = "type must be Citizen or Anonymous. ";
        }
        //Check service
        $temArray =["council tax", "benetifs", "rent"];
        if(!in_array(strtolower($data->service), $temArray)){
            $message .= "Service must be Council Tax, Benetifs or Rent. ";
        }
        if(strtolower($data->type)== "citizen"){
            if(is_null($data->firstName) || ($data->firstName."" =="") ){
                $message .= "Firstname required when type is citizen. ";
            }
            if(is_null($data->lastName) || ($data->lastName."" =="") ){
                $message .= "LastName required when type is citizen. ";
            }
        }
        return ($message == "");
    }
}
