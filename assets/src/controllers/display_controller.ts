import {ActionEvent, Controller} from '@hotwired/stimulus';

export class DisplayController extends Controller {
    static values = {
        mode: { type: String, default: 'list' },
    };
    declare modeValue: String;

    static targets = [ 'node' ];
    declare nodeTargets: HTMLElement[];

    changeMode(event: ActionEvent) {
        this.modeValue = event.params.mode;
    }

    modeValueChanged() {
        this.nodeTargets.forEach((node) => {
            node.classList.toggle('display-grid', this.modeValue === 'grid');
        });
    }
}
