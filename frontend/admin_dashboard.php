<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<style>
body{margin:0;font-family:Arial,sans-serif;background:#f0f0f0;}
nav{background:linear-gradient(to right,#ff6a00,#ee0979);color:#fff;padding:15px;text-align:center;font-size:24px;}
.container{display:flex;gap:20px;width:95%;max-width:1200px;margin:20px auto;}

/* Sidebar buttons */
.buttons{
    display:flex;
    flex-direction:column;
    align-items:flex-start;
    gap:15px;
    min-width:200px;
}
.buttons button{
    padding:12px 20px;
    border:none;
    border-radius:10px;
    color:#fff;
    font-size:16px;
    cursor:pointer;
    background:linear-gradient(to right,#ff6a00,#ee0979);
    box-shadow:0 4px 6px rgba(0,0,0,0.2);
    width:100%;
    text-align:left;
    transition:0.3s;
}
.buttons button.active{
    background:linear-gradient(to right,#ee0979,#ff6a00);
}
.buttons button:hover{opacity:0.85;}

/* Main section */
.main-section{flex:1;}

/* Sections */
.section{display:none;padding:20px;border-radius:12px;box-shadow:0 5px 15px rgba(0,0,0,0.1);}
#manageFood{background:#fff3e0;}      /* orange */
#viewUsers{background:#e8f5e9;}       /* green */
#manageOrders{background:#e3f2fd;}    /* blue */

/* Cards */
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:15px;margin-top:15px;}
.card{padding:15px;border-radius:10px;background:#fff;box-shadow:0 5px 10px rgba(0,0,0,0.1);}
.card h3{color:#ff6a00;margin:5px 0;}
.card p{margin:5px 0;}
.card select{padding:5px;margin-top:5px;width:100%;}

/* Forms */
form input, form select{padding:8px;margin:5px 0;width:100%;border-radius:6px;border:1px solid #ccc;}
form button{margin-top:10px;padding:10px;background:linear-gradient(to right,#ff6a00,#ee0979);color:#fff;border:none;border-radius:8px;cursor:pointer;}
form button:hover{opacity:0.85;}
</style>
</head>
<body>

<nav>Admin Dashboard</nav>

<div class="container">
    <!-- Sidebar buttons -->
    <div class="buttons">
        <button onclick="showSection('manageFood',this)">Add / Manage Food</button>
        <button onclick="showSection('viewUsers',this)">View Users</button>
        <button onclick="showSection('manageOrders',this)">View Orders</button>
        <button onclick="logout()">Logout</button>
    </div>

    <!-- Main display sections -->
    <div class="main-section">

        <!-- Add / Manage Food -->
        <div id="manageFood" class="section">
            <h2>Add / Manage Food Items</h2>
            <form id="addFoodForm">
                <input type="text" id="name" placeholder="Food Name" required>
                <input type="text" id="description" placeholder="Description">
                <input type="number" id="price" placeholder="Price" step="0.01" required>
                <select id="category_id"><option>Loading categories...</option></select>
                <input type="text" id="image" placeholder="Image URL">
                <button type="submit">Add Food</button>
            </form>
            <div class="cards" id="foodCards"></div>
        </div>

        <!-- View Users -->
        <div id="viewUsers" class="section">
            <h2>Registered Users</h2>
            <div class="cards" id="userCards"></div>
        </div>

        <!-- View Orders -->
        <div id="manageOrders" class="section">
            <h2>Orders</h2>
            <div class="cards" id="orderCards"></div>
        </div>

    </div>
</div>

<script>
// Show section and highlight active button
function showSection(id, btn){
    document.querySelectorAll('.section').forEach(s=>s.style.display='none');
    document.getElementById(id).style.display='block';
    document.querySelectorAll('.buttons button').forEach(b=>b.classList.remove('active'));
    if(btn) btn.classList.add('active');
}

// Load categories into dropdown
function loadCategories(){
    fetch('../backend/admin/list_categories.php')
    .then(res => res.json())
    .then(data => {
        const catSelect = document.getElementById('category_id');
        catSelect.innerHTML = '';
        if(data.status === 'success' && data.data.length>0){
            data.data.forEach(c => {
                let option = document.createElement('option');
                option.value = c.category_id;
                option.text = c.category_name;
                catSelect.appendChild(option);
            });
        } else {
            catSelect.innerHTML = '<option value="">No categories found</option>';
        }
    }).catch(err=>{
        console.log("Error loading categories:", err);
        document.getElementById('category_id').innerHTML = '<option value="">Error loading categories</option>';
    });
}

// Load food items
function loadFoods(){
    fetch('../backend/admin/list_items.php')
    .then(res=>res.json())
    .then(data=>{
        const foodCards=document.getElementById('foodCards');
        foodCards.innerHTML='';
        if(data.status==='success'){
            data.data.forEach(f=>{
                let div=document.createElement('div'); div.className='card';
                div.innerHTML=`<h3>${f.name}</h3>
                <p>${f.description}</p>
                <p>Category: ${f.category_name}</p>
                <p>Price: $${f.price}</p>`;
                foodCards.appendChild(div);
            });
        }
    });
}

// Add food
document.getElementById('addFoodForm').addEventListener('submit',function(e){
    e.preventDefault();
    const payload={
        name:document.getElementById('name').value,
        description:document.getElementById('description').value,
        price:parseFloat(document.getElementById('price').value),
        category_id:parseInt(document.getElementById('category_id').value),
        image:document.getElementById('image').value
    };
    fetch('../backend/admin/add_item.php',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify(payload)
    })
    .then(res=>res.json())
    .then(resp=>{
        alert(resp.message);
        if(resp.status=='success'){ 
            loadFoods(); 
            document.getElementById('addFoodForm').reset(); 
        }
    });
});

// Load users
function loadUsers(){
    fetch('../backend/admin/view_users.php')
    .then(res=>res.json())
    .then(data=>{
        const userCards=document.getElementById('userCards');
        userCards.innerHTML='';
        if(data.status==='success'){
            data.data.forEach(u=>{
                let div=document.createElement('div'); div.className='card';
                div.innerHTML=`<h3>${u.full_name}</h3>
                <p>Email: ${u.email}</p>
                <p>Role: ${u.role_name}</p>
                <p>Created: ${u.created}</p>`;
                userCards.appendChild(div);
            });
        }
    });
}

// Load orders
function loadOrders(){
    fetch('../backend/admin/view_orders.php')
    .then(res=>res.json())
    .then(data=>{
        const orderCards=document.getElementById('orderCards');
        orderCards.innerHTML='';
        if(data.status==='success'){
            data.data.forEach(o=>{
                let div=document.createElement('div'); div.className='card';
                div.innerHTML=`<h3>Order #${o.order_id}</h3>
                <p>User: ${o.full_name}</p>
                <p>Total: $${o.total}</p>
                <p>Status: 
                    <select onchange="updateStatus(${o.order_id},this.value)">
                        <option ${o.status=='Pending'?'selected':''}>Pending</option>
                        <option ${o.status=='Processing'?'selected':''}>Processing</option>
                        <option ${o.status=='Completed'?'selected':''}>Completed</option>
                        <option ${o.status=='Cancelled'?'selected':''}>Cancelled</option>
                    </select>
                </p>
                <p>Created: ${o.created}</p>`;
                orderCards.appendChild(div);
            });
        }
    });
}

// Update order status
function updateStatus(orderId,status){
    fetch('../backend/admin/update_order_status.php',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({order_id:orderId,status:status})
    })
    .then(res=>res.json())
    .then(resp=>{
        alert(resp.message);
        loadOrders();
    });
}

// Logout
function logout(){
    fetch('../backend/auth/logout.php')
    .then(res=>res.json())
    .then(data=>{
        if(data.status=='success'){ window.location.href='../frontend/login.html'; }
    });
}

// Initialize dashboard
showSection('manageFood', document.querySelector('.buttons button'));
loadCategories();
loadFoods();
loadUsers();
loadOrders();
</script>

</body>
</html>