<?php

class Admin{
    private $firstName;
    private $lastName;
    private $crime;
    private $sentence;
    private $idNumber;
    private $maritalStatus;
    private $timeServeStart;
    private $dob;
    private $image;
    private $conn;


    public function __construct($db){
        $this->conn = $db;
    }

    public function getAdmin(){
        $sql = "SELECT * FROM `admin`";
            $result = $this->conn->query($sql);
            return $result;      
    }

    public function getInmate(){
        $sql = "SELECT * FROM `inmate`";
            $result = $this->conn->query($sql);
            return $result;      
    }

    public function getInmateById($id) {
        // $sql = "SELECT * FROM inmate WHERE id = ?";
        $sql = "SELECT
                    inmate.idNumber,
                    inmate.firstName,
                    inmate.lastName,
                    inmate.dob,
                    inmate.maritalStatus,
                    inmate.image,
                    crime.crime,
                    inmate.sentence,
                    inmate.timeServeStart
                FROM
                    inmate
                JOIN
                    crime
                ON
                    inmate.crime = crime.id
                WHERE
                    inmate.id = ?";

        $stmt = $this->conn->prepare($sql);

        // Check if the preparation of the statement was successful
        if (!$stmt) {
            error_log("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
            return [
                'success' => false,
                'error' => 'Failed to prepare the SQL statement.'
            ];
        }
        // Bind the parameters to the statement
        if (!$stmt->bind_param("i", $id)) {
            error_log("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
            return [
                'success' => false,
                'error' => 'Failed to bind parameters.'
            ];
        }
        // Execute the statement
        if (!$stmt->execute()) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            return [
                'success' => false,
                'error' => 'Failed to execute the statement.'
            ];
        }
        
            $result = $stmt->get_result();
            $inmate = $result->fetch_assoc();

            // Close the statement
            $stmt->close();
        
            // Return success if all operations were successful
            return [
                'success' => true,
                'data' => $inmate,
                'error' => null
            ];
    }

    public function getCrime(){
        $sql = "SELECT * FROM `crime`";
            $result = $this->conn->query($sql);
            return $result;      
    }

    public function getNumCrimes(){
        $sql = "SELECT COUNT(*) AS totalCrimes FROM crime";
            $result = $this->conn->query($sql);
            return $result;      
    }

    public function getNumInmates(){
        $sql = "SELECT COUNT(*) AS totalInmates FROM inmate";
            $result = $this->conn->query($sql);
            return $result;      
    }

    public function getTodaysNumVisitors(){
        $sql = "SELECT COUNT(*) AS totalVisitors FROM visitor WHERE visitor.date = CURDATE()";
            $result = $this->conn->query($sql);
            return $result;      
    }

    public function getVisitationHistoryByInmateId($inmateId){
        $sql = "SELECT * FROM `visitor` WHERE inmate = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $inmateId);
        $stmt->execute();
        $result = $stmt->get_result();

        $visitationHistory = [];
        while ($row = $result->fetch_assoc()) {
            $visitationHistory[] = $row;
        }

        return $visitationHistory;
    }

    public function getVisitorsByDate($date = null) {
        // Use the provided date or default to today’s date
        $date = $date ? $date : date('d/m/Y');
    
        $sql = "SELECT 
                    visitor.date,
                    visitor.firstName as visitorFirstName,
                    visitor.lastName as visitorLastName,
                    inmate.idNumber as inmateIdNumber,
                    inmate.lastName as inmateFirstName,
                    inmate.firstName as inmateLastName
                FROM
                    `visitor`
                JOIN
                    `inmate` ON visitor.inmate = inmate.id
                WHERE DATE(visitor.date) = ?";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $date);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result;
    }
    
    public function registerInmate($firstName, $lastName, $crime, $sentence, $idNumber, $maritalStatus, $timeServeStart, $dob, $image){

        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->crime = $crime;
        $this->sentence = $sentence;
        $this->idNumber = $idNumber;
        $this->maritalStatus = $maritalStatus;
        $this->timeServeStart = $timeServeStart;
        $this->dob = $dob;
        $this->image = $image;

        $sql = "INSERT INTO `inmate`(`firstName`, `lastName`, `crime`, `sentence`, `idNumber`, `maritalStatus`, `timeServeStart`, `dob`, `image`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        // Check if the preparation of the statement was successful
        if (!$stmt) {
            error_log("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
            return [
                'success' => false,
                'error' => 'Failed to prepare the SQL statement.'
            ];
        }
        // Bind the parameters to the statement
        if (!$stmt->bind_param("ssissssss", $this->firstName, $this->lastName, $this->crime, $this->sentence, $this->idNumber, $this->maritalStatus, $this->timeServeStart, $this->dob, $this->image)) {
            error_log("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
            return [
                'success' => false,
                'error' => 'Failed to bind parameters.'
            ];
        }
        // Execute the statement
        if (!$stmt->execute()) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            return [
                'success' => false,
                'error' => 'Failed to execute the statement.'
            ];
        }
        
            // Close the statement
            $stmt->close();
        
            // Return success if all operations were successful
            return [
                'success' => true,
                'error' => null
            ];
    }

    public function registerVisitor($visitorFirstName, $visitorLastName, $visitorPhone, $inmateId, $date) {
        $sql = "INSERT INTO visitor(firstName, lastName, phone, inmate, date) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        // Check if the preparation of the statement was successful
        if (!$stmt) {
            error_log("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
            return [
                'success' => false,
                'error' => 'Failed to prepare the SQL statement.'
            ];
        }

        // Bind the parameters to the statement
        if (!$stmt->bind_param("sssis", $visitorFirstName, $visitorLastName, $visitorPhone, $inmateId, $date)) {
            error_log("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
            return [
                'success' => false,
                'error' => 'Failed to bind parameters.'
            ];
        }
        // Execute the statement
        if (!$stmt->execute()) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            return [
                'success' => false,
                'error' => 'Failed to execute the statement.'
            ];
        }

        // Close the statement
        $stmt->close();
        
        // Return success if all operations were successful
        return [
            'success' => true,
            'error' => null
        ];
        
    }

    public function registerCrime($crime) {
        $sql = "INSERT INTO crime(crime) 
                  VALUES (?)";

        $stmt = $this->conn->prepare($sql);

        // Check if the preparation of the statement was successful
        if (!$stmt) {
            error_log("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
            return [
                'success' => false,
                'error' => 'Failed to prepare the SQL statement.'
            ];
        }

        // Bind the parameters to the statement
        if (!$stmt->bind_param("s", $crime)) {
            error_log("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
            return [
                'success' => false,
                'error' => 'Failed to bind parameters.'
            ];
        }
        // Execute the statement
        if (!$stmt->execute()) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            return [
                'success' => false,
                'error' => 'Failed to execute the statement.'
            ];
        }

        // Close the statement
        $stmt->close();
        
        // Return success if all operations were successful
        return [
            'success' => true,
            'error' => null
        ];
        
    }

}




        