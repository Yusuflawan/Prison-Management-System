<?php

// include __DIR__ . '/../Classes/Database.php';
// include __DIR__ . '/../Classes/Admin.php';

// $db_conn = new Database();
// $db = $db_conn->connect();
// $admin = new Admin($db);

// // Get the search query from the request
// $searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// // Determine the SQL query based on whether it's a search or fetching all inmates
// if ($searchQuery === '') {
//     // Fetch all inmates
//     $query = "SELECT * FROM inmate";
//     $stmt = $db->prepare($query);
//     $stmt->execute();
// } else {
//     // Search for inmates
//     $query = "
//         SELECT * FROM inmate 
//         WHERE idNumber LIKE ? OR CONCAT(firstName, ' ', lastName) LIKE ?
//     ";
//     $stmt = $db->prepare($query);
//     $searchTerm = '%' . $searchQuery . '%';
//     $stmt->bind_param('ss', $searchTerm, $searchTerm);
//     $stmt->execute();
// }

// $result = $stmt->get_result();
// // $data = $result->fetch_assoc();

// // $crimeId = $data['crime'];

// // $crime = $admin->getCrimeById($crimeId);

// $inmate = "";
// $index = 1;

// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         $inmate .= '<tr>
//                         <td>' . $index . '</td>
//                         <td>' . htmlspecialchars($row['idNumber'], ENT_QUOTES, 'UTF-8') . '</td>
//                         <td>' . htmlspecialchars($row['firstName'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($row['lastName'], ENT_QUOTES, 'UTF-8') . '</td>
//                         <td>' . htmlspecialchars($row['crime'], ENT_QUOTES, 'UTF-8') . '</td>
//                         <td>' . htmlspecialchars($row['sentence'], ENT_QUOTES, 'UTF-8') . '</td>
//                         <td><button type="button" onclick="retrieveRecord('.$row['id'].')">retrieve</button></td>                        
//                     </tr>';
//         $index++;
//     }
// } else {
//     $inmate = '<tr>
//                 <td colspan="6">No inmate found</td>
//             </tr>';
// }

// echo $inmate;

?>


<?php

include __DIR__ . '/../Classes/Database.php';
include __DIR__ . '/../Classes/Admin.php';

$db_conn = new Database();
$db = $db_conn->connect();
$admin = new Admin($db);

// Get the search query from the request
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// Determine the SQL query based on whether it's a search or fetching all inmates
if ($searchQuery === '') {
    // Fetch all inmates
    $query = "
        SELECT inmate.*, crime.crime AS crime_name 
        FROM inmate 
        JOIN crime ON inmate.crime = crime.id";
    $stmt = $db->prepare($query);
    $stmt->execute();
} else {
    // Search for inmates
    $query = "
        SELECT inmate.*, crime.crime AS crime_name 
        FROM inmate 
        JOIN crime ON inmate.crime = crime.id
        WHERE idNumber LIKE ? OR CONCAT(firstName, ' ', lastName) LIKE ?";
    $stmt = $db->prepare($query);
    $searchTerm = '%' . $searchQuery . '%';
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
}

$result = $stmt->get_result();
$inmate = "";
$index = 1;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $inmate .= '<tr>
                        <td>' . $index . '</td>
                        <td>' . htmlspecialchars($row['idNumber'], ENT_QUOTES, 'UTF-8') . '</td>
                        <td>' . htmlspecialchars($row['firstName'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($row['lastName'], ENT_QUOTES, 'UTF-8') . '</td>
                        <td>' . htmlspecialchars($row['crime_name'], ENT_QUOTES, 'UTF-8') . '</td>
                        <td>' . htmlspecialchars($row['sentence'], ENT_QUOTES, 'UTF-8') . '</td>
                        <td><button type="button" onclick="retrieveRecord('.$row['id'].')">retrieve</button></td>                        
                    </tr>';
        $index++;
    }
} else {
    $inmate = '<tr>
                <td colspan="6">No inmate found</td>
            </tr>';
}

echo $inmate;

?>
