let registerForm = document.getElementById('registerForm');

async function handleFormSubmit(event){
    console.log(event);
    event.preventDefault();
    let form = new FormData(event.currentTarget);
    let url ="register";
    let request = new Request(url, {method: 'POST', body: form});
    let response = await fetch (request);
    let responseData = await response.json();

    if (responseData.success) {
        let errorDiv = document.querySelector('.error');
        errorDiv.innerHTML = "Registration is successful"
        window.location.replace('login');
    }else{
        let errorDiv = document.querySelector('.error');
        errorDiv.innerHTML = "Registration is failed";
    }
}

registerForm.addEventListener('submit', (event) => handleFormSubmit(event));