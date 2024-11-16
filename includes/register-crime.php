
<?php

if (isset($_POST['registerCrimeBtn'])) {
    
    $crime = $_POST['crime'];

    $result = $admin->registerCrime($crime);
            
    if ($result['success']) {
        echo '<script>
                alert("Crime has been registered successfully");
                window.location.href = "crime.php";
              </script>';
        exit;
    }
    else{
        echo '<script>alert("' .$result['error']. '")</script>';
    }
}

?>

<div class="register-crime">
    
    <form method="POST" action="">
        <h3>Register Crime</h3>
        <div class="input-container">
            <div class="label crime">
                <label for="crime">Crime</label>
                <input type="text" name="crime" id="crime" required>
            </div>
            <div class="label">
                <input type="hidden">
            </div>
            <button type="submit" name="registerCrimeBtn">Save</button>
            <button type="button" id="discard" onclick="hideCrimeForm()">Discard</button>
        </div>
    </form>
</div>


<script>
    function hideCrimeForm() {
        let crimeForm = document.querySelector(".register-crime");
        let crimeList = document.querySelector(".crime-list");

        if ( crimeForm.style.display === 'none') {
            crimeForm.style.display = 'block';
        } else {
            crimeForm.style.display = 'none';
            crimeList.style.display = 'block';
        }
    }
</script>
