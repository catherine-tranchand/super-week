let booksForm = document.getElementById('booksForm');

async function handleFormSubmit(event){
    event.preventDefault();
    let form = new FormData(event.currentTarget);
    let url = "books/write";
    let request = new Request (url, {methode: 'POST', body: form});
    let response = await fetch(request);
    let responseData = await response.json();
    
    if(responseData.success){
        console.log(responseData.message);
        let erroDiv = document.querySelector('.error');
        

    }

}
    
