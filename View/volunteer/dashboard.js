
document.addEventListener("DOMContentLoaded", () => {

    // 1️⃣ CHECK AUTH & ROLE
    fetch("/ResQHub/index.php?controller=auth&action=checkAuth")
        .then(res => res.json())
        .then(result => {

            if (!result.success || result.data.role !== "volunteer") {
                alert("Unauthorized access");
                window.location.href = "/ResQHub/View/auth/login.html";
                return;
            }

            // 2️⃣ LOAD DASHBOARD DATA
            loadDashboardData();
        })
        .catch(() => {
            alert("Session error. Please login again.");
            window.location.href = "/ResQHub/View/auth/login.html";
        });


    // 3️⃣ LOGOUT
    const logoutLink = document.querySelector(".sidebar nav a:last-child");
    if (logoutLink) {
        logoutLink.addEventListener("click", (e) => {
            e.preventDefault();

            fetch("/ResQHub/index.php?controller=auth&action=logout")
                .then(() => {
                    window.location.href = "/ResQHub/View/auth/login.html";
                });
        });
    }
});


// 🔹 LOAD DASHBOARD DATA (PLACEHOLDER FOR NOW)
function loadDashboardData() {
    fetch("/ResQHub/index.php?controller=volunteer&action=dashboard")
        .then(res => res.json())
        .then(result => {
            if (!result.success) return;

            document.getElementById("assignedCount").innerText =
                result.data.assigned_requests;

            document.getElementById("completedCount").innerText =
                result.data.completed_tasks;

            document.getElementById("statusText").innerText =
                result.data.status;
        });
}
