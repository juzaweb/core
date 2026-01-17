// toastr.options.timeOut = 3000;

function toastr_message(message, success, title = null) {
    if (typeof toastr === 'undefined') {
        console.error('Toastr is not defined. Please include it in your project.');
        return;
    }

    if (success === true) {
        toastr.success(message, title || juzaweb.lang.successfully, {timeOut: 3000});
    } else {
        toastr.error(message, title || juzaweb.lang.error, {timeOut: 3000});
    }
}

function confirm_message(question, callback, title = '', type = 'warning') {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 is not defined. Please include it in your project.');
        return;
    }

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
    if (response.success === true) {
        if (! response.message) {
            return null;
        }

        return {
            success: true,
            message: response.message
        };
    }

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
            message: response.message,
        };
    }

    return null;
}

function show_message(response, append = false, elResult = null) {
    let msg = get_message_response(response);

    if (! msg || response.redirect) {
        return;
    }

    let msgHTML = `<div class="text-${msg.success ? 'success' : 'danger'} jw-message">
        ${msg.message}
    </div>`;

    if (append) {
        $(elResult ?? '#jquery-message').append(msgHTML);
    } else {
        $(elResult ?? '#jquery-message').html(msgHTML);
    }

    if (!$(elResult ?? '#jquery-message').is(':visible')) {
        $(elResult ?? '#jquery-message').show();
    }

    $('html, body').animate({
        scrollTop: ($(elResult ?? '#jquery-message').offset().top - 100)
    }, 500);
}

function show_notify(response) {
    let msg = get_message_response(response);
    if (! msg) {
        return;
    }
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
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function uuidv4() {
    return "10000000-1000-4000-8000-100000000000".replace(/[018]/g, c =>
        (+c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> +c / 4).toString(16)
    );
}

function generate_slug(text) {
    const map = {
        'à': 'a', 'á': 'a', 'ả': 'a', 'ã': 'a', 'ạ': 'a', 'ă': 'a', 'ằ': 'a', 'ắ': 'a', 'ẳ': 'a', 'ẵ': 'a', 'ặ': 'a',
        'â': 'a', 'ầ': 'a', 'ấ': 'a', 'ẩ': 'a', 'ẫ': 'a', 'ậ': 'a',
        'è': 'e', 'é': 'e', 'ẻ': 'e', 'ẽ': 'e', 'ẹ': 'e', 'ê': 'e', 'ề': 'e', 'ế': 'e', 'ể': 'e', 'ễ': 'e', 'ệ': 'e',
        'ì': 'i', 'í': 'i', 'ỉ': 'i', 'ĩ': 'i', 'ị': 'i',
        'ò': 'o', 'ó': 'o', 'ỏ': 'o', 'õ': 'o', 'ọ': 'o', 'ô': 'o', 'ồ': 'o', 'ố': 'o', 'ổ': 'o', 'ỗ': 'o', 'ộ': 'o',
        'ơ': 'o', 'ờ': 'o', 'ớ': 'o', 'ở': 'o', 'ỡ': 'o', 'ợ': 'o',
        'ù': 'u', 'ú': 'u', 'ủ': 'u', 'ũ': 'u', 'ụ': 'u', 'ư': 'u', 'ừ': 'u', 'ứ': 'u', 'ử': 'u', 'ữ': 'u', 'ự': 'u',
        'ỳ': 'y', 'ý': 'y', 'ỷ': 'y', 'ỹ': 'y', 'ỵ': 'y',
        'đ': 'd',
        'À': 'A', 'Á': 'A', 'Ả': 'A', 'Ã': 'A', 'Ạ': 'A', 'Ă': 'A', 'Ằ': 'A', 'Ắ': 'A', 'Ẳ': 'A', 'Ẵ': 'A', 'Ặ': 'A',
        'Â': 'A', 'Ầ': 'A', 'Ấ': 'A', 'Ẩ': 'A', 'Ẫ': 'A', 'Ậ': 'A',
        'È': 'E', 'É': 'E', 'Ẻ': 'E', 'Ẽ': 'E', 'Ẹ': 'E', 'Ê': 'E', 'Ề': 'E', 'Ế': 'E', 'Ể': 'E', 'Ễ': 'E', 'Ệ': 'E',
        'Ì': 'I', 'Í': 'I', 'Ỉ': 'I', 'Ĩ': 'I', 'Ị': 'I',
        'Ò': 'O', 'Ó': 'O', 'Ỏ': 'O', 'Õ': 'O', 'Ọ': 'O', 'Ô': 'O', 'Ồ': 'O', 'Ố': 'O', 'Ổ': 'O', 'Ỗ': 'O', 'Ộ': 'O',
        'Ơ': 'O', 'Ờ': 'O', 'Ớ': 'O', 'Ở': 'O', 'Ỡ': 'O', 'Ợ': 'O',
        'Ù': 'U', 'Ú': 'U', 'Ủ': 'U', 'Ũ': 'U', 'Ụ': 'U', 'Ư': 'U', 'Ừ': 'U', 'Ứ': 'U', 'Ử': 'U', 'Ữ': 'U', 'Ự': 'U',
        'Ỳ': 'Y', 'Ý': 'Y', 'Ỷ': 'Y', 'Ỹ': 'Y', 'Ỵ': 'Y',
        'Đ': 'D'
    };

    return text
        .split('')
        .map(char => map[char] || char)
        .join('')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-+|-+$/g, '');
}

/**
 * Open a share popup window centered on the screen
 * @param {string} url - The URL to open
 * @param {number} width - Window width
 * @param {number} height - Window height
 * @returns {boolean} false to prevent default link behavior
 */
function open_share_popup(url, width, height) {
    const left = (screen.width / 2) - (width / 2);
    const top = (screen.height / 2) - (height / 2);
    window.open(url, 'share-popup', 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top + ',scrollbars,resizable');
    return false;
}
