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
        this.listButtonTarget.classList.toggle('c-toggle-buttons__active', this.modeValue === 'list');
        this.gridButtonTarget.classList.toggle('c-toggle-buttons__active', this.modeValue === 'grid');
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

export { DisplayController, SubmenuController };
