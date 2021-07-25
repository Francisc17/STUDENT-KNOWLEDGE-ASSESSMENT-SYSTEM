<?php


class Pergunta
{
    private $id;
    private $texto;
    private $cotacao;
    private $dificuldade;
    private $id_topico;

    /**
     * Pergunta constructor.
     * @param $id id da pergunta
     * @param $texto texto da pergunta (corresponde à pergunta em si)
     * @param $cotacao cotacao da pergunta
     * @param $dificuldade dificuldade de 1 a 3 de uma pergunta
     * @param $id_topico id_topico que correponde a um FK na bd do id de um topico
     */
    public function __construct($id, $texto, $cotacao, $dificuldade, $id_topico)
    {
        $this->id = $id;
        $this->texto = $texto;
        $this->cotacao = $cotacao;
        $this->dificuldade = $dificuldade;
        $this->id_topico = $id_topico;
    }

    /**
     * @return mixed obter id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed obter texto da pergunta
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * @return mixed obter cotacao
     */
    public function getCotacao()
    {
        return $this->cotacao;
    }

    /**
     * @return mixed obter dificuldade
     */
    public function getDificuldade()
    {
        return $this->dificuldade;
    }

    /**
     * @return mixed obter id do topico, fk para o id do topico
     */
    public function getIdTopico()
    {
        return $this->id_topico;
    }

}