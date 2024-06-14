export class Toggle {
    wrapper: HTMLElement;
    trigger: HTMLElement;
    content: HTMLElement;
    clickOutside: HTMLElement;
    active: boolean = false;

    constructor(wrapper: HTMLElement, triggerSelector: string, contentSelector: string, clickOutsideSelector?: string) {
        this.wrapper = wrapper;
        this.trigger = wrapper.querySelector(triggerSelector) as HTMLElement;
        this.content = wrapper.querySelector(contentSelector) as HTMLElement;

        if (clickOutsideSelector === undefined) {
            clickOutsideSelector = contentSelector;
        }
        this.clickOutside = wrapper.querySelector(clickOutsideSelector) as HTMLElement;
    }

    connect() {
        this.trigger.addEventListener('click', this.toggle.bind(this), true);
        window.addEventListener('click', this.close.bind(this), true);
    }

    disconnect() {
        this.trigger.removeEventListener('click', this.toggle.bind(this), true);
        window.removeEventListener('click', this.close.bind(this), true);
    }

    toggle() {
        this.active = !this.active;
        this.content.classList.toggle('is-active', this.active);
    }

    close(event: Event) {
        if(this.clickOutside === event.target || this.clickOutside.contains((event.target as HTMLElement))) return;

        this.active = false;
        this.content.classList.remove('is-active');
    }
}
