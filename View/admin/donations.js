
document.addEventListener("DOMContentLoaded", () => {
    loadDonations();

    document.getElementById("logoutBtn").onclick = () => {
        fetch("/ResQHub/index.php?controller=auth&action=logout")
            .then(() => location.href = "/ResQHub/View/auth/login.html");
    };
});

function loadDonations() {
    fetch("/ResQHub/index.php?controller=admin&action=listDonations")
        .then(res => res.json())
        .then(result => {
            const tbody = document.getElementById("donationsTable");
            tbody.innerHTML = "";

            if (!result.success || result.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="empty">No donations found</td>
                    </tr>`;
                return;
            }

            result.data.forEach(d => {
                tbody.innerHTML += `
                    <tr>
                        <td>${d.donation_id}</td>
                        <td>${d.donor_name}</td>
                        <td>${d.donation_amount}</td>
                        <td>${d.currency_code}</td>
                        <td>${d.donor_region}</td>
                        <td>${d.donation_date}</td>
                    </tr>
                `;
            });
        });
}
