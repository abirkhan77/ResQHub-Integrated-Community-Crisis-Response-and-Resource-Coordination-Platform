
document.getElementById("registerForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const payload = {
        full_name: document.getElementById("full_name").value.trim(),
        email: document.getElementById("email").value.trim(),
        password: document.getElementById("password").value,
        role: document.getElementById("role").value,
        phone: document.getElementById("phone").value.trim()
    };

    try {
        const res = await fetch("/ResQHub/index.php?controller=auth&action=register", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const text = await res.text();
        console.log("RAW RESPONSE:", text);

        let data;
        try {
            data = JSON.parse(text);
        } catch {
            alert("Backend PHP error. Open Network tab → Response.");
            return;
        }

        if (!data.success) {
            alert(data.message);
            return;
        }

        alert("Registration successful");
        window.location.href = "/ResQHub/View/auth/login.html";

    } catch (err) {
        console.error(err);
        alert("Server error.");
    }
});
