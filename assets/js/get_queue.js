function validateStudentNumber() {
    var studentNumber = $("#studentNumberInput").val();
    if (studentNumber !== "") {
        var regex = /^\d*$/;
        if (!regex.test(studentNumber)) {
        // alert("Please enter only numeric values in the student number.");
        $('#inputerrordetailsModal').modal('show');
            return false;
        }
    } 
    return true;
}

function validateStudentNo() {
    var studentNumber = $("#studentNumberInput").val();
    if (parseInt(studentNumber) < 20020000) {
        // alert('Please input a valid student number.');
        $('#errordetailsModal').modal('show');
        return false;
    }
    return true;
}

function validateEmail() {
    var email = $("#emailInput").val();
    var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email !== "" && !pattern.test(email)) {
        return false;
    }
    return true;
}
// Process Queue button event handler
//  $("#processQueueButton").on("click", function () {
function processQueue(){
    var name = $("#nameInput").val();
    var studentNumber = $("#studentNumberInput").val();
    var selectedCustomer = $("#customerDropdown").text().trim();
    var selectedService = $("#serviceDropdown"  ).text().trim();
    var selectedPaymentFor = $("#paymentForDropdown").text().trim();
    var selectedPaymentMode = $("#paymentModeDropdown").text().trim();
    var email = $("#emailInput").val();
    var id = $("#idinput").val();
    var status = "pending";

    // Check required fields and validate student number if provided
    if (selectedCustomer === "Choose:" || selectedService === "Choose:" || selectedPaymentFor === "Choose:" || selectedPaymentMode === "Choose:" || (studentNumber !== "" && !validateStudentNumber()) || name === "") {
        // alert("Please ensure all required options are selected and your name is provided. If you entered a student number, make sure it contains only numeric values.");
        $('#posterrordetailsModal').modal('show');
        return;
    }

    // Validate email format
    if (!validateEmail()) {
        // alert("Invalid email address provided, please put a proper email.");
        $('#emailerrordetailsModal').modal('show');
        return;
    }
    // Show loading animation
    
    $('body').waitMe({
        effect : 'bounce',
        text : 'Please Wait... This might take a while.',
        bg : 'rgba(255,255,255,0.7)',
        color : '#ed143d',
        maxSize : '',
        waitTime : -1,
        textPos : 'vertical',

    });
    // alert('waitme');
        
    // Proceed with form submission
    var url = selectedCustomer.toLowerCase().includes("priority") ? "priority_queue.php" : "regular_queue.php";
    var baseUrl = "http://localhost/queue-system/Queue/insert_queue/";

    grecaptcha.ready(function() {
        grecaptcha.execute('6LcHMQUqAAAAAFVuePktntV_NN1C9_mpMsNEWwjr', { action: 'queue_submit' }).then(function(token) {
            $.ajax({
                type: "POST",
                url: baseUrl,    
                data: {
                    id: id,
                    name: name,
                    studentNumber: studentNumber,
                    selectedCustomer: selectedCustomer,
                    selectedService: selectedService,
                    selectedPaymentFor: selectedPaymentFor,
                    selectedPaymentMode: selectedPaymentMode,
                    email: email,
                    status: status,
                    recaptcha_response: token
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        showQueueDetails(response.queueNumber, response.average_service_time);
                    } else {
                        // Hide loading animation
                        $('body').waitMe('hide');
                        $('#errordetailsModal').modal('show');
                    }
                },
                error: function(xhr, error) {
                    $('body').waitMe('hide');
                    console.error(xhr.responseText);
                    // alert("Error occurred during AJAX request: " + error);
                    showAlert(error);
                }
            });
        });
    });
}
// });

function showQueueDetails(queueNumber, averageServiceTime) {
    // Hide loading animation
    $('body').waitMe('hide');
    $("#queueNumberDisplay").text(queueNumber);

    var minutes = Math.floor(averageServiceTime);
    var seconds = Math.round((averageServiceTime - minutes) * 60);

    var formattedTime = minutes > 0 ? `${minutes} minute${minutes > 1 ? 's' : ''} and ${seconds} second${seconds > 1 ? 's' : ''}` : `${seconds} second${seconds > 1 ? 's' : ''}`;

    $("#estimatedWaitTime").text("Average Service Time: " + formattedTime);

    $("input[type='text']").val('');
    $("input[type='email']").val('');
    $(".dropdown-toggle").text('Choose:');

    $("#queueDetailsModal").modal("show");
}

function showAlert(message) {
    // Hide loading animation
    $('body').waitMe('hide');
    $("#queueNumberDisplay").text("<p>Something went Wrong 404</p>");

    $("#estimatedWaitTime").text(message);

    $("input[type='text']").val('');
    $("input[type='email']").val('');
    $(".dropdown-toggle").text('Choose:');

    $("#queueDetailsModal").modal("show");
}

$('#queueDetailsModal').on('hidden.bs.modal', function() {
    location.reload();
});
$(document).ready(function () {
$(document).on("click", "#processQueueButton", function () {
        processQueue();
});

// Handle dropdown item click
$(".dropdown-menu").on("click", "a.dropdown-item", function () {
    var selectedText = $(this).text();
    $(this).closest(".dropdown-menu").prev(".dropdown-toggle").text(selectedText);
});

document.getElementById("studentNumberInput").addEventListener("input", function (e) {
    // Remove non-numeric characters
    var value = this.value;
    this.value = value.replace(/\D/g, '');
});

});
