export class Loader {
    constructor(anId, aTemplate)
    {
        this._container = document.getElementById(anId);
        let template = document.getElementById(aTemplate);
        this._loading = template.content.cloneNode(true);
    }
    showLoading()
    {
        this._container.appendChild(this._loading);
    }
    removeLoading()
    {
        this._container.innerHTML = '';
    }
}