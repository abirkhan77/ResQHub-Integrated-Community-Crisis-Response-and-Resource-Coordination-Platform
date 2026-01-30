
document.addEventListener("DOMContentLoaded", loadAssignments);

function loadAssignments() {
    fetch("/ResQHub/index.php?controller=volunteer&action=myAssignments")
        .then(res => res.json())
        .then(data => {
            const table = document.getElementById("assignedTable");
            table.innerHTML = "";

            if (!data.success || data.data.length === 0) {
                table.innerHTML = `
                    <tr>
                        <td colspan="5" class="empty">No assigned requests.</td>
                    </tr>`;
                return;
            }

            data.data.forEach(item => {
                const statusBadge = getStatusBadge(item.current_status);
                const actionBtn = getActionButton(item);

                table.innerHTML += `
                    <tr>
                        <td>#${item.request_id}</td>
                        <td>${item.request_type}</td>
                        <td>${item.location_text}</td>
                        <td>${statusBadge}</td>
                        <td>${actionBtn}</td>
                    </tr>`;
            });
        });
}

/* STATUS UI */
function getStatusBadge(status) {
    if (status === "accepted")
        return `<span class="status-badge status-accepted">Accepted</span>`;
    if (status === "on_the_way")
        return `<span class="status-badge status-on_the_way">On the way</span>`;
    if (status === "completed")
        return `<span class="status-badge status-completed">Completed</span>`;

    return status;
}

/* ACTION UI */
function getActionButton(item) {
    if (item.current_status === "accepted") {
        return `<button class="action-btn btn-progress"
            onclick="updateStatus(${item.assignment_id}, 'on_the_way')">
            Start
        </button>`;
    }

    if (item.current_status === "on_the_way") {
        return `<button class="action-btn btn-complete"
            onclick="updateStatus(${item.assignment_id}, 'completed')">
            Complete
        </button>`;
    }

    return "-";
}

/* UPDATE STATUS */
function updateStatus(assignmentId, status) {
    fetch("/ResQHub/index.php?controller=volunteer&action=updateStatus", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            assignment_id: assignmentId,
            status: status
        })
    })
    .then(res => res.json())
    .then(() => loadAssignments());
}
