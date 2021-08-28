// time validation

jQuery.validator.addMethod("greaterThan",
function(value, element, params) {

    if (!/Invalid|NaN/.test(new Date(value))) {
        return new Date(value) > new Date($(params).val());
    }

    return isNaN(value) && isNaN($(params).val())
        || (Number(value) > Number($(params).val()));
},"დაწყების თარიღი მეტია დასრულების თარიღზე");


// Auth validate
$("#auth-form").validate({
    submitHandler: function(form) {
        form.submit();
    },
    onfocusout: function(element) {
        var $errorContainer = $(".reg-input").siblings(".Ntooltip").find("label");
        $errorContainer.removeClass("checked");
        if ( !this.checkable(element)) {
            this.element(element);
        }
    },
    rules: {
        errorClass:'error',
        idnum: {
            required: true,
            digits:true,
            minlength:11,
            maxlength:11
        }
    },
    messages: {
        idnum: {
            required: 'გთხოვთ შეავსოთ ველი',
            digits: 'გთხოვთ შეიყვანოთ ციფრები',
            minlength: 'გთხოვთ შეიყვანოთ სწორი ID, თქვენს მიერ შეყვანილი ციფრი <em>ნაკლებია 11</em>',
            maxlength: 'გთხოვთ შეიყვანოთ სწორი ID, თქვენს მიერ შეყვანილი ციფრი <em>მეტია 11</em>'
        }
    },
    errorPlacement: function(error, element) {
        var container = $('<div />');
        container.addClass('Ntooltip');
        error.insertAfter(element);
        error.wrap(container);
        $("<div class='errorImage'></div>").insertAfter(error);
      },
      success: function(element) {
        $(element).addClass("checked");
    }
});



// Form validate

$("form").validate({
    submitHandler: function(form) {
        form.submit();
    },
    onfocusout: function(element) {
        var $errorContainer = $(".reg-input").siblings(".Ntooltip").find("label");
        $errorContainer.removeClass("checked");
        if ( !this.checkable(element)) {
            this.element(element);
        }
    },
    rules: {
        errorClass:'error',
        name: {
            required: true
        },
        username: {
            required: true
        },
        last_name: {
            required: true
        },
        phone_number: {
            required: true,
            minlength: 11,
            maxlength:11
        },
        email: {
            required: true,
            email: true
        },
        mobnumber: {
            required: true,
            minlength: 9,
            maxlength: 12
        },
        start_day: {
            required: true
        },
        end_day: {
            required: true,
            greaterThan: "#start_day"
        }
    },
    messages: {
        name: {
            required: 'გთხოვთ ჩაწეროთ ტესტის სახელი'
        },
        username: {
            required: 'გთხოვთ შეავსოთ ველი'
        },
        last_name: {
            required: 'გთხოვთ შეავსოთ ველი'
        },
        phone_number: {
            required: 'გთხოვთ შეავსოთ ველი',
            minlength: 'გთოვთ სწორად მიუთითოთ ნომერი',
            maxlength: 'გთოვთ სწორად მიუთითოთ ნომერი'
        },
        email: {
            required: 'გთხოვთ შეავსოთ ველი',
            email: 'გთხოვთ ჩაწეროთ სწორი ელ.ფოსტის მისამართი'
        },
        mobnumber: {
            required: 'გთხოვთ შეავსოთ ველი',
            required: 'გთხოვთ შეავსოთ ველი',
            minlength: 'გთოვთ სწორად მიუთითოთ ნომერი',
            maxlength: 'გთოვთ სწორად მიუთითოთ ნომერი'
        },
        end_day: {
            required: 'გთხოვთ შეავსოთ ველი'
        },
        start_day: {
            required: 'გთხოვთ შეავსოთ ველი'
        }
    },
    errorPlacement: function(error, element) {
        var container = $('<div />');
        container.addClass('Ntooltip');
        error.insertAfter(element);
        error.wrap(container);
        $("<div class='errorImage'></div>").insertAfter(error);
      },
      success: function(element) {
        $(element).addClass("checked");
    }
});

$('.workexp-valid').each(function() {
    $(this).rules('add', {
        required: true,
        minlength: 5,
        maxlength: 500,
        messages: {
            required: 'გთხოვთ შეავსოთ ველი',
            minlength: "სიმბოლოების რაოდენობა {0} მინიმალურია",
            maxlength: "სიმბოლოების რაოდენობა {0} მახსიმალურია"
        }
    });
});


$('.add_work_experience').on('click', function() {
    $('.date-valid').each(function() {
        $(this).rules('add', {
            required: true,
            messages: {
                required: 'გთხოვთ შეავსოთ ველი'
            }
        });
    });
    $('#end_day').each(function() {
        $(this).rules('add', {
            greaterThan: "#start_day"
        });
    });
    $('.workexp-valid').each(function() {
        $(this).rules('add', {
            required: true,
            minlength: 5,
            maxlength: 500,
            messages: {
                required: 'გთხოვთ შეავსოთ ველი',
                minlength: "სიმბოლოების რაოდენობა {0} მინიმალურია",
                maxlength: "სიმბოლოების რაოდენობა {0} მაქსიმალურია"
            }
        });
    });
})
