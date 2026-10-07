/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

/**
 * Global AJAX delete.
 * Any element with data-delete-url deletes via fetch and removes the row
 * given by data-delete-row. Optional data-delete-message customises the prompt
 * (not data-confirm, which Stisla's scripts.js turns into its own modal).
 */
document.addEventListener('click', async (event) => {
    const btn = event.target.closest('[data-delete-url]');
    if (!btn) return;

    if (!confirm(btn.dataset.deleteMessage ?? 'Are you sure you want to delete this?')) return;

    const res = await fetch(btn.dataset.deleteUrl, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    });

    if (res.ok) {
        document.getElementById(btn.dataset.deleteRow)?.remove();
    } else {
        const data = await res.json().catch(() => ({}));
        alert(data.message ?? 'Failed to delete.');
    }
});
