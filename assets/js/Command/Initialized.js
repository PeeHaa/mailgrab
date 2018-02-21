export default class Initialized {
    static process() {
        const loader = document.querySelector('.loader');

        loader.parentNode.removeChild(loader);
    }
}
