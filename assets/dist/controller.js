import { Controller } from '@hotwired/stimulus';

class Toggle {
    constructor(wrapper, triggerSelector, contentSelector, clickOutsideSelector) {
        this.active = false;
        this.wrapper = wrapper;
        this.trigger = wrapper.querySelector(triggerSelector);
        this.content = wrapper.querySelector(contentSelector);
        if (clickOutsideSelector === undefined) {
            clickOutsideSelector = contentSelector;
        }
        this.clickOutside = wrapper.querySelector(clickOutsideSelector);
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
    close(event) {
        if (this.clickOutside === event.target || this.clickOutside.contains(event.target))
            return;
        this.active = false;
        this.content.classList.remove('is-active');
    }
}

class Collapse {
    constructor(element) {
        this.visible = false;
        this.element = element;
        this.trigger = element.querySelector('.fm-c-collapse__arrow');
        this.wrapper = element.querySelector('.fm-c-collapse__wrapper');
        this.content = element.querySelector('.fm-c-collapse__content');
        this.visible = 'true' === element.dataset.visible;
    }
    connect() {
        this.trigger.addEventListener('click', this.collapse.bind(this), true);
    }
    disconnect() {
        this.trigger.removeEventListener('click', this.collapse.bind(this), true);
    }
    collapse() {
        if (null === this.wrapper || null === this.content)
            return;
        this.visible = !this.visible;
        this.element.classList.toggle('fm-c-collapse--visible', this.visible);
        this.element.classList.toggle('fm-c-collapse--hidden', !this.visible);
        this.element.dataset.visible = this.visible ? 'true' : 'false';
    }
}

class default_1 extends Controller {
    constructor() {
        super(...arguments);
        this.toggleMap = new Map();
        this.collapseMap = new Map();
    }
    initialize() {
        window.addEventListener('modal:close', () => this.closeModal());
    }
    changeDisplayMode(event) {
        document.querySelectorAll('.fm-c-toggle-buttons__action').forEach(button => {
            button.classList.remove('fm-c-toggle-buttons__action--active');
        });
        event.currentTarget.classList.add('fm-c-toggle-buttons__action--active');
        const mode = event.params.mode;
        this.cardTargets.forEach(card => {
            card.classList.toggle('display-grid', mode === 'grid');
        });
        this.listTarget.classList.toggle('fm-c-content__cards--grid', mode === 'grid');
    }
    openModal(event) {
        const { params } = event;
        if (params.value) {
            this.modalOldValueTarget.value = params.value;
            this.modalOldValueTarget.dispatchEvent(new Event('change', { bubbles: true }));
        }
        this.modalActionTarget.value = params.action;
        this.modalActionTarget.dispatchEvent(new Event('change', { bubbles: true }));
        this.dialogTarget.showModal();
        this.dialogTarget.classList.add('fm-c-modal--open');
        document.body.classList.add('fm-u-overflow-hidden');
    }
    closeModal() {
        this.modalOldValueTarget.value = '';
        this.modalOldValueTarget.dispatchEvent(new Event('change', { bubbles: true }));
        this.modalActionTarget.value = '';
        this.modalActionTarget.dispatchEvent(new Event('change', { bubbles: true }));
        this.dialogTarget.classList.remove('fm-c-modal--open');
        this.dialogTarget.close();
        document.body.classList.remove('fm-u-overflow-hidden');
    }
    selectTargetConnected(element) {
        const toggle = new Toggle(element, '.fm-c-custom-select__input-wrapper', '.fm-c-custom-select__list-wrapper');
        toggle.connect();
        this.toggleMap.set(element, toggle);
    }
    selectTargetDisconnected(element) {
        const toggle = this.toggleMap.get(element);
        toggle.disconnect();
        this.toggleMap.delete(element);
    }
    dropdownTargetConnected(element) {
        const toggle = new Toggle(element, '.fm-c-card-actions__action', '.fm-c-card-actions__content');
        toggle.connect();
        this.toggleMap.set(element, toggle);
    }
    dropdownTargetDisconnected(element) {
        const toggle = this.toggleMap.get(element);
        toggle.disconnect();
        this.toggleMap.delete(element);
    }
    submenuTargetConnected(element) {
        const toggle = new Toggle(element, '.fm-c-content__sticky-button', '.fm-c-submenu', '.fm-c-submenu__content');
        toggle.connect();
        this.toggleMap.set(element, toggle);
    }
    submenuTargetDisconnected(element) {
        const toggle = this.toggleMap.get(element);
        toggle.disconnect();
        this.toggleMap.delete(element);
    }
    collapseTargetConnected(element) {
        const collapse = new Collapse(element);
        collapse.connect();
        this.collapseMap.set(element, collapse);
    }
    collapseTargetDisconnected(element) {
        const collapse = this.collapseMap.get(element);
        collapse.disconnect();
        this.collapseMap.delete(element);
    }
    onSearchKeyDown(e) {
        e.preventDefault();
        const currentIndex = this.searchResultTargets.findIndex(searchResult => searchResult.hasAttribute('aria-current'));
        const nextIndex = currentIndex >= this.searchResultTargets.length - 1 ? 0 : currentIndex + 1;
        this.searchResultTargets.forEach((searchResult, index) => {
            index === nextIndex
                ? searchResult.setAttribute('aria-current', 'true')
                : searchResult.removeAttribute('aria-current');
        });
    }
    onSearchKeyUp(e) {
        e.preventDefault();
        const currentIndex = this.searchResultTargets.findIndex(searchResult => searchResult.hasAttribute('aria-current'));
        const prevIndex = currentIndex === 0 ? this.searchResultTargets.length - 1 : currentIndex - 1;
        this.searchResultTargets.forEach((searchResult, index) => {
            index === prevIndex
                ? searchResult.setAttribute('aria-current', 'true')
                : searchResult.removeAttribute('aria-current');
        });
    }
    onSearchEnter(e) {
        e.preventDefault();
        const selectedResult = this.searchResultTargets.find(searchResult => searchResult.hasAttribute('aria-current'));
        if (!selectedResult)
            return;
        const button = selectedResult.querySelector('[data-fm-enter-action]');
        if (!button)
            return;
        button.dispatchEvent(new MouseEvent('click'));
        e.target.blur();
    }
    onSearchFocus() {
        this.element.setAttribute('data-fm-search-active', '');
    }
    onSearchBlur() {
        this.element.removeAttribute('data-fm-search-active');
    }
}
default_1.targets = [
    'list',
    'card',
    'dialog',
    'modalAction',
    'modalOldValue',
    'select',
    'dropdown',
    'submenu',
    'collapse',
    'searchResult',
];

export { default_1 as default };
