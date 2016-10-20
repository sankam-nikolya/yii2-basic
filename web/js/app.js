+function ($) {
    'use strict';

    $(document).on('click', '.showModalButton', function(e){
        e.preventDefault();
        var is_new = $(this).data('new');
        var new_name = $(this).data('new-name');
        var value = $(this).attr('value');

        if(is_new != undefined && is_new != 0 && new_name != undefined && new_name != 0) {
            $(is_new + ' ' + new_name).val(1);
        }

        if(value == undefined) {
            value = $(this).attr('href');
        }

        if ($('#modal-form').data('bs.modal').isShown) {
            $('#modal-form').find('#modalContent')
                    .load(value, function(){
                        $('#modal-form  #modalContent').removeClass('preloader');
                        $('#modal-form  #modalContent form').attr('data-new', is_new);
                    });
            document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('data-title') + '</h4>';
        } else {
            $('#modal-form').modal('show')
                    .find('#modalContent')
                    .load(value, function(){
                        $('#modal-form  #modalContent').removeClass('preloader');
                        $('#modal-form  #modalContent form').attr('data-new', is_new);
                    });
            document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('data-title') + '</h4>';
        }
    });

    $(document).on('click', '#modal-form button[type=submit]', function (e) {
        e.preventDefault();

        var form = $('#modal-form').find('form');
        var on_close = form.data('update-on-close');
        var request = form.data('not-request');

        if(request == undefined) {
            $('#modal-form form').submit();
        } else {
            var input = form.data('input');
            var result = form.data('result');
            var html_input = form.data('html-input');
            var html_val = form.data('html-val');

            if(input != undefined && result != undefined) {
                var data = $(input).val();
                $(result).val(data);

                if(html_input != undefined && html_val != undefined) {
                    var data = $(input).parent().parent().find(html_input).html();
                    $(result).parent().parent().find(html_val).html(data);
                }
            }

            $('#modal-form').modal('hide');

            if(on_close != undefined) {
                $.pjax.reload({container: on_close});
            }
        }

        return false;
    });
    $(document).on('click', '#modal-form button[type=button]', function () {
        var form = $('#modal-form').find('form');
        var on_close = form.data('update-on-close');

        if(form != undefined){
            if(on_close != undefined) {
                $.pjax.reload({container: on_close});
            }
        }

        $('#modal-form').modal('hide');
        return false;
    });
    $(document).on('beforeSubmit', '#modal-form #modalContent form', function () {
        var form = $(this);
        var close = parseInt($(this).data('modal-close'));
        var update = form.attr('update');
        var on_close = form.data('update-on-close');

        var request = form.data('not-request');

        if(request == undefined) {
            request = false;
        } else {
            request = true;
        }

        if (form.find('.has-error').length) {
            return false;
        }

        $.ajax({
            url    : form.attr('action'),
            method : form.attr('method'),
            data   : form.serialize(),
            success: function (response)
            {
                if(close == 1) {
                    $('#modal-form .modal-footer button[type=submit]').addClass('hidden');
                    var text = $('#modal-form .modal-footer button[type=button]').data('success-text');
                    $('#modal-form .modal-footer button[type=button]').html(text);
                }

                $('#modal-form #modalContent').html(response);

                if(update != undefined) {
                    $.pjax.reload({container: update});
                    //$.pjax.reload(update);
                }

                if(on_close != undefined) {
                    $.pjax.reload({container: on_close});
                }
            },
            error  : function ()
            {
                console.log('internal server error');
            }
        });

        return false;
    });
    $('#modal-form').on('hidden.bs.modal', function () {
        $('#modal-form #modalContent').addClass('preloader');
        $('#modal-form #modalContent').html('');
        $('#modal-form button[type=buttom]').html(
            $('#modal-form button[type=buttom]').data('default-text')
        );
        $('#modal-form button[type=submit]').removeClass('hidden');
    });
    $(document).on("afterValidate", "form", function (event, messages) {
        $("a[data-toggle=tab]").removeClass("has-error");
        if (!$.isEmptyObject(messages)) {
            for (var i in messages) {
                var pane = $("#" + i).closest(".tab-pane");
                var parent = $("#" + i).parent();
                if(parent.hasClass("has-error")) {
                    console.log(i + " - has error");
                    $('a[href="#' + pane.attr("id") + '"]').addClass("has-error");
                }
            }
        }
    });
}(jQuery);
