<?php

include_once 'Bd.php';
require_once 'mailer.class.php';

class UserDAO
{
    /**
     * @var PDO parametro usado para fazer ligação à BD
     */
    private $conn;

    /**
     * UserDAO constructor que inicia comuncação com a base de dados (Singleton).
     */
    public function __construct()
    {
        $this->conn = bd::getInstance()->getConn();  //obter instancia da base de dados e a ligação
    }

    /**
     * Funcao para criar um novo utilizador aquando do registo enviando os dados deste para a base dados e enviando
     * um mail de confirmação de conta para o email
     * @param $name nome do utilizador
     * @param $email email do utilizador
     * @param $password password para entrar no sistema
     * @param $tipo tipo de uitlizador (professor ou aluno)
     * @param $foto foto escolhida pelo utilizador
     * @param $valido valor que indica se a conta é ou não válida
     */
    public function newUser($name, $email, $password, $tipo, $foto, $valido){

        $encrypted_pass = password_hash($password,PASSWORD_DEFAULT); //encriptar a password
        $token = bin2hex(openssl_random_pseudo_bytes(100));


        $stmt = $this->conn->prepare("INSERT INTO utilizadores(nome, email, password, tipo, foto, valido, token) 
                                               VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$name, $email,$encrypted_pass,$tipo,$foto,$valido,$token]);


        $carteiro = new mailer();
        $carteiro->mail("$email", "Validar conta da escola remota!", "
        <h1>Valide o seu email:</h1> <br> <a href=http://localhost/projeto/includes/Assistant/validar_email.php?email=$email&token=$token>
            http://localhost/projeto/includes/Assistant/validar_email.php?email=$email&token=$token
        </a>");

        $stmt = null;
    }

