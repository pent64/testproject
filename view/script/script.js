$('.register-link').click(function(){
    $('.register-form').show();
    $('.login-form').hide();
    $('.login-link').show();
    $('.login-links').show();
    $('.register-link').hide();
    $('.register-links').hide();
});
$('.login-link').click(function(){
    $('.register-form').hide();
    $('.login-form').show();
    $('.login-link').hide();
    $('.login-links').hide();
    $('.register-link').show();
    $('.register-links').show();
});
$(document).on('click','button.edit-task',function() {
    var task = $(this).closest('.task');
    $.ajax({
        url: '/Tasks/AddForm',
        type: 'POST',
        data: {'id':$(task).data('id')},
        success: function (response) {
            $(task).find('.form').html(response);
            $('.date input').datetimepicker({
                minDate: 0,
            });
        },
        error: function () {
            console.log('error');
        }
    });
});
$(document).on('click','button.delete-task',function() {
    var task = $(this).closest('.task');
    $.ajax({
        url: '/Tasks/Delete',
        type: 'POST',
        data: {'id':$(task).data('id')},
        success: function (response) {
            $('.container').replaceWith(response);
        },
        error: function () {
            console.log('error');
        }
    });
});
$(document).on('click','button.tag-task',function() {
    var task = $(this).closest('.task');
    var tagged = 1;
    if ($(task).hasClass('tagged')) tagged = 0;
    $.ajax({
        url: '/Tasks/Tag',
        type: 'POST',
        data: {'id':$(task).data('id'),'tagged':tagged},
        success: function (response) {
            $('.container').replaceWith(response);
        },
        error: function () {
            console.log('error');
        }
    });
});
$(document).on('click','button.add-task',function() {
    $.ajax({
        url: '/Tasks/AddForm',
        type: 'POST',
        success: function (response) {
            $('div.add-task').html(response);
            $('div.add-task').show();
            $('.date input').datetimepicker({
                minDate: 0,
            });
            $('button.add-task').hide();
        },
        error: function () {
            console.log('error');
        }
    });
});
$(document).on('click','button.add-subtask',function() {
    $(this).addClass('btn-success');
    var form = $(this).closest('.task').children('.form');
    $.ajax({
        url: '/Tasks/AddForm',
        type: 'POST',
        data: {'parent':$(this).closest('.task').data('id')},
        success: function (response) {
            $(form).html(response);
            $('.date input').datetimepicker({
                minDate: 0,
            });
        },
        error: function () {
            console.log('error');
        }
    });
});
$(document).on('click','button.save-task',function(){
    $('.error').remove();
    var form = $(this).closest('form');
    $.ajax({
        url: '/Tasks/Edit',
        type: 'POST',
        data: {'id':$(form).find('[name="id"]').val(),'parent':$(form).find('[name="parent_id"]').val(),'heading':$(form).find('[name="heading"]').val(),'content':$(form).find('[name="content"]').val(),'status':$(form).find('[name="status"]').val(), 'date':$(form).find('[name="date"]').val()},
        success: function (response) {
            try {
                var result = $.parseJSON(response);
                if (typeof result == 'object') {
                    $(form).prepend('<div class="error">' + result.error + '</div>')
                } else {
                    $('.container').replaceWith(response);
                }
            } catch (e){
                $('.container').replaceWith(response);
            }
        },
        error: function () {
            console.log('error');
        }
    });
});