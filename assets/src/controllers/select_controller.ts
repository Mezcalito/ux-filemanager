import { ActionEvent, Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export class SelectController extends Controller<HTMLElement> {
  static values = {
    isActive: Boolean,
  };

  declare isActiveValue: boolean;

  static targets = ['input', 'item', 'current'];

  declare inputTarget: HTMLInputElement;
  declare currentTarget: HTMLElement;
  declare itemTargets: HTMLElement[];

  connect() {
    const activeItem = this.element.querySelector('.c-custom-select__item.is-active') as HTMLElement | null;
    if (activeItem) {
      this.currentTarget.innerHTML = activeItem.innerHTML;
      this.inputTarget.value = activeItem.dataset.value ? activeItem.dataset.value : activeItem.innerText;
    }
  }

  isActiveValueChanged(): void {
    this.element.classList.toggle('is-active', this.isActiveValue);
  }

  toggle(e: UIEvent): void {
    e.stopPropagation();
    this.isActiveValue = !this.isActiveValue;
  }

  selectOption(e: ActionEvent & MouseEvent) {
    if (!e.currentTarget) return;
    this.inputTarget.value = (e.currentTarget as HTMLElement).dataset.value
      ? ((e.currentTarget as HTMLElement).dataset.value as string)
      : (e.currentTarget as HTMLElement).innerText;
    this.currentTarget.innerHTML = (e.currentTarget as HTMLElement)?.innerHTML;
    this.itemTargets.forEach(elem => {
      elem.classList.remove('is-active');
    });
    (e.currentTarget as HTMLElement)?.classList.add('is-active');
    this.isActiveValue = false;
  }

  close(event: ActionEvent) {
    if(this.element === event.target || this.element.contains((event.target as HTMLElement))) return;
    this.isActiveValue = false;
  }
}
