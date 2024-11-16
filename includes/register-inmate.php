<?php

?>

<?php

$crimeResult = $admin->getCrime();

if ($crimeResult->num_rows > 0) {
    $crimeOptions = "";
    while ($row = $crimeResult->fetch_assoc()) {
        $crimeOptions .= '<option value="' .$row['id']. '">'.$row['crime'].'</option>';
    }
} else {
    $crimeOptions = '<option value="">No crime found</option>';
}

?>




<?php

function generateRandomId($length = 8){
    $randomId = '';
    for ($i=0; $i < $length; $i++) { 
        $randomId .= random_int(0, 9);
    }
    return $randomId;
}


if (isset($_POST['registerInmateBtn'])) {
    if ($_FILES['inmateImage']['error'] === UPLOAD_ERR_OK) {
        $uploadFile = $_FILES['inmateImage']['tmp_name'];
        $destination = "images/inmate-images/".$_FILES['inmateImage']['name'];

        // copying file to the inmate-images folder
        if (move_uploaded_file($uploadFile, $destination)) {
            
            $image = $destination;
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $dob = $_POST['dob'];
            $maritalStatus = $_POST['maritalStatus'];
            $crime = $_POST['crime'];
            $sentence = $_POST['sentence'];
            $timeServeStart = $_POST['timeServeStart'];
            $idNumber = $_POST['idNumber'];

            $result = $admin->registerInmate($firstName, $lastName, $crime, $sentence, $idNumber, $maritalStatus, $timeServeStart, $dob, $image);
            
            if ($result['success']) {
                echo '<script>alert("Inmate added successfully")</script>';
            }
            else{
                echo '<script>alert("' .$result['error']. '")</script>';
            }

        }
    }
    else {
        echo '<script>alert("error!'. $_FILES['inmateImage']['error']. '")</script>';
    }

}


?>


    <div class="register-inmate">
        <form action="" method="post" enctype="multipart/form-data">
            <h2>Register Inmate</h2>
            <div class="input-container">
                <div class="label first-name">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="label last-name">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
                <div class="label dob">
                    <label for="dob">Dob</label>
                    <input type="date" id="dob" name="dob" required>
                </div>
                <div class="label image">
                    <label for="image">Image</label>
                    <input type="file" name="inmateImage" id="image">
                </div>
                <div class="label marital-status">
                    <!-- <label for="marital-status">Marital status</label> -->
                    <select id="marital-status" name="maritalStatus" required>
                        <option value="">Marital status</option>
                        <option value="Single">Single</option>
                        <option value="Maried">Married</option>                    
                    </select>
                </div>
                <div class="label crime">
                    <!-- <label for="crime">Crime</label> -->
                    <select id="crime" name="crime" required>
                        <option value="">Crime Committed</option>
                        <?php echo $crimeOptions; ?>
                    </select>
                </div>
                <div class="label sentence">
                    <label for="sentence">Sentence</label>
                    <input type="text" id="sentence" name="sentence" required>
                </div>
                <div class="label tSS">
                    <label for="tSS">Time Serve Started</label>
                    <input type="date" id="tSS" name="timeServeStart" required>
                </div>
                
                <input type="hidden" name="idNumber" value="<?php echo generateRandomId(8); ?>">

                <button type="submit" name="registerInmateBtn">Register</button>
                <button type="button" id="discard" onclick="hideAddInmateForm()">Discard</button>
            </div>
            
        </form>
    </div>


<script>

    function hideAddInmateForm() {
        let inmateAddForm = document.querySelector(".register-inmate");
        let searchInmate = document.querySelector(".search-inmate");
        let inmateList = document.querySelector(".inmate-list");

        if (inmateAddForm.style.display === 'none') {
            inmateAddForm.style.display = 'block';
        } else {
            inmateAddForm.style.display = 'none';
            searchInmate.style.display = 'block';
            inmateList.style.display = 'block';

        }
    }
    
</script>