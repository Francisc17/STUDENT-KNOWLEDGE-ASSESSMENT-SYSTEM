function pedido_ajax_resposta(id_resposta,id_teste_topicos_pergunta) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == 1){
            }

            if (this.responseText == 2){

            }

        }
    };
    xmlhttp.open("GET", "includes/Controllers/forms/add_students_components.php?id_ttp="
                                    +id_teste_topicos_pergunta+"&resp="+id_resposta, true);
    xmlhttp.send();
}

function terminar_teste_ajax(array_id,id_teste,id_aluno) {
array_id.forEach(function (entry) {

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == 1){
            }

            if (this.responseText == 2){

            }

        }
    };
    xmlhttp.open("GET", "includes/Controllers/forms/add_students_components.php?id_ttp="
        +entry+"&final=true", true);
    xmlhttp.send();

})

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == 1){
            }

            if (this.responseText == 2){

            }

        }
    };
    xmlhttp.open("GET", "includes/Controllers/forms/add_students_components.php?teste="
        +id_teste+"&id_aluno="+id_aluno, true);
    xmlhttp.send();


    window.location.href = "http://localhost/projeto/inicial_alunos.php";

}