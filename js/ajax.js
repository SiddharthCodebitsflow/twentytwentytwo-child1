let currentPage = 1;
function get_data(maxPage, testimonialId_name) {
    currentPage++;
    if (currentPage > maxPage) {
        jQuery('#loadId').hide();
    } else {
        jQuery.ajax({
            type: 'POST',
            url: ajax_var.url,
            dataType: 'html',
            data: {
                action: 'weichie_load_more_btn',
                nonce: ajax_var.nonce,
                paged: currentPage,
                testimonialId_name: testimonialId_name,
            },
            success: function (res) {
                jQuery('.testimonial-listing').append(res);
            }
        });
    }
}
let i = 1;
let maxquestion = 1;
function add_more_field(questionId, questionName, answerId, answerName) {
    questionName = questionName.slice(0, -1);
    answerName = answerName.slice(0, -1);
    i++;
    questionId = questionId + '-' + i;
    questionName = questionName + i + ']';
    answerId = answerId + '-' + i;
    answerName = answerName + i + ']';
    maxquestion++;
    if (maxquestion <= 5) {
        jQuery.ajax({
            type: 'POST',
            url: ajax_var.url,
            dataType: 'html',
            data: {
                action: 'add_more',
                nonce: ajax_var.nonce,
                questionId: questionId,
                questionName: questionName,
                answerId: answerId,
                answerName: answerName
            },
            success: function (res) {
                jQuery('.widget-help').append(res);
            }
        });
    } else {
        jQuery('.btn-add').hide();
    }
}

jQuery(document).ready(function () {
    jQuery('.register').on('click', function () {
        var fst_name = jQuery('#fst_name').val();
        var lst_name = jQuery('#lst_name').val();
        var mobile_number = jQuery('#mobile_number').val();
        var email = jQuery('#email').val();
        var password = jQuery('#password').val();
        if (fst_name.length == 0) {
            jQuery('.fst_name_error').html("<p>* First Name can not Empty</p>");
        } else if (lst_name.length == 0) {
            jQuery('.lst_name_error').html("<p>* Last Name can not Empty</p>");
        } else if (mobile_number.length == 0) {
            jQuery('.mobile_error').html("<p>* Mobile number can not Empty</p>");
        }
        else if (email.length == 0) {
            jQuery('.email_error').html("<p>* Email can not Empty</p>");
        } else if (password.length == 0) {
            jQuery('.password_error').html("<p>* password can not Empty</p>");
        } else {
            jQuery.ajax({
                type: 'POST',
                url: ajax_var.url,
                dataType: 'html',
                data: {
                    action: 'register_form',
                    nonce: ajax_var.nonce,
                    name: fst_name,
                    email: email,
                    password: password
                },
                success: function (res) {
                    alert(res);
                }
            });
        }

    });
});







