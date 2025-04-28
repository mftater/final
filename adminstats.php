<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    echo "<script>
                alert('You do not have access to the admin dashboard');
                window.location.href = './loginPage.php';
              </script>";
    exit();
}

$count_users_qry = "SELECT COUNT(*) as countUsers FROM users";
$returned = $link->query($count_users_qry);
$count_users = $returned->fetch_assoc()['countUsers'];

$count_admin_qry = "SELECT COUNT(*) as countAdmin FROM users WHERE is_admin = 1";
$returned = $link->query($count_admin_qry);
$count_admin = $returned->fetch_assoc()['countAdmin'];

$count_connections_qry = "SELECT COUNT(*) as countConnections from family_connections";
$returned = $link->query($count_connections_qry);
$count_connections = $returned->fetch_assoc()['countConnections'];

$count_approved_qry = "SELECT COUNT(*) as countApproved from family_connections WHERE status = 'approved'";
$returned = $link->query($count_approved_qry);
$count_approved = $returned->fetch_assoc()['countApproved'];

$count_reject_qry = "SELECT COUNT(*) as countReject from family_connections WHERE status = 'rejected'";
$returned = $link->query($count_reject_qry);
$count_reject = $returned->fetch_assoc()['countReject'];

$count_pending_qry = "SELECT COUNT(*) as countPending from family_connections WHERE status = 'pending'";
$returned = $link->query($count_pending_qry);
$count_pending = $returned->fetch_assoc()['countPending'];

$count_events_qry = "SELECT COUNT(*) as eventCount from events";
$returned = $link->query($count_events_qry);
$count_events = $returned->fetch_assoc()['eventCount'];

$count_shared_qry = "SELECT COUNT(DISTINCT event_id) AS countShared FROM shared_events";
$returned = $link->query($count_shared_qry);
$count_shared = $returned->fetch_assoc()['countShared'];

$personal_events = $count_events - $count_shared;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About – Collaborative Calendar</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="./darkmodescript/dark.css">
    <link rel="stylesheet" href="./calendar.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="navbar"></div>
    <nav class="second">
        <a href="./showcalendar_inJS.php">Calendar</a>
        <a href="./proposal.html">Proposal</a>
        <a href="./features.html">Features</a>
        <a href="./about.html">About</a>
    </nav>
    <h3>Admin Dashboard, Stat Distributions Below</h3>

    <h4>User Stats</h4>
    <p>Total Users: <?php echo $count_users; ?></p>
    <p>Number of Admins: <?php echo $count_admin;?></p>

    <h4 style="margin-top:50px; margin-bottom: 10px;">Family Connections Bar Graph</h4>
    <div id="bar-container" style="position: relative; height:400px; width:800px; margin-top: 100px;">
        <canvas id="familyConnectionsBar"></canvas>
    </div>

    <h4 style="margin-top:50px; margin-bottom: 10px;">Event Type Distribution</h4>
    <div id="pie-container" style="position: relative; height:400px; width:800px; margin-top: 100px;">
        <canvas id="eventsBar"></canvas>
    </div>
    <button id="darkModeToggle">Toggle Dark Mode</button>

    <script src="../homepage/script.js" ></script>
    <script src="./darkmodescript/dark.js"></script>
    <script>
        
        /* This builds the Family Connections Bar Chart*/
        const countConnections = <?php echo $count_connections; ?>;
        const countApproved = <?php echo $count_approved; ?>;
        const countRejects = <?php echo $count_reject; ?>;
        const countPending = <?php echo $count_pending; ?>;


        const barData = {
            labels: ['Total Number of Connections', 'Total Number Approved', 'Total Number Rejected', 'Total Number Pending'],
            datasets: [{
                label:'Family_Connections Table Statistics',
                data: [countConnections, countApproved, countRejects, countPending],
                backgroundColor: ['#16a085', '#2ecc71', '#e74c3c', '#f39c12'],
                hoverBackgroundColor: ['#A9A9A9'],
                borderColor: ['#1abc9c', '#27ae60', '#c0392b', '#e67e22'],
                borderWidth: 1
            }]
        };

        const chartOptions = {
            maintainAspectRatio: false,
            indexAxis : 'y',
            responsive: true,
            scales: {
                x: {
                    beginAtZero : true
                }
            }
        };

        const barGraph = document.getElementById('familyConnectionsBar').getContext('2d');
        barGraph.canvas.parentNode
        new Chart(barGraph, {
            type: 'bar',
            data: barData,
            options: chartOptions
        });
        /*This builds the events pie chart */
        const personal_events = <?php echo $personal_events;?>;
        const shared_events = <?php echo $count_shared;?>;
        const eventPieData = {
            labels: ['Number of Personal Events', 'Number of Shared Events'],
            datasets: [{
                label:'Event Type Distribution',
                data: [personal_events, shared_events],
                backgroundColor: ['#16a085', '#2ecc71'],
                hoverBackgroundColor: ['#A9A9A9'],
                borderColor: ['#1abc9c', '#27ae60'],
                borderWidth: 1
            }]
        };
        const pieOptions = {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    enabled: true
                }
            }
        };

        const pieGraph = document.getElementById('eventsBar').getContext('2d');
        new Chart(pieGraph, {
            type: 'pie',
            data: eventPieData,
            options: pieOptions
        })

    </script>
</body>
</html>