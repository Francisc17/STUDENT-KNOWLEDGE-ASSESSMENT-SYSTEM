function clicar_login(){
    window.location.href = "login.php";
}

function clicar_registo() {
    window.location.href = "registo.php";
}

function validar_password() {
    var password = document.getElementById("RepeatPassword").value
    var original_password = document.getElementById("InputPassword").value;

    if (password === original_password){
        return true;
    }
    return false;
}

function validar_email(){
    if($("#valid-email").css('display') == 'block') {
        return true;
    }
    return false;
}

if (document.getElementById('form-registo') != null){
    document.getElementById('form-registo').onsubmit = () => {

        var erro_content = document.getElementById('error-msg-registo');

        if (!validar_email()){
            erro_content.textContent = "email invalido!";
            erro_content.style.display = "block";
            return false;

        }

        else if (!validar_password()){
            erro_content.textContent = "passwords nao correspondem!";
            erro_content.style.display = "block";
            return false;
        }

        return true;
    }
}


function upload_image(el) {
    var file_upload = document.getElementById('file-upload');
    file_upload.click();

    file_upload.onchange = function () {
            const reader = new FileReader();

            reader.onload = function (e) {
                el.src = e.target.result;
            }

            reader.readAsDataURL(file_upload.files[0]);
    };
}

function respostas_iniciais() {

    var alerta= document.getElementById('alerta');
    var checkbox = document.getElementById('correta').checked;


    if (checkbox){
        return true;
    }

    alerta.innerHTML = "Esta resposta deve ser a correta!";

    return false;
}



window.onload = function () {
    if (document.getElementById('duracao') && document.querySelector('#relogio')){
        var minutes = document.getElementById('duracao').value;
        var display = document.querySelector('#relogio');
        duration_arr = minutes.split(":");
        duration = ((duration_arr[0]*60)*60)+duration_arr[1]*60;
        startTimer(minutes,display);
    }
};

function startTimer(duracao,display) {

    var timer = duration, minutes, seconds;
    var ok = false;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent ="tempo restante: "+ minutes + ":" + seconds;

        if (--timer < 120 && !ok){
            alert("Faltam 2 minutos para o exame terminar");
            ok = true;
        }

        if (--timer < 0) {
            document.getElementById("terminar").click(); // Click on the checkbox
        }
    }, 2000);
}