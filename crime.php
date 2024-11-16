<?php

include 'Classes/Database.php';
include 'Classes/Admin.php';

$db_conn = new Database();
$db = $db_conn->connect();

$admin = new Admin($db);

$result = $admin->getCrime();

if ($result->num_rows > 0) { 
    $index = 1;
    $crime = "";  
    while ($row = $result->fetch_assoc()) {
        $crime .= '<tr>
                        <td>' .$index.'</td>
                        <td>' .$row['crime'].'</td>
                    </tr>';
        $index++;
    }
}
else {
    $crime = '<tr>
                <td>No crime registered on this system yet</td>
            </tr>';
}

?>


<?php include 'includes/header.php'; ?>

<div class="crime">
    <?php include 'includes/side-nav.php'; ?>
    <div class="crime-contents">
        <?php include 'includes/register-crime.php'; ?>

        <div class="crime-list">
            <div class="title">
                <h3>Crime List</h3>
                <div class="new-crime">
                    <button type="button" onclick="displayCrimeForm()">New Crime</button>
                </div>
            </div>
            <table>
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Crimes</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php echo $crime; ?>
                    </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    function displayCrimeForm() {
        let crimeAddForm = document.querySelector(".register-crime");
        let crimeList = document.querySelector(".crime-list");

        if (crimeAddForm.style.display === 'none' || crimeAddForm.style.display === '') {
            crimeAddForm.style.display = 'block';
            crimeList.style.display = 'none';
        } else {
            crimeAddForm.style.display = 'block';
        }
    }
</script>