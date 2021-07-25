function validarEmail(emailField){
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

    if (reg.test(emailField) == false)
    {
        return false;
    }
    return true;
}


function pedido_ajax_email(el) {

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {

            if (!validarEmail(el.value) || this.response == 0){
                el.classList.remove("is-valid");
                el.classList.add("is-invalid");
                document.getElementById("valid-email").style.display = "none";
                document.getElementById("invalid-email").style.display = "block";
                return false;
            }
            else if(this.response == 1){
                el.classList.remove("is-invalid");
                el.classList.add("is-valid");
                document.getElementById("invalid-email").style.display = "none";
                document.getElementById("valid-email").style.display = "block";
                return true;
            }
        }
    };
    xmlhttp.open("GET", "includes/Assistant/verificar_email.php?email="+ el.value, true);
    xmlhttp.send();
}