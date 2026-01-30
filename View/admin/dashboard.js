document.addEventListener("DOMContentLoaded", () => {
    fetch("/ResQHub/index.php?controller=admin&action=dashboard")
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            const d = data.data;
            document.getElementById("totalUsers").innerText = d.total_users;
            document.getElementById("activeRequests").innerText = d.active_requests;
            document.getElementById("totalDonations").innerText = d.total_donations;
            document.getElementById("totalTransactions").innerText = d.total_transactions;
        });
});

document.getElementById("logoutBtn").onclick = () => {
    fetch("/ResQHub/index.php?controller=auth&action=logout")
        .then(() => window.location.href = "/ResQHub/View/auth/login.html");
};document.addEventListener("DOMContentLoaded", () => {
    fetch("/ResQHub/index.php?controller=admin&action=dashboard")
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            const d = data.data;
            document.getElementById("totalUsers").innerText = d.total_users;
            document.getElementById("activeRequests").innerText = d.active_requests;
            document.getElementById("totalDonations").innerText = d.total_donations;
            document.getElementById("totalTransactions").innerText = d.total_transactions;
        });
});

document.getElementById("logoutBtn").onclick = () => {
    fetch("/ResQHub/index.php?controller=auth&action=logout")
        .then(() => window.location.href = "/ResQHub/View/auth/login.html");
};