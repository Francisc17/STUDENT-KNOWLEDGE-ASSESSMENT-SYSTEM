var lastInsertedId = 0;


function enviar_mensagem_chat(id,teacher) {
    if (!teacher)
        var msg = document.getElementById('input-msg-chat').value;

    var xmlhttp = new XMLHttpRequest();
    if (this.readyState == 4 && this.status == 200) {
        lastInsertedId = this.response;
    }

    xmlhttp.open("GET", "includes/Controllers/forms/chat.php?id="
        +id+"&msg="+msg+"&teacher="+teacher, true);
    xmlhttp.send();
}



window.onload = function () {
    setInterval(function () {
        if (typeof lastInsertedId !== 'undefined' && lastInsertedId != null) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.response);
                    if (data != null) {
                        data.forEach(function(e) {

                            console.log(data.length);
                            $("#mensagens").append("<div class=\"container container-chat\">\n" +
                                "                <div class=\"row\">\n" +
                                "                    <div class=\"col-10\">\n" +
                                "                            <div class=\"card  text-white bg-primary mb-3 card-chat\" style=\"\">\n" +
                                "                                <div class=\"card-header\">Nome do aluno</div>\n" +
                                "                                <div class=\"card-body body-chat\">\n" +
                                "                                    <p class=\"card-text\">" + e['texto_msg'] + "</p>\n" +
                                "                            </div>\n" +
                                "                        </div>\n" +
                                "                    </div>\n" +
                                "                </div>\n" +
                                "            </div>\n");
                            lastInsertedId = e['id'];


                        });

                        }
                    }
                }
            };
            xmlhttp.open("GET", "includes/Controllers/forms/chat.php?last_id=" + lastInsertedId, true);
            xmlhttp.send();

    }, 1000);
}