export class Loader {
    constructor(anId, aTemplate)
    {
        this._container = document.getElementById(anId);
        let template = document.getElementById(aTemplate);
        let clone = template.content.cloneNode(true);
        this._loading = clone.querySelector('.loader__container');
    }
    showLoading()
    {
        this._container.appendChild(this._loading);
    }
    removeLoading()
    {
        if(this._loading) {
            this._loading.parentNode.removeChild(this._loading);
        }

    }
}