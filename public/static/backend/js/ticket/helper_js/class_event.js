$('.check-number-int').change(function(e) {
    let $number = $(this).val();
    if ($number == null) {
        $(this).val(0);
    }
    $number = parseFloat($number);
    $number = positiveNumbers($number, ($number >= 0));
    $(this).val($number.toFixed(1));
});