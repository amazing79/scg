import {login, credentials} from "./userRepository.js";
import {SessionManager} from "../common/sessionManager.js";
import {routes} from "../common/routes.js";

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
                window.location = routes.MAIN;
            }
        })
        .catch(error => {
           console.error('Ocurrio el error', error.status, error.statusText);
           console.error('Detalles', error.details);
           displayErrors(error);

        })
}
function setListeners()
{
    let btnLogin = document.getElementById('login');
    btnLogin.addEventListener('click', loginAction);
}

SessionManager.redirectToLoginIsNotActiveSession();
window.addEventListener('load' , setListeners);