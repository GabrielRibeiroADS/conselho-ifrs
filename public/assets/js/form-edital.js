$(document).ready(function () {
    $("#preparar-impressao").click(function () {
        $(".is-invalid").removeClass("is-invalid");
        return true;
    })
});

var setMasks = function () {
    $(".little-number").mask("0000");
    $(".number").mask("000000000000");
    $(".money").mask("000.000.000.000,00", {reverse: true});
    $(".cpf").mask("000.000.000-00", {reverse: true});
    $(".cep").mask("00000-000");
}

var showOnSelect = function (select_id, select_value, activated_element_id) {
    var compareValue = (select_value instanceof Array) ? 
        function (v) {return select_value.includes(v)} :
        function (v) {return v == select_value}; 

    $(select_id).change(function () {
        if (compareValue($(this).val())) {
            $(activated_element_id).show();
        } else {
            $(activated_element_id).hide();
        }
    });

    $(select_id).trigger('change');
}

var showOnCheck = function (activator_checkbox_id, activated_element_id) {
    $(activator_checkbox_id).change(function () {
        if ($(this).is(":checked")) {
            $(activated_element_id).show();
        } else {
            $(activated_element_id).hide();
        }
    });

    $(activator_checkbox_id).trigger('change');
}
var showOnCheckRadio = function (activator_id, activator_name, activated_element_id) {
    var radios = $('[name='+ activator_name + ']')

    $(radios).off('click').click(function () {
        $(radios).toArray().forEach(function (e) {
            $(e).trigger('change');
        });
    })

    $(activator_id).change(function (){
        if ($(this).is(":checked")) {
            $(activated_element_id).show();
        } else {
            $(activated_element_id).hide();
        }
    });

    $(radios).trigger('change');
}
