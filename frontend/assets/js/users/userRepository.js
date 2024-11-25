import {config} from "../config.js";

let actions  = {
    PATH: "login",
    login: "POST"
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
    throw new Error('Ocurrio un error al intentar ingresar al sistema');
}
async function logout()
{
    let request = new URL(actions.PATH,config.URL_API);
    let response = await fetch(request);
    if(response.ok) {
        return await response.json();
    }
    throw new Error('Ocurrio un error al intentar obtener el total de gastos realizados por persona');
}

export {login,  logout, credentials}