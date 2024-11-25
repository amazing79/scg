import {login, credentials} from "./userRepository.js";
import {SessionManager} from "../common/sessionManager.js";

function displayErrors(error) {
    let display = document.getElementById('display_error');
    display.classList.remove('display__hide');
    display.classList.add('display__show');
}

function loginAction(evt)
{
    evt.preventDefault();
    let data = Object.assign({}, credentials);
    let user = document.getElementById('user') ?? ''
    let password = document.getElementById('password') ?? '';
    data.email = user.value.trim();
    data.password = password.value.trim();
    login(data)
        .then(response => {
            if(response.code === 200) {
                SessionManager.setAuthToken(response.token);
                window.location = "index.html";
            }
        })
        .catch(error => {
           console.log(error.code);
           displayErrors(error);

        })
}
function setListeners()
{
    let btnLogin = document.getElementById('login');

    btnLogin.addEventListener('click', loginAction);
}

window.addEventListener('load' , setListeners);