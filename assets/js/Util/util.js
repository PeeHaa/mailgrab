export function targetByTagName(element, target) {
    const tagName = target.toUpperCase();

    do {
        if (element.tagName === tagName) {
            return element;
        }
    } while(element = element.parentNode);

    return null;
}
