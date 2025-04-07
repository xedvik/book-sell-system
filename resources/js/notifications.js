function createToastStyles() {
    if (!document.getElementById('toast-styles')) {
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.textContent = `
            .toast-container {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 9999;
            }
            .toast {
                background-color: #fff;
                border-left: 4px solid #10b981;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 4px;
                padding: 16px;
                margin-bottom: 10px;
                width: 300px;
                animation: toast-in-right 0.7s;
                display: flex;
                justify-content: space-between;
            }
            .toast-title {
                font-weight: bold;
                margin-bottom: 5px;
            }
            .toast-message {
                font-size: 14px;
            }
            .toast-close {
                cursor: pointer;
                font-size: 16px;
                opacity: 0.7;
                margin-left: 10px;
                align-self: flex-start;
            }
            .toast-close:hover {
                opacity: 1;
            }
            @keyframes toast-in-right {
                from { transform: translateX(100%); }
                to { transform: translateX(0); }
            }
        `;
        document.head.appendChild(style);
    }
}

function getToastContainer() {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    return container;
}

/**
 * Показать уведомление
 *
 * @param {string} title
 * @param {string} message
 * @param {number} duration
 */
export function showToast(title, message, duration = 5000) {
    createToastStyles();
    const container = getToastContainer();

    const toast = document.createElement('div');
    toast.className = 'toast';

    const content = document.createElement('div');

    const titleElement = document.createElement('div');
    titleElement.className = 'toast-title';
    titleElement.textContent = title;
    content.appendChild(titleElement);

    const messageElement = document.createElement('div');
    messageElement.className = 'toast-message';
    messageElement.textContent = message;
    content.appendChild(messageElement);

    const closeButton = document.createElement('span');
    closeButton.className = 'toast-close';
    closeButton.innerHTML = '&times;';
    closeButton.onclick = () => {
        container.removeChild(toast);
    };

    toast.appendChild(content);
    toast.appendChild(closeButton);
    container.appendChild(toast);

    setTimeout(() => {
        if (container.contains(toast)) {
            container.removeChild(toast);
        }
    }, duration);
}

/**
 * Показать уведомление о покупке книги
 *
 * @param {Object} data
 */
export function showPurchaseNotification(data) {
    const purchaseInfo = {
        purchaseId: data.purchase_id,
        bookId: data.book_id,
        bookTitle: data.book_title,
        userId: data.user_id,
        userName: data.user_name,
        quantity: data.quantity,
        price: data.price,
        totalPrice: data.total_price,
        purchasedAt: data.purchased_at
    };

    console.log('Информация о покупке:', purchaseInfo);

    showToast(
        'Новая покупка!',
        `${purchaseInfo.userName} приобрел книгу "${purchaseInfo.bookTitle}" (${purchaseInfo.quantity} шт.) на сумму ${purchaseInfo.totalPrice} руб.`
    );

}
window.showPurchaseNotification = showPurchaseNotification;
