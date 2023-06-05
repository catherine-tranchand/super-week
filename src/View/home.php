<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="scripts/home.js" defer></script></script>
</head>
<body>

<h1>Welcome to SuperWeek!</h1>

<button id="users">Users</button>
<div id="displayUsers"></div>

<button id="books">Books</button>
<div id="displayBooks"></div>

<form id="userForm">
   <label>User Id</label>
   <input name="user" type="text">
   <button>Search user</button>
</form>

<div id="displayOneUser"></div>


 <form id="book">
    <label>Book Id</label>
    <input name="book" type="text">
    <button type="submit" id="book">Search book</button>

 </form>
 <div id="displayOneBook"></div>


    
</body>
</html>