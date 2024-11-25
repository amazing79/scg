export class SessionManager {
    static isActive()
    {
        let token = sessionStorage.getItem('token') ?? '';
        return token.length === 36 ?? false;
    }

    static clearSession(){
        sessionStorage.clear();
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
}