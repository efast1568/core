import * as Turbo from '@hotwired/turbo';
import { FrameElement } from '@hotwired/turbo';
// @ts-ignore
import { morph } from 'idiomorph';
import { cloneFromTemplate } from '../utils';

declare global {
    interface Window {
        Turbo: any;
    }
}

Turbo.start();

window.Turbo = Turbo;

// Use idiomorph for "replace" Turbo Streams for more fine-grained DOM patching,
// which will hopefully preserve keyboard focus.
document.addEventListener('turbo:before-stream-render', (e) => {
    const stream = e.target as Turbo.StreamElement;

    if (stream.action === 'replace') {
        e.preventDefault();
        stream.targetElements.forEach((el) => {
            morph(el, stream.templateContent.firstElementChild!);
        });
    }
});

document.addEventListener('turbo:before-fetch-response', async (e) => {
    const response = (e as CustomEvent).detail.fetchResponse.response;
    if (response.ok || response.status === 422 || Waterhole.debug) return;
    e.preventDefault();

    const { target } = e;
    if (target instanceof FrameElement) {
        const el = cloneFromTemplate('frame-error');
        el.querySelector('button')?.addEventListener('click', () => {
            target.reload();
            target.replaceChildren(cloneFromTemplate('loading'));
        });
        target.replaceChildren(el);
    } else {
        Waterhole.fetchError(response);
    }
});

Waterhole.fetchError = function (response: Response): void {
    let templateId;
    switch (response.status) {
        case 401:
        case 403:
            templateId = 'forbidden-alert';
            break;

        case 429:
            templateId = 'too-many-requests-alert';
            break;

        default:
            templateId = 'fatal-error-alert';
    }

    const alert = cloneFromTemplate(templateId);

    if (alert) {
        this.alerts.show(alert, { key: 'fetchError', duration: -1 });
    }
};
