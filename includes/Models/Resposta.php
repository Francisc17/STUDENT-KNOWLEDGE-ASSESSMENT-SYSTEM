<?php


class Resposta
{
    private $texto;
    private $correta;
    private $id;
    private $id_pergunta;

    /**
     * Resposta constructor.
     * @param $texto texto da resposta
     * @param $correta indica se a resposta está correta ou n
     * @param $id id da resposta
     * @param $id_pergunta id da pergunta, FK na bd para o id da pergunta
     */
    public function __construct($texto, $correta, $id, $id_pergunta)
    {
        $this->texto = $texto;
        $this->correta = $correta;
        $this->id = $id;
        $this->id_pergunta = $id_pergunta;
    }

    /**
     * @return mixed obter texto
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * @return mixed saber se a resposta é ou não a correta
     */
    public function getCorreta()
    {
        return $this->correta;
    }

    /**
     * @return mixed obter o id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed obter o id da pergunta
     */
    public function getIdPergunta()
    {
        return $this->id_pergunta;
    }

}