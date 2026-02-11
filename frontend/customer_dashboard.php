<?php
session_start();

// Only allow customers (role_id = 2)
if(!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 2){
    header("Location: login.php"); // Redirect if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin:0; padding:0; display:flex; height:100vh; }
        .sidebar { width:200px; background:#333; color:#fff; padding:20px; box-sizing:border-box; }
        .sidebar h2 { color:#fff; font-size:18px; }
        .sidebar button, .sidebar a button {
            width:100%; padding:10px; margin:5px 0; border:none; border-radius:5px; cursor:pointer;
            background:#444; color:#fff; text-align:left; text-decoration:none;
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
        #cart { margin-top:20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Customer Menu</h2>
    <button onclick="showSection('menuSection')" class="active">Menu</button>
    <button onclick="showSection('myOrderSection')">My Orders</button>
    <button onclick="showSection('placeOrderSection')">Place Order</button>
    <button onclick="showSection('paymentSection')">Payment</button>
    <a href="../backend/logout.php"><button>Logout</button></a>
</div>

<div class="main" id="mainContent">

    <div id="menuSection">
        <h2>Menu</h2>
        <div id="foods">Loading food...</div>
    </div>

    <div id="myOrderSection" style="display:none;">
        <h2>My Orders</h2>
        <div id="cart"><p>Your cart is empty.</p></div>
    </div>

    <div id="placeOrderSection" style="display:none;">
        <h2>Place Order</h2>
        <button onclick="placeOrder()">Confirm Order</button>
        <div id="orderMsg"></div>
    </div>

    <div id="paymentSection" style="display:none;">
        <h2>Payment</h2>
        <p>Payment feature coming soon...</p>
    </div>

</div>

<script>
let cart = [];

function showSection(sectionId){
    document.querySelectorAll('.main > div').forEach(div=> div.style.display='none');
    document.getElementById(sectionId).style.display='block';

    document.querySelectorAll('.sidebar button').forEach(btn=> btn.classList.remove('active'));
    document.querySelector(.sidebar button[onclick="showSection('${sectionId}')"]).classList.add('active');
}

// Load all foods from backend
function loadFoods(){
    fetch("../backend/customer/get_foods.php")
    .then(res => res.json())
    .then(data => {
        let html = "";
        if(data.length === 0){
            html = "<p>No foods available.</p>";
        } else {
            data.forEach(f => {
                html += `
                <div class="card">
                    <img src="${f.image}" alt="${f.name}">
                    <h3>${f.name}</h3>
                    <p>${f.description}</p>
                    <b>Tsh ${f.price}</b>
                    <button onclick="addToCart(${f.food_id},'${f.name}',${f.price})">Add</button>
                </div>`;
            });
        }
        document.getElementById("foods").innerHTML = html;
    })
    .catch(err => {
        document.getElementById("foods").innerHTML = "<p>Error loading foods.</p>";
        console.error(err);
    });
}

function addToCart(id, name, price){
    const item = cart.find(i=>i.food_id===id);
    if(item){ item.qty++; } 
    else { cart.push({food_id:id, name:name, price:price, qty:1}); }
    updateCartDisplay();
}

function updateCartDisplay(){
    const cartDiv = document.getElementById('cart');
    if(cart.length===0){
        cartDiv.innerHTML = "<p>Your cart is empty.</p>";
        return;
    }
    let html = "<ul>"; let total=0;
    cart.forEach(item=>{
        html += <li>${item.name} x ${item.qty} = Tsh ${item.price*item.qty}</li>;
        total += item.price*item.qty;
    });
    html += </ul><b>Total: Tsh ${total}</b>;
    cartDiv.innerHTML = html;
}

function placeOrder(){
    if(cart.length===0){
        document.getElementById('orderMsg').innerText="Cart is empty!";
        return;
    }
    fetch("../backend/customer/place_order.php", {
        method:"POST",
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({items:cart})
    })
    .then(res=>res.json())
    .then(data=>{
        document.getElementById('orderMsg').innerText=data.message;
        if(data.status==="success"){ cart=[]; updateCartDisplay(); }
    })
    .catch(err=>{ document.getElementById('orderMsg').innerText="Error placing order."; console.error(err); });
}

// Initial load
loadFoods();
</script>

</body>
</html>