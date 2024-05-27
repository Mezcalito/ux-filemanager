import { Controller } from '@hotwired/stimulus';

export class ModalController extends Controller {
  static values = {
    isActive: Boolean,
  };

  static targets = ['content'];

  declare contentTarget: HTMLElement;
  declare isActiveValue: boolean;

  connect() {
    this.isActiveValue = true;
  }

  change() {
    this.isActiveValue = !this.isActiveValue;
  }

  isActiveValueChanged(): void {
    this.contentTarget.classList.toggle('c-modal--open', this.isActiveValue);
    document.body.classList.toggle('u-overflow-hidden', this.isActiveValue);
  }
}
