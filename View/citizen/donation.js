
document.addEventListener("DOMContentLoaded", function () {

    /* =========================
       DONATION FORM
    ========================= */
    const form = document.getElementById("donationForm");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const amount   = document.getElementById("amount").value.trim();
        const currency = document.getElementById("currency").value;
        const purpose  = document.getElementById("purpose").value.trim();

        if (!amount || isNaN(amount) || Number(amount) <= 0) {
            alert("Please enter a valid donation amount.");
            return;
        }

        if (!currency) {
            alert("Please select a currency.");
            return;
        }

        fetch("/ResQHub/index.php?controller=donation&action=create", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body:
                `amount=${encodeURIComponent(amount)}` +
                `&currency=${encodeURIComponent(currency)}` +
                `&purpose=${encodeURIComponent(purpose)}`
        })
        .then(res => res.json())
        .then(result => {
            if (!result.success) {
                alert(result.message);
                return;
            }

            alert("Thank you for your donation.");
            window.location.href = "/ResQHub/View/citizen/dashboard.html";
        })
        .catch(() => {
            alert("Server error. Please try again.");
        });
    });

    /* =========================
       SIDEBAR NAVIGATION
    ========================= */
    const navItems = document.querySelectorAll(".nav li");

    navItems.forEach(item => {
        item.addEventListener("click", function () {
            const text = this.innerText.trim();

            if (text === "Dashboard") {
                window.location.href = "/ResQHub/View/citizen/dashboard.html";
            }
            if (text === "Create Request") {
                window.location.href = "/ResQHub/View/citizen/create_request.html";
            }
            if (text === "My Requests") {
                window.location.href = "/ResQHub/View/citizen/my_requests.html";
            }
            if (text === "Donations") {
                window.location.href = "/ResQHub/View/citizen/donation.html";
            }
            if (text === "Profile") {
                window.location.href = "/ResQHub/View/citizen/profile.html";
            }
        });
    });

    /* =========================
       LOGOUT
    ========================= */
    document.querySelector(".logout-btn").addEventListener("click", function () {
        fetch("/ResQHub/index.php?controller=auth&action=logout")
            .then(() => {
                window.location.href = "/ResQHub/View/auth/login.html";
            });
    });

});
