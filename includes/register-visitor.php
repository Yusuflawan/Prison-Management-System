<?php

$inmatesResult = $admin->getInmate();
$inmateOptions = '';

if ($inmatesResult->num_rows > 0) {
    while ($row = $inmatesResult->fetch_assoc()) {
        $inmateOptions .= '<option data-idNumber="'.$row['idNumber'].'" value="' . $row['id'] . '">' . $row['firstName'] . " " . $row['lastName'] . '</option>';
    }
} else {
    $inmateOptions = '<option value="">No Inmates found</option>';
}

?>

<?php

if (isset($_POST['registerVisitorBtn'])) {
    
    $visitorFirstName = $_POST['visitorFirstName'];
    $visitorLastName = $_POST['visitorLastName'];
    $visitorPhone = $_POST['visitorPhone'];
    $inmateId = $_POST['inmateId'];
    $date = $_POST['date'];

    $result = $admin->registerVisitor($visitorFirstName, $visitorLastName, $visitorPhone, $inmateId, $date);
            
    if ($result['success']) {
        echo '<script>
                alert("Visitation registered successfully");
                window.location.href = "visitation.php";
              </script>';
        exit;
    }
    else{
        echo '<script>alert("' .$result['error']. '")</script>';
    }
}

?>

<div class="register-visitor">
    
    <form method="POST" action="">
        <h3>Record Visitation</h3>
        <div class="input-container">
            <label for="visitorFirstName">Visitor First Name:</label>
            <input type="text" name="visitorFirstName" id="visitorFirstName" required>
            
            <label for="visitorLastName">Visitor Last Name:</label>
            <input type="text" name="visitorLastName" id="visitorLastName" required>

            <label for="visitorLastName">Visitor Phone:</label>
            <input type="text" name="visitorPhone" id="visitorPhone" required>

            <!-- <label for="inmateSelect">Select Inmate:</label> -->
            <select name="inmateId" id="inmateSelect" onchange="updateInmateDetails()">
                <option value="" selected disabled>Select Inmate</option>
                <?php echo $inmateOptions; ?>
            </select>
            
            <label for="inmateIdNumber">Inmate ID Number:</label>
            <input type="text" name="inmateIdNumber" id="inmateIdNumber" required readonly>
            
            <label for="date">Date:</label>
            <input type="date" name="date" id="date" required>
            
            <button type="submit" name="registerVisitorBtn">Save</button>
            <button type="button" id="discard" onclick="hideVisitorForm()">Discard</button>
        </div>
    </form>
</div>

<script>
     function updateInmateDetails() {
        const select = document.getElementById('inmateSelect');
        const selectedOption = select.options[select.selectedIndex];
        const inmateIdNumber = selectedOption.getAttribute('data-idNumber');

        // Check if inmateIdNumber is undefined or empty
        if (inmateIdNumber) {
            document.getElementById('inmateIdNumber').value = inmateIdNumber;
        } else {
            document.getElementById('inmateIdNumber').value = '';
        }
    }

    function hideVisitorForm() {

        let visitorForm = document.querySelector(".register-visitor");
        let searchVisitation = document.querySelector(".search-visitation");
        let visitationList = document.querySelector(".visitation-list");

        if (visitorForm.style.display === 'none') {
            visitorForm.style.display = 'block';
        } else {
            visitorForm.style.display = 'none';
            searchVisitation.style.display = 'block';
            visitationList.style.display = 'block';

        }

    }

</script>
