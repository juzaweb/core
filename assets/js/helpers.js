toastr.options.timeOut = 3000;

function toastr_message(message, success, title = null) {
    if (success === true) {
        toastr.success(message, title || juzaweb.lang.successfully);
    } else {
        toastr.error(message, title || juzaweb.lang.error);
    }
}

function confirm_message(question, callback, title = '', type = 'warning') {
    Swal.fire({
        title: title,
        text: question,
        type: type,
        position: 'top',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: juzaweb.lang.yes + '!',
        cancelButtonText: juzaweb.lang.cancel + '!',
    }).then((result) => {
        callback(result.value);
    });
}

function get_message_response(response) {
    // Get message validate
    if (response.responseJSON) {
        if (response.responseJSON.errors) {
            let message = '';
            $.each(response.responseJSON.errors, function (index, msg) {
                message = msg[0];
                return false;
            });

            return {
                success: false,
                message: message
            };
        }

        else if (response.responseJSON.message) {
            return {
                success: false,
                message: response.responseJSON.message
            };
        }
    }

    // Get message errors
    if (response.message) {
        return {
            success: false,
            message: response.message.message,
        };
    }

    return null;
}

function show_message(response, append = false) {
    let msg = get_message_response(response);
    if (!msg) {
        return;
    }

    let msgHTML = `<div class="alert alert-${msg.success ? 'success' : 'danger'} jw-message">
        <button type="button" class="close" data-dismiss="alert" aria-label="${juzaweb.lang.close}">
            <span aria-hidden="true">&times;</span>
        </button>

        ${msg.success ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'} ${msg.message}
    </div>`;

    if (append) {
        $('#jquery-message').append(msgHTML);
    } else {
        $('#jquery-message').html(msgHTML);
    }
}

function show_notify(response) {
    let msg = get_message_response(response);
    toastr_message(msg.message, msg.success);
}

function htmlspecialchars(str) {
    str = String(str);
    return str.replace('&', '&amp;').replace('"', '&quot;').replace("'", '&#039;').replace('<', '&lt;').replace('>', '&gt;');
}

function toggle_global_loading(status, timeout = 300) {
    if (status) {
        $("#admin-overlay").fadeIn(300);
    } else {
        setTimeout(function () {
            $("#admin-overlay").fadeOut(300);
        }, timeout);
    }
}

function replace_template(template, data) {
    return template.replace(
        /{(\w*)}/g,
        function (m, key) {
            return data.hasOwnProperty(key) ? data[key] : "";
        }
    );
}

function process_each(elements, cb, timeout, options = {}) {
    let i = 0;
    let l = elements.length;

    (function fn() {
        let result = cb.call(elements[i++]);
        if (i < l) {
            setTimeout(fn, timeout);
        } else {
            if (options.completeCallback ?? false) {
                options.completeCallback(result);
            }
        }
    }());
}

function random_string(length) {
    let result = '';
    let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() *
            charactersLength));
    }
    return result;
}
