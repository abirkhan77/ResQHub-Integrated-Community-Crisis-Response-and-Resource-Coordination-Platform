
document.addEventListener("DOMContentLoaded", () => {

    fetch("/ResQHub/index.php?controller=volunteer&action=getProfile")
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert(data.message);
                return;
            }

            document.getElementById("full_name").value = data.data.full_name;
            document.getElementById("email").value     = data.data.email;
            document.getElementById("phone").value     = data.data.phone ?? '';
            document.getElementById("role").value      = data.data.role;
        });

    document.getElementById("profileForm").addEventListener("submit", e => {
        e.preventDefault();

        fetch("/ResQHub/index.php?controller=volunteer&action=updateProfile", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                full_name: document.getElementById("full_name").value,
                phone: document.getElementById("phone").value
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
        });
    });

    document.getElementById("logoutBtn").onclick = () => {
        fetch("/ResQHub/index.php?controller=auth&action=logout")
            .then(() => location.href = "/ResQHub/View/auth/login.html");
    };
});

document.addEventListener("DOMContentLoaded", () => {

    fetch("/ResQHub/index.php?controller=volunteer&action=getProfile")
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert(data.message);
                return;
            }

            document.getElementById("full_name").value = data.data.full_name;
            document.getElementById("email").value     = data.data.email;
            document.getElementById("phone").value     = data.data.phone ?? '';
            document.getElementById("role").value      = data.data.role;
        });

    document.getElementById("profileForm").addEventListener("submit", e => {
        e.preventDefault();

        fetch("/ResQHub/index.php?controller=volunteer&action=updateProfile", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                full_name: document.getElementById("full_name").value,
                phone: document.getElementById("phone").value
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
        });
    });

    document.getElementById("logoutBtn").onclick = () => {
        fetch("/ResQHub/index.php?controller=auth&action=logout")
            .then(() => location.href = "/ResQHub/View/auth/login.html");
    };
});
