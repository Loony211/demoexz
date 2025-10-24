const login = document.getElementsByName ("login")[0];
const password = document.getElementsByName ("password")[0];
const error = document.querySelector (".Error");

document.querySelector("button").addEventListener("click", (event) =>{
    if (login.value === "" || password.value === "") {
        error.innerHTML="Заполните поля пж";
        event.preventDefault();
    }
});