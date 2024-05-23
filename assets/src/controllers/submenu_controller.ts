import {ActionEvent, Controller} from '@hotwired/stimulus';

export class SubmenuController extends Controller {
    static values = {
        open: { type: Boolean, default: false },
    };
    declare openValue: boolean;

    static targets = [ 'panel' ];
    declare panelTarget: HTMLElement;

    open() {
        this.openValue = true;
    }

    close(event: ActionEvent) {
        if (event.target === this.panelTarget) {
            this.openValue = false;
        }
    }

    openValueChanged() {
        this.panelTarget.classList.toggle('c-submenu--open', this.openValue);
    }
}
