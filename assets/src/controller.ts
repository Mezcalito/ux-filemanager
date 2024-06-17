import './styles.scss';

import {ActionEvent, Controller} from "@hotwired/stimulus";
import {Toggle} from "./scripts/toggle";
import {Collapse} from "./scripts/collapse";

export default class extends Controller {
    static targets = [ 'list', 'card', 'dialog', 'modalAction', 'modalOldValue', 'select', 'dropdown', 'submenu', 'collapse' ];

    declare listTarget: HTMLElement;
    declare cardTargets: HTMLElement[];
    declare dialogTarget: HTMLDialogElement;
    declare modalActionTarget: HTMLInputElement;
    declare modalOldValueTarget: HTMLInputElement;

    private toggleMap: Map<HTMLElement, Toggle> = new Map();
    private collapseMap: Map<HTMLElement, Collapse> = new Map();

    initialize() {
        window.addEventListener('modal:close', () => this.closeModal());
    }

    changeDisplayMode(event: ActionEvent) {
        document.querySelectorAll('.fm-c-toggle-buttons__action').forEach((button) => {
            button.classList.remove('fm-c-toggle-buttons__action--active')
        });
        (event.currentTarget as HTMLButtonElement).classList.add('fm-c-toggle-buttons__action--active');

        const mode = event.params.mode;

        this.cardTargets.forEach((card) => {
            card.classList.toggle('display-grid', mode === 'grid')
        });
        this.listTarget.classList.toggle('fm-c-content__cards--grid', mode === 'grid');
    }

    openModal({params}: {params: any}) {
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

    selectTargetConnected(element: HTMLElement) {
        const toggle = new Toggle(element, '.fm-c-custom-select__input-wrapper', '.fm-c-custom-select__list-wrapper');
        toggle.connect();

        this.toggleMap.set(element, toggle);
    }

    selectTargetDisconnected(element: HTMLElement) {
        const toggle = this.toggleMap.get(element) as Toggle;
        toggle.disconnect()

        this.toggleMap.delete(element);
    }

    dropdownTargetConnected(element: HTMLElement) {
        const toggle = new Toggle(element, '.fm-c-card-actions__action', '.fm-c-card-actions__content');
        toggle.connect();

        this.toggleMap.set(element, toggle);
    }

    dropdownTargetDisconnected(element: HTMLElement) {
        const toggle = this.toggleMap.get(element) as Toggle;
        toggle.disconnect()

        this.toggleMap.delete(element);
    }

    submenuTargetConnected(element: HTMLElement) {
        const toggle = new Toggle(element, '.fm-c-content__sticky-button', '.fm-c-submenu', '.fm-c-submenu__content');
        toggle.connect();

        this.toggleMap.set(element, toggle);
    }

    submenuTargetDisconnected(element: HTMLElement) {
        const toggle = this.toggleMap.get(element) as Toggle;
        toggle.disconnect()

        this.toggleMap.delete(element);
    }

    collapseTargetConnected(element: HTMLElement) {
        const collapse = new Collapse(element);
        collapse.connect();

        this.collapseMap.set(element, collapse);
    }

    collapseTargetDisconnected(element: HTMLElement) {
        const collapse = this.collapseMap.get(element) as Collapse;
        collapse.disconnect()

        this.collapseMap.delete(element);
    }
}