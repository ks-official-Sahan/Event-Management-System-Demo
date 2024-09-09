<?php
// Include the base layout
include 'layout.php';
?>

<div class="row justify-content-center p-4">
    <div class="col-md-6">
        <h2>Submit New Event</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo (strpos($message, 'successful') !== false) ? 'success' : 'danger'; ?>"
                role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form id="bookingForm" method="POST" action="/eventsys/submit-event">
            <label for="eventType">Event Type:</label>
            <select id="eventType" name="eventType" class="form-control mb-3" required>
                <option value="">Select Event Type</option>
                <option value="1">Birthday</option>
                <option value="2">Wedding</option>
                <option value="3">Anniversary</option>
                <option value="4">Gathering</option>
                <option value="5">Other</option>
            </select>

            <label for="eventDate">Event Date:</label>
            <input type="date" id="eventDate" name="eventDate" class="form-control mb-3" required />

            <label for="eventTime">Event Time:</label>
            <input type="time" id="eventTime" name="eventTime" class="form-control mb-3" required />

            <label for="guests">Number of Guests:</label>
            <input type="number" id="guests" name="guests" min="1" max="500" class="form-control mb-3" required />

            <label for="package">Package:</label>
            <select id="package" name="package" class="form-control mb-3" required>
                <option value="1">Basic</option>
                <option value="2">Deluxe</option>
                <option value="3">Premium</option>
            </select>

            <fieldset class="mb-3">
                <legend>Add-ons (Optional):</legend>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="dj" name="addons[]" value="dj">
                    <label class="form-check-label" for="dj">DJ Service ($100)</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="photographer" name="addons[]"
                        value="photographer">
                    <label class="form-check-label" for="photographer">Photographer ($200)</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="catering" name="addons[]" value="catering">
                    <label class="form-check-label" for="catering">Catering Service ($300)</label>
                </div>
            </fieldset>

            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" class="form-control mb-3" required />

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control mb-3" required />

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" class="form-control mb-3" required />

            <label for="requests">Special Requests:</label>
            <textarea id="requests" name="requests" rows="4" class="form-control mb-3"
                placeholder="Any special requests or instructions..."></textarea>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="terms" name="terms" required />
                <label class="form-check-label" for="terms"> I agree to the <a href="#">terms and
                        conditions</a>.</label>
            </div>

            <button type="submit" class="btn btn-primary">Submit Booking</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const bookingForm = document.getElementById("bookingForm");

        bookingForm.addEventListener("submit", function (e) {
            e.preventDefault();

            // Get form values
            const eventType = document.getElementById("eventType").value;
            const eventDate = document.getElementById("eventDate").value;
            const eventTime = document.getElementById("eventTime").value;
            const numGuests = document.getElementById("guests").value;
            const packageSelected = document.getElementById("package").value;
            const addons = Array.from(document.querySelectorAll('input[name="addons"]:checked'))
                .map(checkbox => checkbox.value);
            const contactName = document.getElementById("name").value;
            const contactEmail = document.getElementById("email").value;
            const contactPhone = document.getElementById("phone").value;
            const specialRequests = document.getElementById("requests").value;

            const data = {
                eventType: eventType,
                eventDate: eventDate,
                eventTime: eventTime,
                guests: numGuests,
                package: packageSelected, // Include the 'package' value
                addons: addons,
                name: contactName,
                email: contactEmail,
                phone: contactPhone,
                requests: specialRequests
            };

            const jsonData = JSON.stringify(data);

            const request = new XMLHttpRequest();

            request.onreadystatechange = function () {
                if (request.readyState == 4) {
                    const res = request.responseText;
                    // console.log(res);
                    if (request.status == 200) {
                        let resObj = JSON.parse(res);
                        alert(resObj.message);
                        if (resObj.status === 'success') {
                            bookingForm.reset(); // Reset the form on success
                        }
                    } else {
                        console.log("Bad Request", request.status, res);
                        alert(res);
                    }
                }
            };

            request.open("POST", "/eventsys/submit-event");
            request.setRequestHeader("Content-Type", "application/json");
            request.send(jsonData);
        });
    });
</script>