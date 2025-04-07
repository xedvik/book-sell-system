/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
import Pusher from 'pusher-js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Pusher.logToConsole = false;

const pusher = new Pusher('8fb096f345a29c098f59', {
    cluster: 'eu',
    enabledTransports: ['ws', 'wss']
});

// Подписываемся на канал purchases
const purchasesChannel = pusher.subscribe('purchases');

// Прослушиваем событие о покупке книги
purchasesChannel.bind('book.purchased', function(data) {
    console.log('Получено событие о покупке книги:', data);

    if (window.showPurchaseNotification) {
        window.showPurchaseNotification(data);
    }
});

// Добавляем Pusher и клиент в глобальную область видимости
window.Pusher = Pusher;
window.pusherClient = pusher;
