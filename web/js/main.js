$('#contact_other').parents('.form-group').hide();

if ($("input[name='contact[knowledge]']:checked").val() == 'autre') {
    $('#contact_other').parents('.form-group').show();
} else {
    $('#contact_other').parents('.form-group').hide();
}

$("input[name='contact[knowledge]']").click(function () {
    if ($(this).val() == 'autre') {
        $('#contact_other').parents('.form-group').fadeIn();
    } else {
        $('#contact_other').parents('.form-group').fadeOut();
    }
});
