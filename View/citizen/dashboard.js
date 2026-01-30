// NAVIGATION
document.getElementById("btnCreate").onclick = () => {
    window.location.href = "/ResQHub/View/citizen/create_request.html";
};

document.getElementById("btnMyRequests").onclick = () => {
    window.location.href = "/ResQHub/View/citizen/my_requests.html";
};

document.getElementById("btnDonations").onclick = () => {
    window.location.href = "/ResQHub/View/citizen/donation.html";
};

document.getElementById("btnProfile").onclick = () => {
    window.location.href = "/ResQHub/View/citizen/profile.html";
};

// SIDEBAR
document.getElementById("navCreate").onclick = () => {
    window.location.href = "/ResQHub/View/citizen/create_request.html";
};

document.getElementById("navMyRequests").onclick = () => {
    window.location.href = "/ResQHub/View/citizen/my_requests.html";
};

document.getElementById("navDonations").onclick = () => {
    window.location.href = "/ResQHub/View/citizen/donation.html";
};

document.getElementById("navProfile").onclick = () => {
    window.location.href = "/ResQHub/View/citizen/profile.html";
};

// LOGOUT (FIXED)
document.getElementById("logoutBtn").onclick = () => {
    fetch("/ResQHub/index.php?controller=auth&action=logout")
        .then(res => res.json())
        .then(() => {
            window.location.href = "/ResQHub/View/auth/login.html";
        });
};