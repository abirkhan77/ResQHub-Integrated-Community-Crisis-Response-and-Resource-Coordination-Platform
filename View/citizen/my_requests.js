
document.addEventListener("DOMContentLoaded", () => {
    loadMyRequests();
});

/* =========================
   FETCH MY REQUESTS
========================= */
function loadMyRequests() {
    fetch("/ResQHub/index.php?controller=request&action=listMyRequests")
        .then(res => res.json())
        .then(result => {
            if (!result.success) {
                alert(result.message);
                return;
            }

            const requests = result.data;
            const container = document.getElementById("requestsContainer");
            const emptyState = document.getElementById("emptyState");

            container.innerHTML = "";

            if (!requests || requests.length === 0) {
                emptyState.classList.remove("hidden");
                return;
            }

            emptyState.classList.add("hidden");

            requests.forEach(req => {
                container.appendChild(createRequestCard(req));
            });
        })
        .catch(() => {
            alert("Failed to load requests. Please try again.");
        });
}

/* =========================
   CREATE REQUEST CARD
========================= */
function createRequestCard(req) {
    const card = document.createElement("div");
    card.className = "request-card";

    card.innerHTML = `
        <div class="request-header">
            <div class="request-type">${formatType(req.request_type)}</div>
            <div class="status ${req.request_status}">
                ${formatStatus(req.request_status)}
            </div>
        </div>

        <div class="request-body">
            <p><span>Urgency:</span> ${capitalize(req.urgency_level)}</p>
            <p><span>Location:</span> ${req.location_text}</p>
            <p><span>Description:</span> ${req.description}</p>
            <p><span>Submitted:</span> ${formatDate(req.created_at)}</p>
        </div>
    `;

    return card;
}

/* =========================
   NAVIGATION HELPERS
========================= */
function goDashboard() {
    window.location.href = "/ResQHub/View/citizen/dashboard.html";
}

function goCreate() {
    window.location.href = "/ResQHub/View/citizen/create_request.html";
}

function goDonations() {
    window.location.href = "/ResQHub/View/citizen/donation.html";
}

function goProfile() {
    window.location.href = "/ResQHub/View/citizen/profile.html";
}

function logout() {
    fetch("/ResQHub/index.php?controller=auth&action=logout")
        .then(() => {
            window.location.href = "/ResQHub/View/auth/login.html";
        });
}

/* =========================
   FORMATTERS
========================= */
function formatType(type) {
    return type.replace(/_/g, " ").toUpperCase();
}

function formatStatus(status) {
    return status.replace(/_/g, " ").toUpperCase();
}

function capitalize(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
}

function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString() + " " + d.toLocaleTimeString();
}
