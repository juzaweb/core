function jwOnlineStatusesUpdate() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    loadReCaptcha().then(function (jwToken) {
        fetch("/online/statuses", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": token,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                "jw-token": jwToken,
                "view-page": juzaweb.viewPage,
            }),
        });
    });
}

window.addEventListener('load', function () {
    jwOnlineStatusesUpdate();

    setInterval(() => {
        jwOnlineStatusesUpdate();
    }, 60 * 1000);
});
