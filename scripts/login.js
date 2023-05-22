let registerForm = document.getElementById('connectionForm');

async function handleFormSubmit(event){
    console.log(event);
    event.preventDefault();
    let form = new FormData(event.currentTarget);
    let url ="login";
    let request = new Request(url, {method: 'POST', body: form});
    let response = await fetch (request);
    let responseData = await response.json();

    if (responseData.success) {
        let errorDiv = document.querySelector('.error');
        errorDiv.innerHTML = "Connection is successful"
       // window.location.replace('');
    }else{
        let errorDiv = document.querySelector('.error');
        errorDiv.innerHTML = "Connection is failed";
    }
}

registerForm.addEventListener('submit', (event) => handleFormSubmit(event));