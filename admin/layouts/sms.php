<?php 
if (isset($_SESSION['message']) && is_array($_SESSION['message'])) {
    $status = isset($_SESSION['message']['status']) ? $_SESSION['message']['status'] : null;
    $sms = isset($_SESSION['message']['sms']) ? $_SESSION['message']['sms'] : '';

    // Output the alert based on the status
    if ($status == 'success') {
        echo "<div id='alert-message' class='alert alert-success alert-dismissible fade show' role='alert'>" . htmlspecialchars($sms) .
            "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } elseif ($status == 'error') {
        echo "<div id='alert-message' class='alert alert-danger alert-dismissible fade show' role='alert'>" . htmlspecialchars($sms) .
            "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } elseif ($status == 'warning') {
        echo "<div id='alert-message' class='alert alert-warning alert-dismissible fade show' role='alert'>" . htmlspecialchars($sms) .
            "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }

    // Clear the session message after displaying
    unset($_SESSION['message']);
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Check if the alert-message exists
        if ($('#alert-message').length) {
            // Automatically hide the alert after 5 seconds (5000 milliseconds)
            setTimeout(function() {
                $('#alert-message').alert('close');
            }, 5000); // Change the time as needed
        }
    });
</script>
