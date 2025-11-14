import {reset, credentials} from "./userRepository.js";
import {SessionManager} from "../common/sessionManager.js";
import {routes} from "../common/routes.js";

function displayErrors(error) {
    let display = document.getElementById('display_error');
    display.classList.remove('display__hide');
    display.classList.add('display__show');
}

function resetAction(evt)
{
    evt.preventDefault();
    let mail = document.getElementById('email') ?? ''
    let data = Object.assign({}, credentials);
    data.email = mail.value.trim();
    reset(data)
        .then(response => {
            if(response.code === 200) {
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
    let btnReset = document.getElementById('reset');
    btnReset.addEventListener('click', resetAction);
}

if (SessionManager.isActive()) {
    window.location = routes.MAIN;
}

window.addEventListener('DOMContentLoaded' , setListeners);