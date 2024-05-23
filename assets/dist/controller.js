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

export { DisplayController };
