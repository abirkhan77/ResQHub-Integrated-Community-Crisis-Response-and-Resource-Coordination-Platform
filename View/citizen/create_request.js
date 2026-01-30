
document.getElementById("requestForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const type        = document.getElementById("type").value;
    const urgency     = document.getElementById("urgency").value;
    const location    = document.getElementById("location").value.trim();
    const description = document.getElementById("description").value.trim();

    document.querySelectorAll(".error").forEach(el => el.innerText = "");

    let valid = true;

    if (!type) {
        document.getElementById("typeError").innerText = "Please select emergency type";
        valid = false;
    }

    if (!urgency) {
        document.getElementById("urgencyError").innerText = "Please select urgency level";
        valid = false;
    }

    if (!location) {
        document.getElementById("locationError").innerText = "Location is required";
        valid = false;
    }

    if (!description) {
        document.getElementById("descriptionError").innerText = "Description is required";
        valid = false;
    }

    if (!valid) return;

    fetch("/ResQHub/index.php?controller=request&action=create", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body:
            `type=${encodeURIComponent(type)}` +
            `&urgency=${encodeURIComponent(urgency)}` +
            `&location=${encodeURIComponent(location)}` +
            `&description=${encodeURIComponent(description)}`
    })
    .then(res => res.json())
    .then(result => {

        if (!result.success) {
            alert(result.message);
            return;
        }

        alert("Emergency request submitted successfully.");

        // ✅ SAFE REDIRECT (my_requests not built yet)
        window.location.href = "/ResQHub/View/citizen/dashboard.html";
    })
    .catch(() => {
        alert("Server error. Please try again.");
    });
});

document.getElementById("requestForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const type        = document.getElementById("type").value;
    const urgency     = document.getElementById("urgency").value;
    const location    = document.getElementById("location").value.trim();
    const description = document.getElementById("description").value.trim();

    document.querySelectorAll(".error").forEach(el => el.innerText = "");

    let valid = true;

    if (!type) {
        document.getElementById("typeError").innerText = "Please select emergency type";
        valid = false;
    }

    if (!urgency) {
        document.getElementById("urgencyError").innerText = "Please select urgency level";
        valid = false;
    }

    if (!location) {
        document.getElementById("locationError").innerText = "Location is required";
        valid = false;
    }

    if (!description) {
        document.getElementById("descriptionError").innerText = "Description is required";
        valid = false;
    }

    if (!valid) return;

    fetch("/ResQHub/index.php?controller=request&action=create", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body:
            `type=${encodeURIComponent(type)}` +
            `&urgency=${encodeURIComponent(urgency)}` +
            `&location=${encodeURIComponent(location)}` +
            `&description=${encodeURIComponent(description)}`
    })
    .then(res => res.json())
    .then(result => {

        if (!result.success) {
            alert(result.message);
            return;
        }

        alert("Emergency request submitted successfully.");

        // ✅ SAFE REDIRECT (my_requests not built yet)
        window.location.href = "/ResQHub/View/citizen/dashboard.html";
    })
    .catch(() => {
        alert("Server error. Please try again.");
    });
});
