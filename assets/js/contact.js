(function ($) {
    "use strict";
    jQuery(document).ready(function ($) {

        var contact_form = $("#contact_page_form");

        if (contact_form.length) {
            contact_form.validate({
                rules: {
                    name:  'required',
                    phone: 'required',
                    email: { required: true, email: true },
                    msg:   'required'
                },
                messages: {
                    name:  'يرجى إدخال الاسم',
                    phone: 'يرجى إدخال رقم الهاتف',
                    email: {
                        required: 'يرجى إدخال البريد الإلكتروني',
                        email: 'يرجى إدخال بريد إلكتروني صحيح'
                    },
                    msg: 'يرجى كتابة رسالتك'
                },
                submitHandler: function (form) {
                    var $btn = $(form).find('button[type="submit"]');
                    $btn.prop('disabled', true);

                    $.ajax({
                        type: "POST",
                        url: 'contact.php',
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function (res) {
                            contact_form.find('.alert').remove();
                            var cls = (res.type === 'success') ? 'alert-success' : 'alert-danger';
                            contact_form.prepend('<div class="alert ' + cls + '">' + res.msg + '</div>');
                            if (res.type === 'success') {
                                form.reset();
                            }
                        },
                        error: function () {
                            contact_form.find('.alert').remove();
                            contact_form.prepend('<div class="alert alert-danger">تعذر إرسال الرسالة. يرجى المحاولة لاحقاً.</div>');
                        },
                        complete: function () {
                            $btn.prop('disabled', false);
                        }
                    });

                    return false;
                }
            });
        }

    });

}(jQuery));
