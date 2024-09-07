function registerUser() {
  const fname = document.getElementById("first_name");
  const lname = document.getElementById("last_name");
  const dob = document.getElementById("dob");

  const line1 = document.getElementById("line1");
  const line2 = document.getElementById("line2");
  const line3 = document.getElementById("line3");
  const zipcode = document.getElementById("zip");
  const province = document.getElementById("province");
  const mobile = document.getElementById("mobile");

  const email = document.getElementById("your_email");
  const password = document.getElementById("password");
  const cPassword = document.getElementById("cPassword");

  const checkbox = document.getElementById("checkbox");

  if (province.value == "0") {
    province.focus();
    alert("Select your Province");
  } else if (!checkbox.checked) {
    checkbox.focus();
    alert("Accept terms & conditions to continue.");
  } else {
    if (!password.value === cPassword.value) {
      alert("Passwords doesn't match");
    } else {
      const data = {
        fname: fname.value,
        lname: lname.value,
        email: email.value,
        password: password.value,
        mobile: mobile.value,
        dob: dob.value,
        address: {
          line1: line1.value,
          line2: line2.value,
          line3: line3.value,
          zipcode: zipcode.value,
          province: province.value,
        },
      };
      const jsonData = JSON.stringify(data);

      const request = new XMLHttpRequest();

      request.onreadystatechange = function () {
        if (request.readyState == 4) {
          const res = request.responseText;
          if (request.status == 200) {
            // alert(res);
            let resObj = JSON.parse(res);
            window.location = "login.html";
          } else {
            console.log("Bad Request", request.status, res);
            alert(res);
          }
        }
      };

      request.open("POST", "http://localhost:3000/api/users/");
      request.setRequestHeader("Content-Type", "application/json");
      request.send(jsonData);
    }
  }
}

function registerAdmin() {
  const fname = document.getElementById("first_name");
  const lname = document.getElementById("last_name");

  const mobile = document.getElementById("mobile");
  const email = document.getElementById("your_email");
  const password = document.getElementById("password");
  const cPassword = document.getElementById("cPassword");

  if (password.value != cPassword.value) {
    alert("Passwords doesn't match");
  } else {
    const data = {
      title: title.value,
      fname: fname.value,
      lname: lname.value,
      nwi: nwi.value,
      email: email.value,
      password: password.value,
      mobile: mobile.value,
      position: position.value,
    };
    const jsonData = JSON.stringify(data);

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
      if (request.readyState == 4) {
        const res = request.responseText;
        if (request.status == 200) {
          // alert(res);
          let resObj = JSON.parse(res);
          // window.sessionStorage.setItem("hms_admin", resObj);
          // window.location = 'admin_dashboard/admin.html';
          window.location = "adminLogin.html";
        } else {
          console.log("Bad Request", request.status, res);
          alert(res);
        }
      }
    };

    request.open("POST", "http://localhost:3000/api/admins/");
    request.setRequestHeader("Content-Type", "application/json");
    request.send(jsonData);
  }
}

function loadRegisterPage(userType) {
  if (userType == "user") {
    document.getElementById("dob").max = new Date().toISOString().slice(0, 10);
  }
}
