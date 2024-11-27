import {routes} from "./routes.js";
import {config} from "../config.js";

export class SessionManager {
    static isActive()
    {
        let token = sessionStorage.getItem('token') ?? '';
        return token.length === 36 ?? false;
    }

    static clearSession(){
        sessionStorage.clear();
        //redirijo a login.html
        let url = config.APP_PATH + routes.LOGIN;
        window.location.replace(url);
    }

    static getAuthToken()
    {
        return sessionStorage.getItem('token') ?? '';
    }

    static getAuthUser()
    {
        return sessionStorage.getItem('user') ?? 'INVITADO';
    }

    static setAuthToken(token)
    {
        sessionStorage.setItem('token', token);
    }

    static setAuthUser(user)
    {
        sessionStorage.setItem('user', user);
    }

    static redirectToLoginIsNotActiveSession()
    {
        if (!this.isActive()) {
           this.clearSession();
        }
    }
}