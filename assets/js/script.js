const API_URL = "/eventsys";
const UI_PATH = "/eventsys/view";

function validateEmail(email) {
  // Basic email validation using a regular expression
  const regex =
    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return regex.test(String(email).toLowerCase());
}

function getFormDataJson(form) {
  const formData = new FormData(form);
  const json = {};
  formData.forEach((value, key) => {
    json[key] = value;
  });
  return JSON.stringify(json);
}

async function handleResponse(response) {
  if (!response.ok) {
    throw new Error("Network response was not ok");
  }
  return await response.json();
}

function handleError(error) {
  console.error("Error:", error);
  alert("An error occurred. Please try again.");
}

// User Registration Form Submission
document.addEventListener("DOMContentLoaded", () => {
  const registerForm = document.getElementById("registerForm");

  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      e.preventDefault();

      // Client-side validation
      const formData = new FormData(registerForm);
      if (
        !formData.get("first_name") ||
        !formData.get("last_name") ||
        !formData.get("username") ||
        !formData.get("email") ||
        !formData.get("password") ||
        !formData.get("mobile")
      ) {
        alert("Please fill in all required fields.");
        return;
      }

      if (!validateEmail(formData.get("email"))) {
        alert("Invalid email format.");
        return;
      }

      if (formData.get("password").length < 8) {
        alert("Password must be at least 8 characters long.");
        return;
      }

      fetch(`${API_URL}/register`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: getFormDataJson(registerForm),
      })
        .then(handleResponse)
        .then((data) => {
          alert(data.message);
          if (data.status === "success") {
            window.location = `${UI_PATH}/login.php`;
          }
        })
        .catch(handleError);
    });
  }
});

// User Login Form Submission
document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm");

  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(loginForm);

      // Client-side validation
      if (!formData.get("username") || !formData.get("password")) {
        alert("Please fill in all required fields.");
        return;
      }

      fetch(`${API_URL}/login`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: getFormDataJson(loginForm),
      })
        .then(handleResponse)
        .then((data) => {
          alert(data.message);
          if (data.status === "success") {
            window.location = `${UI_PATH}/profile.php`;
          }
        })
        .catch(handleError);
    });
  }
});

// Admin Registration Form Submission
document.addEventListener("DOMContentLoaded", () => {
  const adminRegisterForm = document.getElementById("adminRegisterForm");

  if (adminRegisterForm) {
    adminRegisterForm.addEventListener("submit", function (e) {
      e.preventDefault();

      // Client-side password matching validation
      const password = document.getElementById("password").value;
      const cPassword = document.getElementById("cPassword").value;
      if (password !== cPassword) {
        alert("Passwords don't match");
        return;
      }

      fetch(`${API_URL}/admin/register`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: getFormDataJson(adminRegisterForm),
      })
        .then(handleResponse)
        .then((data) => {
          alert(data.message);
          if (data.status === "success") {
            window.location = `${UI_PATH}/admin/login.php`;
          }
        })
        .catch(handleError);
    });
  }
});

// Admin Login Form Submission
document.addEventListener("DOMContentLoaded", () => {
  const adminLoginForm = document.getElementById("adminLoginForm");

  if (adminLoginForm) {
    adminLoginForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(adminLoginForm);

      // Client-side validation
      if (!formData.get("username") || !formData.get("password")) {
        alert("Please fill in all required fields.");
        return;
      }

      fetch(`${API_URL}/admin/login`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: getFormDataJson(adminLoginForm),
      })
        .then(handleResponse)
        .then((data) => {
          alert(data.message);
          if (data.status === "success") {
            window.location = `${UI_PATH}/admin/review-events.php`;
          }
        })
        .catch(handleError);
    });
  }
});

// Event Submission Form Submission
document.addEventListener("DOMContentLoaded", () => {
  const bookingForm = document.getElementById("bookingForm");

  if (bookingForm) {
    bookingForm.addEventListener("submit", function (e) {
      e.preventDefault();

      // Get form values
      const data = {
        eventType: document.getElementById("eventType").value,
        eventDate: document.getElementById("eventDate").value,
        eventTime: document.getElementById("eventTime").value,
        numGuests: document.getElementById("guests").value,
        package: document.getElementById("package").value,
        addons: Array.from(
          document.querySelectorAll('input[name="addons[]"]:checked')
        ).map((checkbox) => checkbox.value),
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        phone: document.getElementById("phone").value,
        requests: document.getElementById("requests").value,
      };

      // Client-side validation
      if (
        !data.eventType ||
        !data.eventDate ||
        !data.eventTime ||
        !data.numGuests ||
        !data.package ||
        !data.name ||
        !data.email ||
        !data.phone
      ) {
        alert("Please fill in all required fields.");
        return;
      }

      if (!validateEmail(data.email)) {
        alert("Invalid email format.");
        return;
      }

      fetch(`${API_URL}/submit-event`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      })
        .then(handleResponse)
        .then((data) => {
          alert(data.message);
          if (data.status === "success") {
            bookingForm.reset();
          }
        })
        .catch(handleError);
    });
  }
});

// Admin Event Review (approve/reject)
document.addEventListener("DOMContentLoaded", () => {
  const approveButtons = document.querySelectorAll(".approve-event");
  const rejectButtons = document.querySelectorAll(".reject-event");

  approveButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const eventId = this.dataset.eventId;
      handleEventReview(eventId, "approve");
    });
  });

  rejectButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const eventId = this.dataset.eventId;
      handleEventReview(eventId, "reject");
    });
  });

  function handleEventReview(eventId, action) {
    fetch(`${API_URL}/admin/review-events`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        action: action,
        event_id: eventId,
      }),
    })
      .then(handleResponse)
      .then((data) => {
        alert(data.message);
        if (data.status === "success") {
          location.reload();
        }
      })
      .catch(handleError);
  }
});

// User/Admin Profile Update
document.addEventListener("DOMContentLoaded", () => {
  const profileForms = document.querySelectorAll(".profile-form");

  profileForms.forEach((form) => {
    const editButton = form.querySelector(".edit-profile-btn");
    const saveButton = form.querySelector(".save-profile-btn");
    const profileFields = form.querySelectorAll(".profile-field");

    saveButton.style.display = "none";
    profileFields.forEach((field) => (field.readOnly = true));

    editButton.addEventListener("click", function () {
      profileFields.forEach((field) => (field.readOnly = false));
      editButton.style.display = "none";
      saveButton.style.display = "inline-block";
    });

    saveButton.addEventListener("click", function () {
      const data = {};
      profileFields.forEach((field) => (data[field.name] = field.value));

      fetch(`${API_URL}/update-profile`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      })
        .then(handleResponse)
        .then((data) => {
          alert(data.message);
          if (data.status === "success") {
            profileFields.forEach((field) => (field.readOnly = true));
            editButton.style.display = "inline-block";
            saveButton.style.display = "none";
          }
        })
        .catch(handleError);
    });
  });
});
