const form = document.getElementById("loginForm");

form.addEventListener("submit", function(e){
    e.preventDefault();

    const formData = new FormData(form);

    fetch("../backend/login.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("message").innerText = data.message;

        if(data.status === "success"){
            if(data.role_id == 1){
                window.location.href = "admin_dashboard.html";
            } else {
                window.location.href = "customer_dashboard.html";
            }
        }
    })
    .catch(err => console.error("Fetch error:", err));
});