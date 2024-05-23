import { Controller } from '@hotwired/stimulus';

class DisplayController extends Controller {
    changeMode(event) {
        this.modeValue = event.params.mode;
    }
    modeValueChanged() {
        this.nodeTargets.forEach((node) => {
            node.classList.toggle('display-grid', this.modeValue === 'grid');
        });
    }
}
DisplayController.values = {
    mode: { type: String, default: 'list' },
};
DisplayController.targets = ['node'];

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
