import {ActionEvent, Controller} from '@hotwired/stimulus';

export class ToggleController extends Controller {
  static values = {
    isActive: Boolean,
  };

  static targets = ['content'];

  declare contentTarget: HTMLElement;
  declare isActiveValue: boolean;

  change() {
    this.isActiveValue = !this.isActiveValue;
  }

  close(event: ActionEvent) {
    if(this.element === event.target || this.element.contains((event.target as HTMLElement))) return;

    this.isActiveValue = false;
  }

  isActiveValueChanged(): void {
    this.contentTarget.classList.toggle('is-active', this.isActiveValue);
  }
}
