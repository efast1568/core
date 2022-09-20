import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';
import { PopupElement } from 'inclusive-elements';
import { htmlToElement } from '../utils';

export default class extends Controller {
    static targets = ['badge', 'frame'];

    hasBadgeTarget?: boolean;
    badgeTarget?: HTMLElement;

    hasFrameTarget?: boolean;
    frameTarget?: FrameElement;

    connect() {
        this.frameTarget!.removeAttribute('disabled');

        window.Echo.private('Waterhole.Models.User.' + Waterhole.userId).listen(
            'NotificationReceived',
            ({ unreadCount, html }: any) => {
                if (this.hasBadgeTarget) {
                    this.badgeTarget!.hidden = !unreadCount;
                    this.badgeTarget!.innerText = unreadCount;
                }

                if (this.hasFrameTarget) {
                    this.frameTarget!.reload();
                }

                const alert = htmlToElement(html) as HTMLElement;
                if (alert) {
                    Waterhole.alerts.show(alert, { key: 'notification' });
                }

                Waterhole.documentTitle.increment();
            }
        );
    }

    disconnect() {
        window.Echo.leave('Waterhole.Models.User.' + Waterhole.userId);
    }

    open(e: MouseEvent) {
        if (this.hasBadgeTarget) {
            this.badgeTarget!.hidden = true;
        }

        Waterhole.alerts.dismiss('notification');

        // breakpoint-xs
        if (window.innerWidth < 576) {
            window.Turbo.visit((e.currentTarget as HTMLAnchorElement).href);
            (this.element as PopupElement).open = false;
        }
    }
}