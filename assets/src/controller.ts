import { Controller } from '@hotwired/stimulus';
import './styles.scss';

export default class extends Controller {
    connect() {
        console.log('Hello from Stimulus Controller');
    }
}
