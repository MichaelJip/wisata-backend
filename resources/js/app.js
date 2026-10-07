//
document.addEventListener("click", async (event) => {
    const btn = event.target.closest("[data-delete-url]");
    if (!btn) return;

    if (
        !confirm(btn.dataset.confirm ?? "Are you sure you want to delete this?")
    )
        return;

    const res = await fetch(btn.dataset.deleteUrl, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
    });

    if (res.ok) {
        document.getElementById(btn.dataset.deleteRow)?.remove();
    } else {
        alert("Failed to delete.");
    }
});
