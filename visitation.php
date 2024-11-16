<?php
include 'Classes/Database.php';
include 'Classes/Admin.php';

// Initialize database connection
$db_conn = new Database();
$db = $db_conn->connect();

// Create Admin instance
$admin = new Admin($db);

// Determine the selected date from the GET request, default to today's date if not provided
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Fetch visitors for the selected date
$result = $admin->getVisitorsByDate($selectedDate);

if ($result->num_rows > 0) {
    $index = 1;
    $visitor = "";
    while ($row = $result->fetch_assoc()) {
        $visitor .= '<tr>
                        <td>' . $index . '</td>
                        <td>' . htmlspecialchars($row['visitorFirstName']) . ' ' . htmlspecialchars($row['visitorLastName']) . '</td>
                        <td>' . htmlspecialchars($row['inmateIdNumber']) . '</td>
                        <td>' . htmlspecialchars($row['inmateFirstName']) . ' ' . htmlspecialchars($row['inmateLastName']) . '</td>
                        <td>' . htmlspecialchars($row['date']) . '</td>
                    </tr>';
        $index++;
    }
} else {
    $visitor = '<tr>
                    <td colspan="5">No visitors found on the selected date</td>
                </tr>';
}

// Function to format the date
function formatDate($date) {
    try {
        $dateObj = new DateTime($date);
        return $dateObj->format('D, jS M Y'); // Adjusted format to include year
    } catch (Exception $e) {
        return 'Invalid date'; // Handle invalid date formats
    }
}

// Format the selected date
$formattedDate = formatDate($selectedDate);
?>


<?php include 'includes/header.php'; ?>

<div class="visitation">
    <?php include 'includes/side-nav.php'; ?>
    <div class="visitation-contents">
        <div class="search-visitation">
            <form method="GET" action="">
                <label for="search-input">Filter Date</label>
                <input type="date" id="search-input" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        <?php include 'includes/register-visitor.php'; ?>
        <div class="visitation-list">
            <?php $text = ($selectedDate == date("Y-m-d")) ? "Visitation for Today" : "Visitation on " . htmlspecialchars($formattedDate); ?>
            <div class="title">
                <div class="new-visitation">
                    <h3><?php echo $text; ?></h3>
                    <button onclick="displayVisitorForm()">New Visitation</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Visitor</th>
                        <th>Inmate ID</th>
                        <th>Inmate</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $visitor; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function displayVisitorForm(){
        let visitorForm = document.querySelector(".register-visitor");
        let searchVisitation = document.querySelector(".search-visitation");
        let visitationList = document.querySelector(".visitation-list");

        if (visitorForm.style.display === 'none' || '') {
            visitorForm.style.display = 'block';
            searchVisitation.style.display = 'none';
            visitationList.style.display = 'none';
        }
        else{
            visitorForm.style.display = 'block';  
            searchVisitation.style.display = 'none';
            visitationList.style.display = 'none';  
        }
    }

</script>