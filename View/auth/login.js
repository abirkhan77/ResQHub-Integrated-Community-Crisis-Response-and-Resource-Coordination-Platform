
document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("loginForm");

    if (!form) {
        console.error("loginForm not found in DOM");
        return;
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");

        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        // Clear previous errors
        document.getElementById("emailError").innerText = "";
        document.getElementById("passwordError").innerText = "";

        let valid = true;

        if (email === "") {
            document.getElementById("emailError").innerText = "Email is required";
            valid = false;
        }

        if (password === "") {
            document.getElementById("passwordError").innerText = "Password is required";
            valid = false;
        }

        if (!valid) return;

        fetch("/ResQHub/index.php?controller=auth&action=login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            credentials: "include",   // 🔥 REQUIRED FOR SESSION
            body: JSON.stringify({
                email: email,
                password: password
            })
        })
        .then(response => response.json())
        .then(result => {

            if (!result.success) {
                alert(result.message);
                return;
            }

            const role = result.data.role;

            // ROLE-BASED REDIRECT
            if (role === "citizen") {
                window.location.href = "/ResQHub/View/citizen/dashboard.html";
            }
            else if (role === "volunteer") {
                window.location.href = "/ResQHub/View/volunteer/dashboard.html";
            }
            else if (role === "admin") {
                window.location.href = "/ResQHub/View/admin/dashboard.html";
            }
            else {
                alert("Unknown user role");
            }

        })
        .catch(error => {
            console.error(error);
            alert("Server error. Please try again later.");
        });
    });

});

document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("loginForm");

    if (!form) {
        console.error("loginForm not found in DOM");
        return;
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");

        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        // Clear previous errors
        document.getElementById("emailError").innerText = "";
        document.getElementById("passwordError").innerText = "";

        let valid = true;

        if (email === "") {
            document.getElementById("emailError").innerText = "Email is required";
            valid = false;
        }

        if (password === "") {
            document.getElementById("passwordError").innerText = "Password is required";
            valid = false;
        }

        if (!valid) return;

        fetch("/ResQHub/index.php?controller=auth&action=login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            credentials: "include",   // 🔥 REQUIRED FOR SESSION
            body: JSON.stringify({
                email: email,
                password: password
            })
        })
        .then(response => response.json())
        .then(result => {

            if (!result.success) {
                alert(result.message);
                return;
            }

            const role = result.data.role;

            // ROLE-BASED REDIRECT
            if (role === "citizen") {
                window.location.href = "/ResQHub/View/citizen/dashboard.html";
            }
            else if (role === "volunteer") {
                window.location.href = "/ResQHub/View/volunteer/dashboard.html";
            }
            else if (role === "admin") {
                window.location.href = "/ResQHub/View/admin/dashboard.html";
            }
            else {
                alert("Unknown user role");
            }

        })
        .catch(error => {
            console.error(error);
            alert("Server error. Please try again later.");
        });
    });

});
