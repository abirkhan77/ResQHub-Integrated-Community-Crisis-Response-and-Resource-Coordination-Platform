
document.addEventListener("DOMContentLoaded", () => {
    loadRequests();

    document.getElementById("logoutBtn").onclick = () => {
        fetch("/ResQHub/index.php?controller=auth&action=logout")
            .then(() => location.href = "/ResQHub/View/auth/login.html");
    };
});

function loadRequests() {
    fetch("/ResQHub/index.php?controller=admin&action=listRequests")
        .then(res => res.json())
        .then(result => {
            const tbody = document.getElementById("requestsTable");
            tbody.innerHTML = "";

            if (!result.success || result.data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="empty">No requests found</td></tr>`;
                return;
            }

            result.data.forEach(req => {
                tbody.innerHTML += `
                    <tr>
                        <td>${req.request_id}</td>
                        <td>${req.request_type}</td>
                        <td>${req.location_text}</td>
                        <td>${req.urgency_level}</td>
                        <td><span class="status ${req.status}">${req.status}</span></td>
                        <td>
                            <button class="action-btn resolve" onclick="updateStatus(${req.request_id}, 'resolved')">Resolve</button>
                            <button class="action-btn cancel" onclick="updateStatus(${req.request_id}, 'cancelled')">Cancel</button>
                        </td>
                    </tr>
                `;
            });
        });
}

function updateStatus(requestId, status) {
    if (!confirm(`Change request status to ${status}?`)) return;

    fetch("/ResQHub/index.php?controller=admin&action=overrideRequestStatus", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ request_id: requestId, status })
    })
    .then(res => res.json())
    .then(() => loadRequests());
}
