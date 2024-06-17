export class Collapse {
    element: HTMLElement;
    trigger: HTMLElement;
    wrapper?: HTMLElement;
    content?: HTMLElement;
    visible: boolean = false;

    constructor(element: HTMLElement) {
        this.element = element;
        this.trigger = element.querySelector('.fm-c-collapse__arrow') as HTMLElement;
        this.wrapper = element.querySelector('.fm-c-collapse__wrapper') as HTMLElement;
        this.content = element.querySelector('.fm-c-collapse__content') as HTMLElement;
        this.visible = 'true' === element.dataset.visible;
    }

    connect() {
        this.trigger.addEventListener('click', this.collapse.bind(this), true);
    }

    disconnect() {
        this.trigger.removeEventListener('click', this.collapse.bind(this), true);
    }

    collapse() {
        if (null === this.wrapper || null === this.content) return;

        this.visible = !this.visible;

        this.element.classList.toggle('fm-c-collapse--visible', this.visible);
        this.element.classList.toggle('fm-c-collapse--hidden', !this.visible);
        this.element.dataset.visible = this.visible ? 'true' : 'false';
    }
}