import {SessionManager} from "./sessionManager.js";
import {routes} from "./routes.js";
import {logout} from "../users/userRepository.js";

function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

function hideMenu() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.remove('show');
}

function closeSession() {
    if(SessionManager.isActive()) {
        logout()
            .then(result => console.log('nos juimos!'))
            .catch(error => console.error('Ver servidor'))
    }
    SessionManager.clearSession();
    window.location = routes.LOGIN;
}

document.getElementById('btnClose').addEventListener('click', closeSession);
