import { Controller } from '@hotwired/stimulus';

export class CollapseController extends Controller<HTMLElement> {
    static values = {
        visible: {
            type: Boolean,
            default: false,
        },
        gradient: {
            type: Boolean,
            default: false,
        },
        fade: {
            type: Boolean,
            default: true,
        },
    };

    static targets = ['wrapper', 'content'];

    static classes = ['visible', 'hidden', 'overflow'];

    declare visibleValue: boolean;
    declare gradientValue: boolean;
    declare fadeValue: boolean;

    declare readonly wrapperTarget: HTMLElement;
    declare readonly hasWrapperTarget: boolean;
    declare readonly contentTarget: HTMLElement;
    declare readonly hasContentTarget: boolean;

    declare readonly visibleClasses: string[];
    declare readonly hasVisibleClass: boolean;
    declare readonly hiddenClasses: string[];
    declare readonly hasHiddenClass: boolean;
    declare readonly overflowClasses: string[];
    declare readonly hasOverflowClass: boolean;

    private overflow: boolean = false;

    private wrapperResizeObserver?: ResizeObserver;
    private contentResizeObserver?: ResizeObserver;

    connect() {
        if (!this.hasWrapperTarget) {
            console.warn('CollapseController : The wrapper target is required');
            return;
        }
        if (!this.hasContentTarget) {
            console.warn('CollapseController : The content target is required');
            return;
        }

        this.wrapperResizeObserver = new ResizeObserver(this.onWrapperResize.bind(this));
        this.wrapperResizeObserver.observe(this.wrapperTarget);
        this.wrapperTarget.addEventListener('transitionend', this.onTransitionEnd.bind(this));

        this.contentResizeObserver = new ResizeObserver(this.onContentResize.bind(this));
        this.contentResizeObserver.observe(this.contentTarget);
    }

    show() {
        this.visibleValue = true;
    }

    hide() {
        this.visibleValue = false;
    }

    toggle() {
        this.visibleValue = !this.visibleValue;
    }

    onWrapperResize() {
        const { height: wrapperHeight } = this.wrapperTarget.getBoundingClientRect();
        const { height: contentHeight } = this.contentTarget.getBoundingClientRect();

        this.overflow = wrapperHeight < contentHeight;
        this.element.classList.toggle('c-collapse--overflow', this.overflow);

        if (this.hasOverflowClass) {
            this.overflow
                ? this.element.classList.add(...this.overflowClasses)
                : this.element.classList.remove(...this.overflowClasses);
        }
    }

    onContentResize(entries: ResizeObserverEntry[]) {
        const { height } = entries[0].contentRect;
        this.element.style.setProperty('--collapse-height', `${height}px`);
        this.element.style.setProperty('--collapse-width', `auto`);
    }

    onTransitionEnd() {
        this.element.classList.remove('c-collapse--transition');
    }

    visibleValueChanged(visible: boolean): void {
        if (!this.hasWrapperTarget || !this.hasContentTarget) return;

        this.element.classList.toggle('c-collapse--visible', visible);
        this.element.classList.toggle('c-collapse--hidden', !visible);

        this.element.classList.add('c-collapse--transition');

        if (visible) {
            if (this.hasVisibleClass) this.element.classList.add(...this.visibleClasses);
            if (this.hasHiddenClass) this.element.classList.remove(...this.hiddenClasses);
            this.wrapperTarget.style.setProperty('opacity', '1');

            this.dispatch('show', { detail: { fade: this.fadeValue, gradient: this.gradientValue } });
        } else {
            if (this.hasVisibleClass) this.element.classList.remove(...this.visibleClasses);
            if (this.hasHiddenClass) this.element.classList.add(...this.hiddenClasses);
            this.wrapperTarget.style.setProperty('opacity', this.fadeValue ? '0' : '1');

            this.dispatch('hide', { detail: { fade: this.fadeValue, gradient: this.gradientValue } });
        }
    }

    gradientValueChanged(gradient: boolean) {
        this.element.classList.toggle('c-collapse--gradient', gradient);
    }

    fadeValueChanged(fade: boolean) {
        this.element.classList.toggle('c-collapse--fade', fade);
        if (!this.visibleValue) this.wrapperTarget.style.setProperty('opacity', fade ? '0' : '1');
    }

    /* c8 ignore start */
    disconnect() {
        this.contentResizeObserver?.disconnect();
    }
    /* c8 ignore end */
}
