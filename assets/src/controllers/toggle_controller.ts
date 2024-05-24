import { Controller } from '@hotwired/stimulus';
import { useClickOutside } from 'stimulus-use';

export class ToggleController extends Controller {
  static values = {
    isActive: Boolean,
  };

  static targets = ['content'];

  declare contentTarget: HTMLElement;
  declare isActiveValue: boolean;

  connect() {
    //@ts-ignore
    useClickOutside(this);
  }

  clickOutside() {
    this.isActiveValue = false;
  }

  isActiveValueChanged(): void {
    this.contentTarget.classList.toggle('is-active', this.isActiveValue);
  }

  change() {
    this.isActiveValue = !this.isActiveValue;
  }
}
