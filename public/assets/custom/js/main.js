$('#companyForm').on('submit', function (e) {
    e.preventDefault();
    validateForm();
});

function validateForm() {
    var customerType = document.getElementById("company_form_customerType").value;
    var debtLimit = document.getElementById("company_form_debtLimit").value;
    if(customerType === 'seller' && debtLimit > 0){
        invalidDebtLimit();
        document.getElementById("debt-limit-error").innerHTML = "You can not set debt limit to creditor type!";
        $('#debt-limit-error').show(0).delay(4000).hide(0);
        document.getElementById("company_form_debtLimit").value = 0;
        return false;
    } else if(customerType === 'seller' && debtLimit === 0) {
        $('#companyForm')[0].submit();
    }
    else if(customerType === 'buyer')
        $('#companyForm')[0].submit();
    }

function invalidDebtLimit()
{
    $('#debtLimitContainer').addClass('has-error');
}

setTimeout(function() {
    $('#flash').fadeOut('fast');
}, 5000);