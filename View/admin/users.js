
document.addEventListener("DOMContentLoaded", () => {
    loadUsers();

    document.getElementById("logoutBtn").onclick = () => {
        fetch("/ResQHub/index.php?controller=auth&action=logout", {
            credentials: "same-origin"
        })
        .then(() => location.href = "/ResQHub/View/auth/login.html");
    };
});

function loadUsers() {
    fetch("/ResQHub/index.php?controller=admin&action=listUsers", {
        credentials: "same-origin"
    })
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById("usersTable");
        tbody.innerHTML = "";

        if (!data.success || !Array.isArray(data.data) || data.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="empty">No users found</td></tr>`;
            return;
        }

        data.data.forEach(user => {
            const statusClass = user.account_status === "active" ? "active" : "suspended";

            const actionBtn =
                user.account_status === "active"
                    ? `<button class="action-btn suspend" onclick="updateStatus(${user.user_id}, 'suspended')">Suspend</button>`
                    : `<button class="action-btn activate" onclick="updateStatus(${user.user_id}, 'active')">Activate</button>`;

            tbody.innerHTML += `
                <tr>
                    <td>${user.user_id}</td>
                    <td>${user.full_name}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td><span class="status ${statusClass}">${user.account_status}</span></td>
                    <td>${actionBtn}</td>
                </tr>
            `;
        });
    })
    .catch(err => {
        console.error(err);
        document.getElementById("usersTable").innerHTML =
            `<tr><td colspan="6" class="empty">Failed to load users</td></tr>`;
    });
}

function updateStatus(userId, status) {
    if (!confirm(`Are you sure you want to ${status} this user?`)) return;

    fetch("/ResQHub/index.php?controller=admin&action=updateUserStatus", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        credentials: "same-origin",
        body: JSON.stringify({ user_id: userId, status })
    })
    .then(res => res.json())
    .then(() => loadUsers());
}
