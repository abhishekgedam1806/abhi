var Login = function() {
    var handleLogin = function() {
        var $form = $('.login-form');
        if (!$form.length) return;

        $form.validate({
            errorElement: 'span',
            errorClass: 'admin-field-error',
            focusInvalid: false,
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: "Please enter your email address.",
                    email: "Please enter a valid email address."
                },
                password: {
                    required: "Please enter your password."
                }
            },
            invalidHandler: function(event, validator) {
                $('.login-js-alert').slideDown(200);
            },
            highlight: function(element) {
                $(element).closest('.admin-field-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.admin-field-group').removeClass('has-error');
            },
            errorPlacement: function(error, element) {
                error.appendTo(element.closest('.admin-field-group'));
            },
            submitHandler: function(form) {
                var $btn = $(form).find('button[type="submit"]');
                $btn.prop('disabled', true).addClass('loading');
                $btn.find('.btn-text').text('Signing In...');
                form.submit();
            }
        });

        $form.find('input').on('input keypress', function(e) {
            $('.login-js-alert').slideUp(200);
        });
    };

    return {
        init: function() {
            handleLogin();
        }
    };
}();

jQuery(document).ready(function() {
    Login.init();
});