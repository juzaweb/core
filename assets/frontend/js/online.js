function jwOnlineStatusesUpdate() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch("/online/statuses", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": token,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            "view-page": juzaweb.viewPage,
        }),
    });
}

window.addEventListener('load', function () {
    jwOnlineStatusesUpdate();

    setInterval(() => {
        jwOnlineStatusesUpdate();
    }, 60 * 1000);
});
