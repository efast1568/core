import { Controller } from '@hotwired/stimulus';
import { debounce } from 'lodash-es';

/**
 * Controller to power incremental search.
 *
 *
 */
export default class extends Controller<HTMLInputElement> {
    input(e: InputEvent) {
        if ((e.target as HTMLInputElement).value) {
            this.debouncedSubmit();
        } else {
            this.submit();
        }
    }

    submit() {
        this.element.form?.requestSubmit();
    }

    debouncedSubmit = debounce(this.submit, 250);
}
