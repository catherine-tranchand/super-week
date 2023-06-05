let btnUsers = document.getElementById('users');
let btnBooks = document.getElementById('books');
let btnBook = document.getElementById('book');
let userForm = document.getElementById('userForm');

async function getUsers(){
   let response = await fetch(`users`);
   let responseData = await response.json();
   
   let divUsers = document.querySelector('#displayUsers');
   divUsers.innerHTML = "";

   for (let i = 0; i < responseData.length; i++){
    let template =
    `<li>
    <strong>Id</strong> : ${responseData[i].id}
    <strong>Email</strong> : ${responseData[i].email}
    <strong>First Name</strong> : ${responseData[i].first_name}
    <strong>Last Name</strong> : ${responseData[i].last_name}
    </li>`;

    divUsers.insertAdjacentHTML('beforeend', template);


   }
}

async function getUser(idUser) {
 let response = await fetch(`users/${idUser}`);
 let responseData = await response.json();

 let divOneUser = document.querySelector('#displayOneUser');
 divOneUser.innerHTML = "";

 let template = 
 `<li>
 <strong>Name</strong> : ${responseData[0].first_name}
 <strong>Last Name</strong> : ${responseData[0].last_name}
 <strong>Email</strong> : ${responseData[0].email}
 </li>`

 divOneUser.insertAdjacentHTML('beforeend', template);

}

userForm.addEventListener('submit', (ev) => {
    ev.preventDefault();
    let idUser = ev.target[0].value;
    getUser(idUser);

})

async function getBooks() {
    let response = await fetch(`books`);
    let responseData = await response.json();

    let divBooks = document.querySelector('#displayBooks');
    divBooks.innerHTML = "";

    for (let i = 0; i < responseData.length; i++) {
        let template = `
    <li>
        <strong>Titre</strong> : ${responseData[i].title}
        <strong>Content</strong> : ${responseData[i].content}
    </li>
    `
        divBooks.insertAdjacentHTML('beforeend', template);
    }
    btnBooks.addEventListener('click', (ev) =>{
        ev.preventDefault();
        getBooks();
    })
}


async function getBook(idBook){
    let response = await fetch (`books/${idBook}`);
    let responseData = await response.json();

    let divOneBook = document.querySelector('#displayOneBook');
    divOneBook.innerHTML ="";

    let template = `
    <li>
        <strong>Titre</strong> : ${responseData.title}
        <strong>Contenu</strong> : ${responseData.content}
    </li>
    `
    divOneBook.insertAdjacentHTML('beforeend', template)
 
}

btnBook.addEventListener('submit', (ev) => {
    ev.preventDefault();
    let idBook = ev.target[0].value;
    getBook(idBook);
})

btnUsers.addEventListener('click', (ev) => {
    getUsers();
   
})

btnBooks.addEventListener('click', (ev) => {
    getBooks();
})
