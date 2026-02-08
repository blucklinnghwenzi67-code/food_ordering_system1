// Register
const registerForm = document.getElementById('registerForm');
if(registerForm){
    registerForm.addEventListener('submit', e => {
        e.preventDefault();
        const formData = new FormData(registerForm);
        fetch('../backend/register.php',{
            method:'POST',
            body: formData
        })
        .then(res=>res.text())
        .then(data=>{
            const message = document.getElementById('message');
            if(data==='success'){
                message.style.color='green';
                message.textContent='Registration successful! Redirecting to login...';
                setTimeout(()=>{ window.location='login.html'; },1500);
            } else {
                message.style.color='red';
                message.textContent=data;
            }
        });
    });
}

// Login
const loginForm = document.getElementById('loginForm');
if(loginForm){
    loginForm.addEventListener('submit', e => {
        e.preventDefault();
        const formData = new FormData(loginForm);
        fetch('../backend/login.php',{
            method:'POST',
            body: formData
        })
        .then(res=>res.text())
        .then(data=>{
            const message = document.getElementById('message');
            if(data==='success'){
                window.location='../backend/dashboard.php';
            } else {
                message.style.color='red';
                message.textContent=data;
            }
        });
    });
}

// Display Food Menu
const menuDiv = document.getElementById('menu');
if(menuDiv){
    fetch('../backend/get_foods.php')
    .then(res=>res.json())
    .then(data=>{
        data.forEach(food=>{
            const div = document.createElement('div');
            div.innerHTML = `<h4>${food.food_name}</h4>
                             <p>${food.description}</p>
                             <p>Price: $${food.price}</p>`;
            menuDiv.appendChild(div);
        });
    });
}