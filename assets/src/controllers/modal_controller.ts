import {Controller} from '@hotwired/stimulus';

export class ModalController extends Controller {
  static targets = ['dialog', 'action', 'oldValue'];
  declare dialogTarget: HTMLDialogElement;
  declare actionTarget: HTMLInputElement;
  declare oldValueTarget: HTMLInputElement;

  initialize() {
    window.addEventListener('modal:close', () => this.close());
  }

  open({params}: {params: any}) {
    if (params.value) {
      this.oldValueTarget.value = params.value;
      this.oldValueTarget.dispatchEvent(new Event('change', { bubbles: true }));
    }

    this.actionTarget.value = params.action;
    this.actionTarget.dispatchEvent(new Event('change', { bubbles: true }));

    this.dialogTarget.showModal();
    this.dialogTarget.classList.add('c-modal--open');
    document.body.classList.add('u-overflow-hidden');
  }

  close() {
    this.oldValueTarget.value = '';
    this.oldValueTarget.dispatchEvent(new Event('change', { bubbles: true }));
    this.actionTarget.value = '';
    this.actionTarget.dispatchEvent(new Event('change', { bubbles: true }));

    this.dialogTarget.classList.remove('c-modal--open');
    this.dialogTarget.close();
    document.body.classList.remove('u-overflow-hidden');
  }
}
