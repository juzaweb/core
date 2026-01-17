const firebaseConfig = {
    apiKey: "AIzaSyDwGMZguGCmhFpGlwa-SscGA25tjgb403Y",
    authDomain: "juzaweb.firebaseapp.com",
    projectId: "juzaweb",
    storageBucket: "juzaweb.firebasestorage.app",
    messagingSenderId: "611082949945",
    appId: "1:611082949945:web:94d3648a4c8bdaee8904e8",
    measurementId: "G-6G84QG4LPN"
};

const vapidKey = "BOgx9DqwxgyQXi4OaLejklqAKh_G41QNT42ebx3qz4adByZ_EmwVeednnySnu6Ompq2A2DcNUfBgKE2d_hzS6bc";

async function askNotificationPermission(messaging) {
    if (localStorage.getItem("notification_permission_asked")) {
        return;
    }

    localStorage.setItem("notification_permission_asked", "true");
    try {
        const permission = await Notification.requestPermission();

        if (permission === "granted") {
            const token = await messaging.getToken({ vapidKey });
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            await fetch("/notification/fcm/subscribe", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf,
                },
                body: JSON.stringify({ token: token}),
            });
        } else {
            console.warn("🚫 Notification denied");
        }
    } catch (error) {
        console.error("🚫 Unable to get permission to notify.", error);
    }
}

// check is https
if (location.protocol === "https:" && "Notification" in window && juzaweb?.firebase?.messaging_enabled) {
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    document.addEventListener("DOMContentLoaded", () => {
        if (!localStorage.getItem("notification_permission_asked")) {
            setTimeout(() => {
                askNotificationPermission(messaging);
            }, 3000);
        }
    });

    // messaging.onMessage((payload) => {
    //     if (typeof toastr !== "undefined") {
    //         toastr.info(payload.notification.body, payload.notification.title);
    //     }
    // });
}
