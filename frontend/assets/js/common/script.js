import {SessionManager} from "./sessionManager.js";
import {logout} from "../users/userRepository.js";

function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}
function closeSession() {
    if(SessionManager.isActive()) {
        logout()
            .then(result => console.log('nos juimos!'))
            .catch(error => console.error('Ver servidor'))
    }
    SessionManager.clearSession();
}

SessionManager.redirectToLoginIsNotActiveSession();
document.getElementById('btnClose').addEventListener('click', closeSession);
document.getElementById('btn_menu').addEventListener('click', toggleMenu);

