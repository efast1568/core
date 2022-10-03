import { Controller } from '@hotwired/stimulus';

/**
 * Controller for the icon-picker component.
 *
 * @internal
 */
export default class extends Controller {
    static targets = ['preview', 'form'];

    declare readonly previewTarget: HTMLElement;
    declare readonly formTarget: HTMLElement;

    change() {
        this.previewTarget.hidden = true;
        this.formTarget.hidden = false;
    }
}
