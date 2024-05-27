import { Controller } from '@hotwired/stimulus';

class DisplayController extends Controller {
    changeMode(event) {
        this.modeValue = event.params.mode;
    }
    modeValueChanged() {
        this.cardTargets.forEach((card) => {
            card.classList.toggle('display-grid', this.modeValue === 'grid');
        });
        this.listTarget.classList.toggle('c-content__cards--grid', this.modeValue === 'grid');
        this.listButtonTarget.classList.toggle('c-toggle-buttons__action--active', this.modeValue === 'list');
        this.gridButtonTarget.classList.toggle('c-toggle-buttons__action--active', this.modeValue === 'grid');
    }
}
DisplayController.values = {
    mode: { type: String, default: 'list' },
};
DisplayController.targets = ['list', 'card', 'listButton', 'gridButton'];

class SubmenuController extends Controller {
    open() {
        this.openValue = true;
    }
    close(event) {
        if (event.target === this.panelTarget) {
            this.openValue = false;
        }
    }
    openValueChanged() {
        this.panelTarget.classList.toggle('c-submenu--open', this.openValue);
    }
}
SubmenuController.values = {
    open: { type: Boolean, default: false },
};
SubmenuController.targets = ['panel'];

class CollapseController extends Controller {
    constructor() {
        super(...arguments);
        this.overflow = false;
    }
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
    onContentResize(entries) {
        const { height } = entries[0].contentRect;
        this.element.style.setProperty('--collapse-height', `${height}px`);
        this.element.style.setProperty('--collapse-width', `auto`);
    }
    onTransitionEnd() {
        this.element.classList.remove('c-collapse--transition');
    }
    visibleValueChanged(visible) {
        if (!this.hasWrapperTarget || !this.hasContentTarget)
            return;
        this.element.classList.toggle('c-collapse--visible', visible);
        this.element.classList.toggle('c-collapse--hidden', !visible);
        this.element.classList.add('c-collapse--transition');
        if (visible) {
            if (this.hasVisibleClass)
                this.element.classList.add(...this.visibleClasses);
            if (this.hasHiddenClass)
                this.element.classList.remove(...this.hiddenClasses);
            this.wrapperTarget.style.setProperty('opacity', '1');
            this.dispatch('show', { detail: { fade: this.fadeValue, gradient: this.gradientValue } });
        }
        else {
            if (this.hasVisibleClass)
                this.element.classList.remove(...this.visibleClasses);
            if (this.hasHiddenClass)
                this.element.classList.add(...this.hiddenClasses);
            this.wrapperTarget.style.setProperty('opacity', this.fadeValue ? '0' : '1');
            this.dispatch('hide', { detail: { fade: this.fadeValue, gradient: this.gradientValue } });
        }
    }
    gradientValueChanged(gradient) {
        this.element.classList.toggle('c-collapse--gradient', gradient);
    }
    fadeValueChanged(fade) {
        this.element.classList.toggle('c-collapse--fade', fade);
        if (!this.visibleValue)
            this.wrapperTarget.style.setProperty('opacity', fade ? '0' : '1');
    }
    disconnect() {
        var _a;
        (_a = this.contentResizeObserver) === null || _a === void 0 ? void 0 : _a.disconnect();
    }
}
CollapseController.values = {
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
CollapseController.targets = ['wrapper', 'content'];
CollapseController.classes = ['visible', 'hidden', 'overflow'];

class ToggleController extends Controller {
    change() {
        this.isActiveValue = !this.isActiveValue;
    }
    close(event) {
        if (this.element === event.target || this.element.contains(event.target))
            return;
        this.isActiveValue = false;
    }
    isActiveValueChanged() {
        this.contentTarget.classList.toggle('is-active', this.isActiveValue);
    }
}
ToggleController.values = {
    isActive: Boolean,
};
ToggleController.targets = ['content'];

export { CollapseController, DisplayController, SubmenuController, ToggleController };
