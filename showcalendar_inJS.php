<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ./loginPage.php"); // Redirect to login if not logged in
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>My Calendar</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="./darkmodescript/dark.css">
  <link rel="stylesheet" href="./calendar.css">
  <style type="text/css">

  </style>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="calendar_functions.js"></script>
</head>
<body>
  <div id="navbar"></div>
  <nav class="second">
    <a href="./about.html">About</a>
    <a href="./proposal.html">Proposal</a>
    <a href="./features.html">Features</a>
  </nav>
  <button id="darkModeToggle">Toggle Dark Mode</button>
  <h1>Select a Month/Year Combination</h1>
  <form id="datePicker"></form>
  <div id="myCal"></div>
  
<!--This is the add event toggle -->
  <div id="eventtoggle" class="modal">
    <div class="modal-content">
      <h2>Add Event</h2>
      <form action="addEvent.php" method="POST">
          <label for="event_title">Event Title:</label><br>
          <input type="text" id="event_title" name="event_title" required><br><br>

          <label for="event_desc">Event Description:</label><br>
          <textarea id="event_desc" name="event_desc" required></textarea><br><br>

          <label for="event_date">Event Date:</label><br>
          <input type="date" id="event_date" name="event_date" required><br><br>

          <label for="event_time">Event Time:</label><br>
          <input type="time" id="event_time" name="event_time" required><br><br>

          <label for="event_att">Event Added By:</label><br>
          <input type="text" id="event_att" name="event_att" readonly><br><br>

<!--This is the section where the user can use a checkbox to share with family, then a selector appears letting them choose-->
          <label for="share">Share with Family Members:</label>
            <input type="checkbox" id="share" name="share"><br><br>

            <div id="family_members_select" style="display: none;">
            <label for="family_members">Select Family Members to Share with:</label><br>
            <select id="family_members" name="family_members[]" multiple>
                <!-- Dynamically populated with family members -->
            </select>
            </div><br><br>

          <button type="submit">Add Event</button>
      </form>
    </div>
  </div>

<!--This is the event detail toggle -->
  <div id= "event-details" class = "detailsmodal">
      <div id="detail-modal">
          <span class="detailsclose">&times;</span>
          <h3 id="detailTitle">Event Details</h3>
          <p> <strong>Time:</strong><span id="eventTime"></span></p>
          <p> <strong>Created By:</strong><span id="eventCreator"></span></p>
          <p> <strong>Description:</strong><span id="eventDescription"></span></p>
          <button id="delete" class="delete-button">Delete Event?</button>
      </div>
  </div>


<!--Button to show the add family member form -->
  <button id="showFamilyForm">Add Family Member</button>

  <div id="addFamilyMemberForm" class="form-container" style="display: none;">
        <h3>Add Family Member</h3>
        <form action="add_family.php" method="POST">
            <label for="family_member_id">Family Member ID:</label>
            <input type="text" name="family_member_id" id="family_member_id" required>

            <label for="relationship">Relationship:</label>
            <select name="relationship" id="relationship">
                <option value="sibling">Sibling</option>
                <option value="parent">Parent</option>
                <option value="child">Child</option>
                <option value="Spouse">Spouse</option>
            </select>

            <button type="submit">Send Request</button>
        </form>
    </div>

<!--Success message generation based on what is passed back from add_family.php -->
    <?php if (isset($_GET['message']) && $_GET['message'] == 'request_sent'): ?>
        <div class="success-message">
            Family member request sent successfully!
    </div>
    <?php endif; ?>
    
    <h3>Pending Requests From Family Members</h3>
    <div id="pendingRequests">

    </div>

    <!--Beginning of this script generates the list of requests for the user, pulls data using the current user as the family member id in the family table -->       
  <script type="text/javascript">
$.getJSON("pending.php", function(data) {
    var pendingRequestsDiv = $("#pendingRequests");

    if (data.length > 0) {
        var requestsList = "<ul>";
        data.forEach(function(request) {
            requestsList += "<li>";
            requestsList += "Request from User ID: " + request.user_id + "<br>";
            requestsList += "Relationship: " + request.relationship + "<br>";
            requestsList += '<form action="approve_request.php" method="POST">';
            requestsList += '<input type="hidden" name="request_id" value="' + request.id + '">';
            requestsList += '<button type="submit" name="action" value="approve">Approve</button>';
            requestsList += '<button type="submit" name="action" value="reject">Reject</button>';
            requestsList += '</form>';
            requestsList += "</li>";
        });
        requestsList += "</ul>";
        pendingRequestsDiv.html(requestsList);
    } else {
        pendingRequestsDiv.html("<p>No pending requests.</p>");
    }
});
  var username = "<?php echo $_SESSION['username']; ?>";
  $(document).ready(function(){
    // build the picker form and initialize the calendar with the current date
    buildDateForm();
    calendar(); // Initialize the calendar with the current month/year

    // Event listener for the "Go!" button
    $("#submit").click(function() {
      var newMonth = $('#month').val(); 
      var newYear = $('#year').val(); 
      var newDate = new Date(newYear, newMonth, 1); 
      calendar(newDate);
      return false; 
    });
  });
  </script>
  
  <script src="../homepage/script.js"></script>
  <script src="./darkmodescript/dark.js"></script>
  <script src="./addFamily.js"></script>
</body>
</html>