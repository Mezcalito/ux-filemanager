import {ActionEvent, Controller} from '@hotwired/stimulus';

export class DisplayController extends Controller {
    static values = {
        mode: { type: String, default: 'list' },
    };
    declare modeValue: String;

    static targets = [ 'list', 'card', 'listButton', 'gridButton' ];
    declare listTarget: HTMLElement;
    declare cardTargets: HTMLElement[];
    declare listButtonTarget: HTMLElement;
    declare gridButtonTarget: HTMLElement;

    changeMode(event: ActionEvent) {
        this.modeValue = event.params.mode;
    }

    //c-toggle-buttons__active

    modeValueChanged() {
        this.cardTargets.forEach((card) => {
            card.classList.toggle('display-grid', this.modeValue === 'grid');
        });
        this.listTarget.classList.toggle('c-content__cards--grid', this.modeValue === 'grid');
        this.listButtonTarget.classList.toggle('c-toggle-buttons__action--active', this.modeValue === 'list');
        this.gridButtonTarget.classList.toggle('c-toggle-buttons__action--active', this.modeValue === 'grid');
    }
}
