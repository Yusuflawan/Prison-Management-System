
<?php

include 'Classes/Database.php';
include 'Classes/Admin.php';

$db_conn = new Database();
$db = $db_conn->connect();

$admin = new Admin($db);

?>
<?php include 'includes/header.php'; ?>

<div class="inmate">
    <?php include 'includes/side-nav.php'; ?>
    <div class="inmate-contents">
        <?php include 'includes/register-inmate.php'; ?>
        <div class="search-inmate">
            <input type="search" id="searchInmate" placeholder="Search inmate by ID or name">
        </div>
        <div class="inmate-list">
            <div class="title">
                <h3>Inmate List</h3>
                <div class="new-inmate">
                    <button type="button" onclick="displayAddInmateForm()">New Inmate</button>
                </div>
            </div>
          
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Id Number</th>
                        <th>Name</th>
                        <th>Crime</th>
                        <th>Sentence</th>
                        <th>Record</th>
                    </tr>
                </thead>
                <tbody id="inmateTableBody">
                    <!-- Table rows will be populated here by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Function to load all inmates on page load
    async function loadInmates() {
        try {
            const response = await fetch('includes/search-inmate.php');
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.text();
            document.getElementById('inmateTableBody').innerHTML = data;
        } catch (error) {
            console.error('Error fetching data:', error);
            document.getElementById('inmateTableBody').innerHTML = '<tr><td colspan="6">Error fetching data</td></tr>';
        }
    }

    // Function to handle the search input
    document.getElementById('searchInmate').addEventListener('input', async function() {
        const query = this.value;

        try {
            const response = await fetch(`includes/search-inmate.php?query=${encodeURIComponent(query)}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.text();
            document.getElementById('inmateTableBody').innerHTML = data;
        } catch (error) {
            console.error('Error fetching data:', error);
            document.getElementById('inmateTableBody').innerHTML = '<tr><td colspan="6">Error fetching data</td></tr>';
        }
    });

    function displayAddInmateForm() {
        let inmateAddForm = document.querySelector(".register-inmate");
        let searchInmate = document.querySelector(".search-inmate");
        let inmateList = document.querySelector(".inmate-list");
        
        if (inmateAddForm.style.display === 'none' || inmateAddForm.style.display === '') {
            inmateAddForm.style.display = 'block';
            searchInmate.style.display = 'none';
            inmateList.style.display = 'none';
        } else {
            inmateAddForm.style.display = 'block';
            // inmateList.style.display = 'block';
            // inmateList.style.display = 'block';
        }
    }

    // Load all inmates initially
    loadInmates();
</script>
<script>
    async function retrieveRecord(id) {
        try {
            const response = await fetch('includes/retrieve-inmate-record.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        if (data.error) {
            alert(data.error);
            return;
        }

       // Create a div to hold the inmate details
       const printDiv = document.createElement('div');
        printDiv.id = 'printDiv';
        printDiv.innerHTML = `
    <style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printDiv, #printDiv * {
            visibility: visible;
        }
        #printDiv {
            position: relative;
            width: 100%;
            margin: 0;
            padding: 1rem;
            box-sizing: border-box;
        }
        #record-title {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: bold;
        }
        #personal-info {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            margin-bottom: 2rem;
        }
        #personal-info img {
            width: 10rem;
            height: 12rem;
            object-fit: cover;
            border: 1px solid #ddd;
            margin-right: 1rem;
        }
        #personal-info table {
            width: 100%;
            border-collapse: collapse;
        }
        #personal-info table, #personal-info th, #personal-info td {
            border: 1px solid #ddd;
        }
        #personal-info th, #personal-info td {
            padding: 0.5rem;
            text-align: left;
        }
        #visitation-history {
            margin-top: 2rem;
        }
        #visitation-history h3 {
            margin-bottom: 1rem;
            font-size: 1.2rem;
            font-weight: bold;
        }
        #visitation-history table {
            width: 100%;
            border-collapse: collapse;
        }
        #visitation-history table, #visitation-history th, #visitation-history td {
            border: 1px solid #ddd;
        }
        #visitation-history th, #visitation-history td {
            padding: 0.5rem;
            text-align: left;
        }
        #visitation-history thead {
            background-color: #f4f4f4;
        }
        /* Ensure printDiv does not split across pages */
        #printDiv {
            page-break-inside: avoid;
        }
    }
</style>



            <h2 id="record-title">Wudil Prison Inmate Record</h2>
            <div id="personal-info">
                <img src="${data['inmateDetails'].image}" alt="inmate image">
                <table>
                    <tr><th>ID Number</th><td>${data['inmateDetails'].idNumber || 'N/A'}</td></tr>
                    <tr><th>Name</th><td>${data['inmateDetails'].firstName || 'N/A'} ${data['inmateDetails'].lastName || 'N/A'}</td></tr>
                    <tr><th>Dob</th><td>${data['inmateDetails'].dob || 'N/A'}</td></tr>
                    <tr><th>Marital status</th><td>${data['inmateDetails'].maritalStatus || 'N/A'}</td></tr>
                    <tr><th>Crime</th><td>${data['inmateDetails'].crime || 'N/A'}</td></tr>
                    <tr><th>Sentence</th><td>${data['inmateDetails'].sentence || 'N/A'}</td></tr>
                    <tr><th>Time in Prison Start</th><td>${data['inmateDetails'].timeServeStart || 'N/A'}</td></tr>
                </table>
            </div>
            <div id="visitation-history">
                <h3>Visitation History</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Visitor Name</th>
                            <th>Phone</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>${
                        data.visitationHistory && data.visitationHistory.length > 0
                            ? data['visitationHistory'].map(visit => `
                                <tr>
                                    <td>${visit.firstName || 'N/A'} ${visit.lastName || 'N/A'}</td>
                                    <td>${visit.phone || 'N/A'}</td>
                                    <td>${visit.date || 'N/A'}</td>
                                </tr>
                            `).join('')
                            : '<tr><td colspan="3">No visitation history available</td></tr>'
                        }
                    </tbody>
                </table>
            </div>

        `;

        // Append the div to the body
        document.body.appendChild(printDiv);

        // Print the content of the div
        window.print();

        // Optionally, remove the div after printing
        document.body.removeChild(printDiv);

        } catch (error) {
            console.error('Error:', error);
            alert('Error retrieving record');
        }
    }


function closeVideoModal() {
    var modal = document.getElementById("uploadModal");
    console.log("Closing modal...");
    modal.style.display = "none";

    var vidContainer = document.getElementById("vidContainer");
    vidContainer.classList.remove("vInactive");

    var body = document.querySelector("body");
    body.classList.remove("inactive-main"); // Remove the class to activate the main page
}

console.log(data.visitationHistory);
</script>
