import {config} from "../config.js";
import {SessionManager} from "../common/sessionManager.js";

let actions  = {
    PATH: "login",
    RESET_PASSWORD: "reset",
    login: "POST",
    logout:"POST",
    reset: "POST"
};

let credentials =
    {
        "email" : "",
        "password" : ""
    }
async function login(credentials)
{
    let request = new URL(actions.PATH, config.URL_API);

    let options = {
        method: actions.login, // *GET, POST, PUT, DELETE, etc.
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(credentials),
    }

    let response = await fetch(request, options);
    if(response.ok) {
        return await response.json();
    }
    const errorsDetails = await response.json();
    throw {
        status: response.status,
        statusText : response.statusText,
        details: errorsDetails
    }
}

async function reset(credentials)
{
    let request = new URL(actions.RESET_PASSWORD, config.URL_API);

    let options = {
        method: actions.reset, // *GET, POST, PUT, DELETE, etc.
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(credentials),
    }

    let response = await fetch(request, options);
    if(response.ok) {
        return await response.json();
    }
    const errorsDetails = await response.json();
    throw {
        status: response.status,
        statusText : response.statusText,
        details: errorsDetails
    }
}
async function logout()
{
    let uri = SessionManager.getAuthToken() + '/logout';
    let request = new URL(uri,config.URL_API);
    let options = {
        method: actions.logout, // *GET, POST, PUT, DELETE, etc.
    }
    let response = await fetch(request, options);
    if(response.ok) {
        return await response.json();
    }
    const errorsDetails = await response.json();
    throw {
        status: response.status,
        statusText : response.statusText,
        details: errorsDetails
    }
}

export {login, reset, logout, credentials}