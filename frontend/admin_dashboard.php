<?php
session_start();
if(!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1){
    header("Location: ../login.html");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin:0; padding:0; display:flex; height:100vh; }
        .sidebar {
            width:200px; background:#333; color:#fff; padding:20px; box-sizing:border-box;
        }
        .sidebar h2 { color:#fff; font-size:18px; }
        .sidebar button {
            width:100%; padding:10px; margin:5px 0; border:none; border-radius:5px; cursor:pointer;
            background:#444; color:#fff; text-align:left;
        }
        .sidebar button.active { background:#ff5722; }
        .sidebar button:hover { background:#555; }
        .main { flex:1; padding:20px; overflow:auto; background:#f7f7f7; }
        .card{
            border:1px solid #ccc; padding:15px; margin:10px; width:220px; display:inline-block;
            vertical-align:top; border-radius:8px; box-shadow:2px 2px 8px rgba(0,0,0,0.1); text-align:center;
            background:#fff; transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover{ transform: translateY(-5px); box-shadow:2px 8px 20px rgba(0,0,0,0.2); }
        .card img{ border-radius:6px; width:200px; height:150px; object-fit:cover; }
        .card h3{ margin:10px 0 5px 0; font-size:18px; color:#333; }
        .card p{ font-size:14px; color:#555; height:50px; overflow:hidden; }
        .card b{ display:block; margin:5px 0; color:#000; }
        .card small{ color:#888; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Menu</h2>
    <button id="btnFoods" class="active">Manage Food</button>
    <button id="btnAddFood">Add Food</button>
    <button id="btnOrders">View Orders</button>
    <button id="btnUsers">View Users</button>
</div>

<div class="main" id="mainContent">
    <h2>Food List</h2>
    <div id="foods">Loading foods...</div>
</div>

<script>
// Load Foods Function
function loadFoods(){
    document.getElementById("mainContent").innerHTML = '<h2>Food List</h2><div id="foods">Loading foods...</div>';
    fetch("../backend/admin/get_foods.php")
    .then(res => res.json())
    .then(data => {
        let html = "";
        if(data.length === 0){
            html = "<p>No foods found.</p>";
        } else {
            data.forEach(f => {
                html += `
                <div class="card">
                    <img src="${f.image}" alt="${f.name}">
                    <h3>${f.name}</h3>
                    <p>${f.description}</p>
                    <b>Tsh ${f.price}</b>
                    <small>${f.category_name}</small>
                </div>`;
            });
        }
        document.getElementById("foods").innerHTML = html;
    })
    .catch(err=>{
        document.getElementById("foods").innerHTML = "<p>Error loading foods.</p>";
        console.error(err);
    });
}

// Sidebar buttons
document.getElementById("btnFoods").addEventListener("click", function(){
    setActiveButton(this);
    loadFoods();
});

document.getElementById("btnAddFood").addEventListener("click", function(){
    setActiveButton(this);
    document.getElementById("mainContent").innerHTML = `
        <h2>Add Food</h2>
        <form id="addFoodForm">
            <label>Name:</label><br>
            <input type="text" id="foodName" required><br>
            <label>Description:</label><br>
            <textarea id="foodDesc" required></textarea><br>
            <label>Price:</label><br>
            <input type="number" id="foodPrice" required><br>
            <label>Category ID:</label><br>
            <input type="number" id="foodCategory" required><br>
            <label>Image URL:</label><br>
            <input type="text" id="foodImage"><br><br>
            <button type="submit">Add Food</button>
        </form>
        <div id="addFoodMsg"></div>
    `;
    document.getElementById("addFoodForm").addEventListener("submit", function(e){
        e.preventDefault();
        const data = {
            name: document.getElementById("foodName").value,
            description: document.getElementById("foodDesc").value,
            price: document.getElementById("foodPrice").value,
            category_id: document.getElementById("foodCategory").value,
            image: document.getElementById("foodImage").value
        };
        fetch("../backend/admin/add_food.php", {
            method:"POST",
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify(data)
        })
        .then(res=>res.json())
        .then(res=>{
            document.getElementById("addFoodMsg").innerText = res.message;
            if(res.status=="success") loadFoods();
        });
    });
});

document.getElementById("btnOrders").addEventListener("click", function(){
    setActiveButton(this);
    document.getElementById("mainContent").innerHTML = "<h2>Orders</h2><p>Coming soon...</p>";
});

document.getElementById("btnUsers").addEventListener("click", function(){
    setActiveButton(this);
    document.getElementById("mainContent").innerHTML = "<h2>Users</h2><p>Coming soon...</p>";
});

// Set active button color
function setActiveButton(btn){
    document.querySelectorAll(".sidebar button").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
}

// Initial load
loadFoods();
</script>

</body>
</html>