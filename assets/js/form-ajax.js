/*
 * JUZAWEB CMS 1.0 - Form Ajax support
 *
 *
 * Copyright JS Foundation and other contributors
 * Released under the MIT license
 *
 * Date: 2021-03-12T21:04Z
 */

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });

    $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
        if (jqxhr.status === 401) {
            window.location = "/admin-cp/login";
        }

        if (jqxhr.status === 419) {
            window.location = location.toString();
        }
    });

    function sendMessageByResponse(response, notify = true) {
        if (notify) {
            if (typeof show_notify !== 'undefined' && typeof show_notify === 'function') {
                show_notify(response);
            }
        } else {
            if (typeof show_message !== 'undefined' && typeof show_message === 'function') {
                show_message(response);
            }
        }
    }

    function sendRequestFormAjax(form, data, btnsubmit, currentText, currentIcon, captchaToken = null) {
        let submitSuccess = form.data('success');
        let notify = form.data('notify') || true;

        if (captchaToken) {
            data.append('g-recaptcha-response', captchaToken);
        }

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            dataType: 'json',
            data: data,
            cache: false,
            contentType: false,
            processData: false
        }).done(function(response) {
            sendMessageByResponse(response, notify);

            if (response.redirect) {
                setTimeout(function () {
                    window.location = response.redirect;
                }, 1000);
                return false;
            }

            btnsubmit.find('i').attr('class', currentIcon);
            btnsubmit.prop("disabled", false);

            if (btnsubmit.data('loading-text')) {
                btnsubmit.html(currentText);
            }

            if (response.success === false) {
                return false;
            }

            if (submitSuccess) {
                eval(submitSuccess)(form, response);
            }

            return false;
        }).fail(function(response) {
            btnsubmit.find('i').attr('class', currentIcon);
            btnsubmit.prop("disabled", false);

            if (btnsubmit.data('loading-text')) {
                btnsubmit.html(currentText);
            }

            sendMessageByResponse(response, notify);
            return false;
        });
    }

    $(document).on('submit', '.form-ajax', function(event) {
        if (event.isDefaultPrevented()) {
            return false;
        }

        event.preventDefault();

        let form = $(this);
        let formData = new FormData(form[0]);
        let btnsubmit = form.find("button[type=submit]");
        let currentText = btnsubmit.html();
        let currentIcon = btnsubmit.find('i').attr('class');

        btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
        btnsubmit.prop("disabled", true);

        if (btnsubmit.data('loading-text')) {
            btnsubmit.html('<i class="fa fa-spinner fa-spin"></i> ' + btnsubmit.data('loading-text'));
        }

        if (typeof grecaptcha !== 'undefined') {
            loadRecapchaAndSubmit(
                function (token) {
                    sendRequestFormAjax(
                        form,
                        formData,
                        btnsubmit,
                        currentText,
                        currentIcon,
                        token
                    );
                }
            );
            return false;
        }

        sendRequestFormAjax(
            form,
            formData,
            btnsubmit,
            currentText,
            currentIcon
        );
    });

    $(document).on('keypress', '.is-number', function () {
        return validate_isNumberKey(this);
    });

    $(document).on('keyup', '.number-format', function () {
        return validate_FormatNumber(this);
    });

    function validate_isNumberKey(evt) {
        let charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode == 59 || charCode == 46)
            return true;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function validate_FormatNumber(a) {
        a.value = a.value.replace(/\,/gi, "");
        a.value = a.value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }
});
