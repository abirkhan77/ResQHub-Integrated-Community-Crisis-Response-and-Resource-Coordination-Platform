
document.addEventListener("DOMContentLoaded", () => {
    loadProfile();

    document.getElementById("profileForm").addEventListener("submit", updateProfile);
    document.getElementById("logoutBtn").onclick = logout;

    document.getElementById("navDashboard").onclick = () => location.href = "dashboard.html";
    document.getElementById("navCreate").onclick = () => location.href = "create_request.html";
    document.getElementById("navMyRequests").onclick = () => location.href = "my_requests.html";
    document.getElementById("navDonations").onclick = () => location.href = "donation.html";
});

function loadProfile() {
    fetch("/ResQHub/index.php?controller=citizen&action=profile")
        .then(res => res.json())
        .then(result => {
            if (!result.success) {
                alert("Failed to load profile");
                return;
            }

            document.getElementById("full_name").value = result.data.full_name;
            document.getElementById("email").value     = result.data.email;
            document.getElementById("phone").value     = result.data.phone ?? "";
            document.getElementById("role").value      = result.data.role;
        })
        .catch(() => alert("Server error"));
}

function updateProfile(e) {
    e.preventDefault();

    const full_name = document.getElementById("full_name").value.trim();
    const phone     = document.getElementById("phone").value.trim();

    if (!full_name) {
        document.getElementById("nameError").innerText = "Name required";
        return;
    }

    fetch("/ResQHub/index.php?controller=citizen&action=updateProfile", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `full_name=${encodeURIComponent(full_name)}&phone=${encodeURIComponent(phone)}`
    })
    .then(res => res.json())
    .then(result => {
        if (!result.success) {
            alert(result.message);
            return;
        }
        alert("Profile updated successfully");
    })
    .catch(() => alert("Server error"));
}

function logout() {
    fetch("/ResQHub/index.php?controller=auth&action=logout")
        .then(() => location.href = "/ResQHub/View/auth/login.html");
}
