<?php

include 'Classes/Database.php';
include 'Classes/Admin.php';

$db_conn = new Database();
$db = $db_conn->connect();

$admin = new Admin($db);


?>


<?php

$numberOfCrimes = $admin->getNumCrimes();

if ($numberOfCrimes->num_rows > 0) {

    while ($row = $numberOfCrimes->fetch_assoc()) {
            $totalCrimes = $row['totalCrimes'];
        }
    }

?>

<?php

$numberOfInmates = $admin->getNumInmates();

if ($numberOfInmates->num_rows > 0) {

    while ($row = $numberOfInmates->fetch_assoc()) {
            $totalInmates = $row['totalInmates'];
        }
    }

?>

<?php

$numberOfTodaysVisitors = $admin->getTodaysNumVisitors();

if ($numberOfTodaysVisitors->num_rows > 0) {

    while ($row = $numberOfTodaysVisitors->fetch_assoc()) {
            $totalVisitors = $row['totalVisitors'];
        }
    }

?>
<?php include 'includes/header.php'; ?>

    <div class="dashboard">
        <?php include 'includes/side-nav.php'; ?>
        <div class="dashboard-contents">
            <div class="dashboard-text">
                <p>Dashboard</p>
            </div>
            <div class="welcome-text">
                <p>Welcome, <?php echo $_SESSION['firstName']; ?></p>
            </div>
            <div class="cards">
                <div class="inmate-number">
                    <p>Number of Inmates <?php echo '<span>'. $totalInmates .'</span>'; ?> </p>
                </div>
                <div class="crime-number">
                    <p>Numer of Crimes <?php echo '<span>'. $totalCrimes .'</span>'; ?> </p>
                </div>
                <!-- <div class="discharged-number">
                    <p>Discharged Inmates <span>50</span></p>
                </div> -->
                <!-- <div class="cell-number">
                    <p>Number of cell blocks <span>5</span></p>
                </div> -->
                <!-- <div class="staff-number">
                    <p>Number of Staffs <span>50</span></p>
                </div> -->
                <div class="visitor-number">
                    <p>Today's visitors <?php echo '<span>'. $totalVisitors .'</span>'; ?> </p>
                </div>
                                
            </div>
        </div>
    </div>
</body>
</html>