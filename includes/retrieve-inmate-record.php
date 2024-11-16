<?php

// include '../Classes/Database.php';
// include '../Classes/Admin.php';

// $db_conn = new Database();
// $db = $db_conn->connect();
// $admin = new Admin($db);

// // Read JSON data from the request body
// $jsonData = file_get_contents('php://input');
// $data = json_decode($jsonData, true);

// // Check if the 'id' key is present in the decoded JSON
// if (isset($data['id'])) {
//     $inmateId = $data['id'];

//     // Retrieve the inmate record
//     $getInmateInfo = $admin->getInmateById($inmateId);

//     // retrieve visitation history
//     $getInmateVisitation = $admin->getVisitationHistoryByInmateId($inmateId);

//     if ($getInmateInfo['success']) {
//         $inmateRecord = $getInmateInfo['data'];

//         $data['inmateDetails'] = $inmateRecord;

//         // Add visitation history to the inmate record
//         $idata['visitationHistory'] = $getInmateVisitation;
//         echo json_encode($data); // Return the full inmate record as JSON
//     } else {
//         echo json_encode(['error' => $getInmateInfo['error']]); // Return the error message as JSON
//     }
// } else {
//     echo json_encode(['error' => 'No ID provided']); // Handle missing ID in the request
// }
?>



<?php

include '../Classes/Database.php';
include '../Classes/Admin.php';

$db_conn = new Database();
$db = $db_conn->connect();
$admin = new Admin($db);

// Read JSON data from the request body
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

// Check if the 'id' key is present in the decoded JSON
if (isset($data['id'])) {
    $inmateId = $data['id'];

    // Retrieve the inmate record
    $getInmateInfo = $admin->getInmateById($inmateId);
    // var_dump($getInmateVisitation);

    // Retrieve visitation history
    $getInmateVisitation = $admin->getVisitationHistoryByInmateId($inmateId);

    if ($getInmateInfo['success']) {
        $inmateRecord = $getInmateInfo['data'];

        // Wrap inmate details and visitation history in the appropriate structure
        $data = [
            'inmateDetails' => [
                'idNumber' => $inmateRecord['idNumber'],
                'firstName' => $inmateRecord['firstName'],
                'lastName' => $inmateRecord['lastName'],
                'dob' => $inmateRecord['dob'],
                'maritalStatus' => $inmateRecord['maritalStatus'],
                'crime' => $inmateRecord['crime'],
                'sentence' => $inmateRecord['sentence'],
                'timeServeStart' => $inmateRecord['timeServeStart'],
                'image' => $inmateRecord['image'],
            ],
            'visitationHistory' => $getInmateVisitation
        ];

        echo json_encode($data); // Return the full inmate record with visitation history as JSON
    } else {
        echo json_encode(['error' => $getInmateInfo['error']]); // Return the error message as JSON
    }
} else {
    echo json_encode(['error' => 'No ID provided']); // Handle missing ID in the request
}
?>