    /**
     * Obter utilizador da BD quando for fazer o login
     * @param $email email colocado no login.
     * @param $password password associada ao email colocada no login
     * @return bool|int return true se tudo correr bem ou entao retornar um inteiro com o codigo de erro correspondente
     */
    public function getUser($email, $password){

        $stmt = $this->conn->prepare("SELECT * FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $user_db = $stmt->fetch();

        if($user_db != false){

            $password_bd = $user_db['password'];
            if (!password_verify($password,$password_bd)) {
                return 2;  //password incorreta
            }

            if ($user_db['valido'] == 0) {
                return 3;  //utilizador ainda n validou a conta
            }

           $_SESSION['nome']=$user_db['nome'];
           $_SESSION['email']=$user_db['email'];

           if ($user_db['tipo']){
               $_SESSION['tipo'] = "Aluno";
           }else
               $_SESSION['tipo'] = "Professor";

           $_SESSION['foto'] = $user_db['foto'];
           $_SESSION['id'] = $user_db['id'];

           return true;     // ação executada com sucesso!
        }

        return 1; // email não existe
    }

    /**
     * Obter um aluno através do seu id
     * @param $id_aluno id do aluno a obter da BD.
     * @return mixed|null retorna o aluno da base de dados ou então retorna null caso este n exista.
     */
    public function obter_aluno($id_aluno){
        $stmt = $this->conn->prepare("SELECT * FROM utilizadores WHERE id = ?");
        $stmt->execute([$id_aluno]);


        if ($result = $stmt->fetch())
            return $result;

        return null;
    }

    /**
     * Função responsável por validar o email de um utilizador.
     * @param $email email do utilizador.
     * @param $token token recebido no email de confirmação de conta.
     */
    public function validar_email($email, $token){

        $stmt = $this->conn->prepare("SELECT token from utilizadores WHERE email = ? AND NOW() <=  data_criado + INTERVAL 2 DAY; ");

        $stmt->execute([$email]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if($result = $stmt->fetch()){
            if ($result['token'] == $token){
                $stmt = $this->conn->prepare("UPDATE utilizadores set valido = true where email = ?");
                $stmt->execute([$email]);

                if (!$stmt)
                    echo "nao foi possivel validar a conta";
                else
                    echo "Conta validada com sucesso";
            }
        }else
            echo "nao foi possivel validar a conta";
    }


    /**
     * Função para inserir uma disciplina na BD.
     * @param $nome nome da disciplina
     * @param $descricao descrição da disciplina
     * @param $id_docente id_docente que é uma FK na BD para o id_docente.
     * @return string retorna o id do registo inserido através do uso da função lastInsertId().
     */
    public function inserir_disciplina($nome, $descricao, $id_docente){
        $stmt = $this->conn->prepare("INSERT INTO disciplinas(nome,descricao,id_docente) VALUES (?,?,?)");
        $stmt->execute([$nome,$descricao,$id_docente]);

            return $this->conn->lastInsertId();
    }

    /**
     * Função Inserir topicos na bd
     * @param $nome nome do topico
     * @param $id_disciplina disciplina a que pertence
     * @return string retorna o id do registo inserido através do uso da função lastInsertId().
     */
    public function inserir_topico($nome, $id_disciplina){
        $stmt = $this->conn->prepare("INSERT INTO topicos(nome,id_disciplina) VALUES (?,?)");
        $stmt->execute([$nome,$id_disciplina]);

            return $this->conn->lastInsertId();
    }

    /**
     * Função para inserir uma pergunta na bd.
     * @param $texto texto da pergunta
     * @param $cotacao cotacao da pergunta
     * @param $dificuldade dificuldade da pergunta de 1 a 3
     * @param $id_topico id_topico, FK na bd para o id do topico correspondente.
     * @return string retorna o id do registo inserido através do uso da função lastInsertId().
     */
    public function inserir_pergunta($texto, $cotacao, $dificuldade, $id_topico){
        $stmt = $this->conn->prepare("INSERT INTO perguntas(texto_pergunta,cotacao,dificuldade,id_topico)
                                                VALUES (?,?,?,?)");
        $stmt->execute([$texto,$cotacao,$dificuldade,$id_topico]);

            return $this->conn->lastInsertId();
    }

    /**
     * Funcao para inserir uma resposta
     * @param $texto texto da resposta
     * @param $correta indicacao sobre se a resposta esta correta ou n
     * @param $id_pergunta inteiro com indicacao do id da pergunta
     * @return string retorna o id do registo inserido através do uso da função lastInsertId().
     */
    public function inserir_resposta($texto, $correta, $id_pergunta){
        $stmt = $this->conn->prepare("INSERT INTO respostas(texto_resposta,correta,id_pergunta)
                                                VALUES (?,?,?)");
        $stmt->execute([$texto,$correta,$id_pergunta]);

            return $this->conn->lastInsertId();
    }

    /**
     * Inserir testes na BD.
     * @param $nome string com o nome do teste.
     * @param $observacoes string com as observacoes do teste.
     * @param $data_disponivel date a que o teste inicia
     * @param $hora_disponivel time a que o teste inicia
     * @param $duracao time com a duracao do teste
     * @param $id_disciplina inteiro que é uma FK na bd para o id da disciplina correspondente
     * @return string retorna o id do registo inserido através do uso da função lastInsertId().
     */
    public function inserir_testes($nome, $observacoes, $data_disponivel, $hora_disponivel, $duracao, $id_disciplina){
        $stmt = $this->conn->prepare("INSERT INTO testes(nome,observacoes,data_disponivel,hora_disponivel,duracao,
                                                                 id_disciplina,hash) VALUES (?,?,?,?,?,?,?)");

        try {
            $hash = bin2hex(random_bytes(50));
        } catch (Exception $e) {
        }

        $stmt->execute([$nome,$observacoes,$data_disponivel,$hora_disponivel,$duracao,$id_disciplina,$hash]);

            return $this->conn->lastInsertId();
    }

    /**
     * Inserir na tabela testes_topicos
     * @param $id_teste inteiro que é uma FK na bd para o id do teste correspondente
     * @param $id_topico inteiro que é uma FK na bd para o id do topico correspondente
     * @param $nr_perguntas inteiro com o numero de perguntas que determinado topico associado ao exame tem.
     * @return string retorna o id do registo inserido através do uso da função lastInsertId().
     */
    public function inserir_testes_topicos($id_teste, $id_topico, $nr_perguntas){
        $stmt = $this->conn->prepare("INSERT INTO teste_topicos(id_teste,id_topico,nr_perguntas)
                                                VALUES (?,?,?)");
        $stmt->execute([$id_teste,$id_topico,$nr_perguntas]);

        return $this->conn->lastInsertId();
    }

    /**
     * Inserir dados na tabela teste_alunos
     * @param $id_teste inteiro que é uma FK para o id do teste correspondente
     * @param $id_aluno inteiro que é uma FK para o id do aluno correspondente
     * @return string retorna o id do registo inserido através do uso da função lastInsertId().
     */
    public function inserir_teste_alunos($id_teste, $id_aluno){
        $stmt = $this->conn->prepare("INSERT INTO testes_alunos(id_aluno,id_teste,estado)
                                                VALUES (?,?,?)");
        $stmt->execute([$id_aluno,$id_teste,1]);

        return $this->conn->lastInsertId();
    }


    //Updates

    /**
     * Alterar password do utilizador.
     * @param $pass password em plaintext escolhida pelo utilizador.
     * @param $id id do utilizador
     * @return bool boolean que diz se foi bem alterada a pass
     */
    public function alterar_password($pass, $id){

        $encrypted_pass = password_hash($pass,PASSWORD_DEFAULT); //encriptar a password

        $stmt = $this->conn->prepare("UPDATE utilizadores SET password = ? where id = ?");
        $stmt->execute([$encrypted_pass,$id]);

        if (!$stmt)
            return false;

        return true;

    }

    /**
     * alterar o nome do utilizador
     * @param $nome string nome do utilizador
     * @param $id id do utilizador
     * @return bool boolean a indicar se foi ou n bem alterado o nome
     */
    public function alterar_nome($nome, $id){
        $stmt = $this->conn->prepare("UPDATE utilizadores SET nome = ? where id = ?");
        $stmt->execute([$nome,$id]);

        if (!$stmt)
            return false;

        $_SESSION['nome'] = $nome;
        return true;
    }


    /**
     * alterar foto do utilizador
     * @param $foto foto do utilizador
     * @param $id id do utilizador
     * @return bool boolean para confirmar a alteração
     */
    public function alterar_foto($foto, $id){
        $stmt = $this->conn->prepare("UPDATE utilizadores SET foto = ? where id = ?");
        $stmt->execute([$foto,$id]);

        if (!$stmt)
            return false;

        $_SESSION['foto'] = $foto;
        return true;
    }

    /**
     * Função para alterar um campo de uma determinada coluna da tabela testes
     * @param $atributo atributo a alterar
     * @param $coluna coluna a alterar
     * @param $id_teste id do teste onde desejamos fazer a alteracao, FK na bd para o teste associado
     * @return bool boolean a confirmar a alteração
     */
    public function alterar_campo_teste($atributo, $coluna, $id_teste){
        $stmt = $this->conn->prepare("UPDATE testes SET {$coluna} = ? where id = ?");
        $stmt->execute([$atributo,$id_teste]);
        if (!$stmt)
            return false;

        return true;
    }

    /**
     * alterar um atributo de uma coluna na tabela perguntas
     * @param $atributo atributo a alterar
     * @param $coluna coluna a alterar
     * @param $id_pergunta id da pergunta que vamos alterar
     * @return bool boolean a confirmar
     */
    public function alterar_campo_pergunta($atributo, $coluna, $id_pergunta){
        $stmt = $this->conn->prepare("UPDATE perguntas SET {$coluna} = ? where id = ?");
        $stmt->execute([$atributo,$id_pergunta]);
        if (!$stmt)
            return false;

        return true;
    }

    //get from database

    /**
     * obter disciplinas associadas a um aluno
     * @param $user_id id do aluno
     * @return null return null ou um array com os resultados caso existam
     */
    public function obter_disciplinas($user_id){
        $stmt = $this->conn->prepare("SELECT * FROM disciplinas WHERE id_docente = ?");
        $stmt->execute([$user_id]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;

        while($result = $stmt->fetch()){
            $array_result[$i] = $result;
            $i++;
        }

        if (isset($array_result))
            return $array_result;

        return null;
    }

    /**
     * Obter disciplina a partir do id
     * @param $disciplina_id id da disciplina
     * @return mixed|null retornar um array com os resultados ou null caso n existam
     */
    public function obter_disciplina($disciplina_id){
        $stmt = $this->conn->prepare("SELECT * FROM disciplinas WHERE id = ?");
        $stmt->execute([$disciplina_id]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($result = $stmt->fetch())
            return $result;

        return null;
    }

    /**
     * Obter o id da disciplina a partir do nome
     * @param $disciplina_nome nome da disciplina
     * @return mixed|null obter resultado em array ou null se n existir
     */
    public function obter_disciplina_id($disciplina_nome){
        $stmt = $this->conn->prepare("SELECT id FROM disciplinas WHERE nome = ?");
        $stmt->execute([$disciplina_nome]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($result = $stmt->fetch())
            return $result;

        return null;
    }

    /**
     * obter todos os topicos associados a uma disciplina
     * @param $id_disciplina id da disciplina
     * @return null retornar null caso n haja resultado ou um array associativo se houver resultados
     */
    public function obter_topicos($id_disciplina){
        $stmt = $this->conn->prepare("SELECT * FROM topicos WHERE id_disciplina = ?");
        $stmt->execute([$id_disciplina]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;

        while($result = $stmt->fetch()){
            $array_result[$i] = $result;
            $i++;
        }

        if (isset($array_result))
            return $array_result;

        return null;
    }

    /**
     * Função para obter topico a partir do id
     * @param $id_topico id do topico a obter
     * @return mixed|null resultado em array ou null caso n exista
     */
    public function obter_topico($id_topico){
        $stmt = $this->conn->prepare("SELECT * FROM topicos WHERE id = ?");
        $stmt->execute([$id_topico]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($result = $stmt->fetch())
            return $result;

        return null;
    }

    /**
     * Obter perguntas associadas a um topico
     * @param $id_topico id do topico
     * @return null null se n existir resultado ou se existir retorna um array associativo
     */
    public function obter_perguntas($id_topico){
        $stmt = $this->conn->prepare("SELECT * FROM perguntas WHERE id_topico = ?");
        $stmt->execute([$id_topico]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;

        while($result = $stmt->fetch()){
            $array_result[$i] = $result;
            $i++;
        }

        if (isset($array_result))
            return $array_result;

        return null;
    }

    /**
     * Obter pergunta atraves do seu id
     * @param $id_pergunta id da pergunta
     * @return mixed|null resultado em array ou null caso n exista
     */
    public function obter_pergunta($id_pergunta){
        $stmt = $this->conn->prepare("SELECT * FROM perguntas WHERE id = ?");
        $stmt->execute([$id_pergunta]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($result = $stmt->fetch())
            return $result;

        return null;
    }

    /**
     * obter respostas associadas a uma pergunta
     * @param $id_pergunta id da pergunta
     * @return null retorna null se a pergunta n tiver respostas, se tiver retorna um array associativo
     */
    public function obter_respostas($id_pergunta){
        $stmt = $this->conn->prepare("SELECT * FROM respostas WHERE id_pergunta = ?");
        $stmt->execute([$id_pergunta]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;

        while($result = $stmt->fetch()){
            $array_result[$i] = $result;
            $i++;
        }

        if (isset($array_result))
            return $array_result;

        return null;
    }


    /**
     * obter teste a partir do seu id
     * @param $id_teste id do teste
     * @return mixed|null retorna null se n existir um teste com o id dado, se existir retorna um array com o teste.
     */
    public function obter_teste($id_teste){
        $stmt = $this->conn->prepare("SELECT * FROM testes WHERE id = ?");
        $stmt->execute([$id_teste]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($result = $stmt->fetch())
            return $result;

        return null;
    }

    /**
     * obter testes associados a um docente
     * @param $id_docente id do docente
     * @return null retorna null se o docente n tiver testes associados ou se tiver retorna um array associativo com
     * os testes
     */
    public function obter_testes($id_docente){
        $stmt = $this->conn->prepare("SELECT * FROM `testes`
                                               WHERE id_disciplina IN (SELECT id 
                                               FROM disciplinas
                                               WHERE id_docente = ?)");
        $stmt->execute([$id_docente]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;

        while($result = $stmt->fetch()){
            $array_result[$i] = $result;
            $i++;
        }

        if (isset($array_result))
            return $array_result;

        return null;
    }

    /**
     * obter id do teste através da hash
     * @param $hash hash do teste
     * @return mixed|null retorna o teste associado à hash ou null caso este n exista
     */
    public function obter_id_teste_pela_hash($hash){
        $stmt = $this->conn->prepare("SELECT * FROM testes where hash = ?");
        $stmt->execute([$hash]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($result = $stmt->fetch())
            return $result;

        return null;
    }

    /**
     * obter todos os testes associados a um aluno
     * @param $id_aluno id do aluno
     * @return null retorna null caso o aluno n tenha nenhum teste associado a ele ou retorna um array associativos
     * com os testes caso estes existam
     */
    public function obter_testes_aluno($id_aluno){
        $stmt = $this->conn->prepare("SELECT * FROM `testes`
                                               WHERE id IN (SELECT id_teste 
                                                            FROM testes_alunos
                                                            WHERE id_aluno = ?)");
        $stmt->execute([$id_aluno]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;

        while($result = $stmt->fetch()){
            $array_result[$i] = $result;
            $i++;
        }

        if (isset($array_result))
            return $array_result;

        return null;
    }


    //count respostas

    /**
     * contar as respostas de uma pergunta
     * @param $id_pergunta id da pergunta
     * @return mixed devolve o numero de respostas da pergunta em array.
     */
    public function contar_respostas($id_pergunta){
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS 'Total' FROM respostas where id_pergunta = ?");
        $stmt->execute([$id_pergunta]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($result = $stmt->fetch())
            return $result;
    }


    /**
     * contar numero de perguntas de 1 topico
     * @param $id_topico id do topico
     * @return mixed devolve o numero de perguntas desse topico
     */
    public function contar_perguntas_topico($id_topico){
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS 'Total' FROM perguntas where id_topico = ?");
        $stmt->execute([$id_topico]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($result = $stmt->fetch())
            return $result;
    }

    /**
     * obter uma entrada da tabela testes_alunos através do id do aluno e do id do teste
     * @param $id_teste id do teste
     * @param $id_aluno id do aluno
     * @return bool boolean caso tenha sido obtido com sucesso um resultado
     */
    public function aluno_associado_teste($id_teste, $id_aluno){
        $stmt = $this->conn->prepare("SELECT * FROM testes_alunos WHERE id_teste = ? and id_aluno = ?");
        $stmt->execute([$id_teste,$id_aluno]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($result = $stmt->fetch())
            return false;

        return true;
    }

    /**
     * destruct
     */
    public function __destruct()
    {
        $this->conn = null;
    }

    //saber se pergunta já está associada a um teste

    /**
     * obter uma entrada da tabela teste_topicos_pergunta
     * @param $id_pergunta id da pergunta
     * @return bool boolean a confirmar se foi possivel obter resultados
     */
    public function pergunta_associada_testes($id_pergunta){
        $stmt = $this->conn->prepare("SELECT * FROM teste_topicos_pergunta WHERE id_pergunta = ?");
        $stmt->execute([$id_pergunta]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($result = $stmt->fetch())
            return false;

        return true;
    }

    //SELECT column FROM table
    //ORDER BY RAND()
    //Where topic = topic
    //LIMIT nr-por-topico

    /**
     * Selecionar x perguntas aleatorias de um determinado topico
     * @param $id_topico id do topico
     * @param $nr_perguntas x numero de perguntas a selecionar
     * @return null null se n for possivel fazer a seleção ou se for possivel retorna uma array com as perguntas
     */
    public function perguntas_aleatorias_topico($id_topico, $nr_perguntas){
        $stmt = $this->conn->prepare("SELECT * FROM perguntas where id_topico = ?
                                               ORDER BY rand()
                                               LIMIT  ".$nr_perguntas." ");
        $stmt->execute([$id_topico]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;

        while($result = $stmt->fetch()){
            $array_result[$i] = $result;
            $i++;
        }

        if (isset($array_result))
            return $array_result;

        return null;
    }

    /**
     * obter topicos do teste
     * @param $id_teste id do teste
     * @return null retornar null ou retornar o resultado numa array
     */
    public function obter_topicos_teste($id_teste){
        $stmt = $this->conn->prepare("SELECT * FROM teste_topicos where id_teste = ?");
        $stmt->execute([$id_teste]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;

        while($result = $stmt->fetch()){
            $array_result[$i] = $result;
            $i++;
        }

        if (isset($array_result))
            return $array_result;

        return null;
    }

    /**
     * obter id da pergunta em teste_topicos_pergunta
     * @param $id_aluno id do aluno
     * @param $id_teste id do teste
     * @return null retorna null ou caso exista resultado retorna o id_pergunta num array associativo.
     */
    public function obter_perguntas_teste_criado($id_aluno, $id_teste){
        $stmt = $this->conn->prepare("SELECT id_pergunta FROM `teste_topicos_pergunta`
                                    WHERE id_teste_topicos IN ( SELECT id FROM teste_topicos WHERE id_teste IN 
                                    ( SELECT id_teste FROM testes_alunos WHERE id_aluno = ? And id_teste = ?))");
        $stmt->execute([$id_aluno,$id_teste]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;

        while($result = $stmt->fetch()){
            $array_result[$i] = $result;
            $i++;
        }

        if (isset($array_result))
            return $array_result;

        return null;
    }

    /**
     * criar o teste quando o aluno inicia
     * @param $id_aluno id do aluno
     * @param $id_teste_topicos id do teste_topicos
     * @param $id_pergunta id da pergunta
     * @return string string com o id gerado.
     */
    public function criar_teste_associado_aluno($id_aluno, $id_teste_topicos, $id_pergunta){
        $stmt = $this->conn->prepare("INSERT INTO teste_topicos_pergunta(id_pergunta,id_teste_topicos,id_aluno)
                                    VALUES (?,?,?)");
        $stmt->execute([$id_pergunta,$id_teste_topicos,$id_aluno]);

        return $this->conn->lastInsertId();
    }

    /**
     * alterar o estado que um aluno está no teste
     * @param $estado estado que vai ser definido
     * @param $id_aluno id do aluno
     * @param $id_teste id do teste
     * @return bool boolean a confirmar a alteração
     */
    public function alterar_estado_teste($estado, $id_aluno, $id_teste){
        $stmt = $this->conn->prepare("UPDATE testes_alunos SET estado = ? where id_aluno = ? and id_teste = ?");
        $stmt->execute([$estado,$id_aluno,$id_teste]);

        if (!$stmt)
            return false;

        return true;
    }

    /**
     * obter o id de uma entrada na tabela teste_topicos_pergunta
     * @param $id_pergunta id da pergunta
     * @param $id_aluno id do aluno
     * @param $id_teste id do teste
     * @param $id_topico id do topico
     * @return mixed|null null caso n exista resultado ou se existir retorna um array com o id.
     */
    public function obter_teste_topicos_pergunta_id($id_pergunta, $id_aluno, $id_teste, $id_topico){
        $stmt = $this->conn->prepare("Select id from teste_topicos_pergunta where id_pergunta = ?
                                               AND id_aluno = ? AND id_teste_topicos = (SELECT id
                                                                                        FROM teste_topicos
                                                                                        where id_teste = ?
                                                                                        AND id_topico = ?)");
        $stmt->execute([$id_pergunta,$id_aluno,$id_teste,$id_topico]);

        if ($result = $stmt->fetch())
            return $result;

        return null;
    }

    /**
     * adicionar uma resposta temporaria
     * @param $id_resposta id da respsota
     * @param $id_ttp id da tabela teste_topicos_perguntas, uma FK
     * @return string string com o id gerado
     */
    public function adicionar_resposta_temporaria($id_resposta, $id_ttp){
        $stmt = $this->conn->prepare("INSERT INTO teste_topicos_perguntas_respostas
                                                (id_teste_topicos_pergunta,id_resposta,temporaria)
                                    VALUES (?,?,?) ON DUPLICATE key update
                                                    id_teste_topicos_pergunta = VALUES(id_teste_topicos_pergunta),
                                                    id_resposta = VALUES(id_resposta),
                                                    temporaria = VALUES(temporaria)");
        $stmt->execute([$id_ttp,$id_resposta,1]);

        return $this->conn->lastInsertId();
    }

    /**
     * função para terminar o teste, neste caso altera o campo temporaria para 0 (false)
     * ou seja a resposta fica definitiva
     * @param $id_teste_topicos_pergunta id da entrada na tabela
     * @return bool boolean a confirmar a alteracao
     */
    public function terminar_teste($id_teste_topicos_pergunta){
        $stmt = $this->conn->prepare("UPDATE teste_topicos_perguntas_respostas SET temporaria = 0
                                                WHERE id_teste_topicos_pergunta = ?");
        $stmt->execute([$id_teste_topicos_pergunta]);
        if (!$stmt)
            return false;

        return true;
    }

    /**
     * obter o estado de um teste
     * @param $id_aluno id do aluno
     * @param $id_teste id do teste
     * @return mixed|null array associativo com o resultado ou null caso o msm n exista.
     */
    public function obter_estado_teste($id_aluno, $id_teste){
        $stmt = $this->conn->prepare("SELECT estado FROM testes_alunos WHERE id_aluno = ? and id_teste = ?");
        $stmt->execute([$id_aluno,$id_teste]);

        if ($result = $stmt->fetch())
            return $result;

        return null;
    }


    /**
     * Obter id da tabela testes_alunos
     * @param $id_aluno id do aluno
     * @param $id_teste id do teste
     * @return mixed|null array associativo com o resultado ou null caso o msm n exista.
     */
    public function obter_id_testes_alunos($id_aluno, $id_teste){
        $stmt = $this->conn->prepare("SELECT id FROM testes_alunos WHERE id_aluno = ? and id_teste = ?");
        $stmt->execute([$id_aluno,$id_teste]);

        if ($result = $stmt->fetch())
            return $result;

        return null;
    }

    /**
     * obter estado dos alunos no teste
     * @param $id_teste id do teste
     * @return null array associativo com o resultado ou null caso o msm n exista.
     */
    public function obter_estado_testes_alunos($id_teste){
        $stmt = $this->conn->prepare("SELECT * FROM testes_alunos WHERE id_teste = ?");
        $stmt->execute([$id_teste]);
        $i = 0;

        while($result = $stmt->fetch()){
            $array_result[$i] = $result;
            $i++;
        }

        if (isset($array_result))
            return $array_result;

        return null;

    }

    /**
     * Inserir uma mensagem no chat
     * @param $id_testes_alunos id de testes_alunos
     * @param $msg mensagem a colocar
     * @param $is_teacher campo para saber se foi o aluno ou o professor a enviar a mensagem
     * @return string string com o id gerado
     */
    public function inserir_msg_chat($id_testes_alunos, $msg, $is_teacher){
        $stmt = $this->conn->prepare("INSERT INTO chat(id_testes_alunos,texto_msg,is_teacher)
                                               VALUES(?,?,?)");

        $stmt->execute([$id_testes_alunos,$msg,$is_teacher]);

        return $this->conn->lastInsertId();
    }

    public function obter_msg_chat($id){
        $stmt = $this->conn->prepare("SELECT * FROM chat where id = ?");
        $stmt->execute([$id]);

        if ($result = $stmt->fetch())
            return $result;

        return null;

    }

    /**
     * Obter mensagem com maior id
     * @return mixed|null array associativo com o resultado ou null caso o msm n exista.
     */
    public function obter_msg_maior_id($last_id){
      $stmt = $this->conn->prepare("SELECT * FROM chat WHERE id > ?");
        $stmt->execute([$last_id]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;

        while($result = $stmt->fetch()){
            $array_result[$i] = $result;
            $i++;
        }

        if (isset($array_result))
            return $array_result;

        return null;
    }
}