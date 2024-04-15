import { Controller } from '@hotwired/stimulus';

class controller extends Controller {
    connect() {
        console.log('Hello from Stimulus Controller');
    }
}

export { controller as default };
