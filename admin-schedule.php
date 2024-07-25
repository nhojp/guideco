<?php
session_start();
include 'conn.php';
include 'head.php';
include 'admin-nav.php';

// Assume user is authenticated and $admin_id is the logged-in admin's ID
$admin_id = $_SESSION['admin_id']; // This should be set during the login process

// Fetch the schedule data
$query = "SELECT date, description, location FROM schedules WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

$schedule_events = [];
while ($row = $result->fetch_assoc()) {
    $schedule_events[] = [
        'title' => $row['description'] . ' at ' . $row['location'],
        'start' => $row['date']
    ];
}
?>

<main class="flex-fill mt-5">
    <div class="container mt-4">
        <div class="container-fluid mb-5">
            <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
                <div class="row pt-3">
                    <div class="col-md-12 justify-content-center">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-guideco text-white">
                <h5 class="modal-title" id="eventModalLabel">Meeting</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="eventDetails">
                <!-- Event details will be populated here -->
            </div>
            
        </div>
    </div>
</div>

<?php include 'footer.php'?>
<style>
    
    .fc-daygrid-day:hover {
        cursor: pointer;
        background-color: #e0e0e0;
    }
    #calendar {
        max-height: 600px;

    }
    .fc-daygrid-day {
        font-size: 0.8rem; /* Smaller font size */
    }
    .modal-content {
        font-size: 0.9rem; /* Smaller font size for the modal */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: <?php echo json_encode($schedule_events); ?>,
            eventColor: '#007bff',
            dateClick: function(info) {
                var events = calendar.getEvents().filter(event => event.startStr === info.dateStr);
                if (events.length > 0) {
                    var eventDetails = events.map(event => event.title).join('<br>');
                    document.getElementById('eventDetails').innerHTML = eventDetails;
                    $('#eventModal').modal('show');
                } else {
                    document.getElementById('eventDetails').innerHTML = 'No events on ' + info.dateStr;
                    $('#eventModal').modal('show');
                }
            }
        });

        calendar.render();
    });
</script>
