<?php


class Teste
{
    private $nome;
    private $observacao;
    private $hora_inicio;
    private $data_inicio;
    private $duracao;
    private $id_disciplina;
    private $id;
    private $estado;
    private $hash;

    /**
     * Teste constructor.
     * @param $nome nome do teste
     * @param $observacao observacoes presentes no teste
     * @param $hora_inicio hora de inicio do teste em (hh:mm:ss)
     * @param $data_inicio data de inicio do teste em (aaaa/mm/dd)
     * @param $duracao duracao do teste em (hh:mm:ss)
     * @param $id_disciplina id da disciplina, FK na bd para id da disciplina associada
     * @param $id id do teste
     * @param $hash hash que corresponde à chave usada pelos alunos para terem acesso ao teste
     */
    public function __construct($nome, $observacao, $hora_inicio, $data_inicio, $duracao, $id_disciplina, $id, $hash)
    {
        $this->nome = $nome;
        $this->observacao = $observacao;
        $this->hora_inicio = $hora_inicio;
        $this->data_inicio = $data_inicio;
        $this->duracao = $duracao;
        $this->id_disciplina = $id_disciplina;
        $this->id = $id;
        $this->hash = $hash;
    }

    /**
     * @return mixed obter nome
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @return mixed obter observacoes
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @return mixed obter hora de inicio
     */
    public function getHoraInicio()
    {
        return $this->hora_inicio;
    }

    /**
     * @return mixed obter data de inicio
     */
    public function getDataInicio()
    {
        return $this->data_inicio;
    }


    /**
     * @return mixed obter duracao
     */
    public function getDuracao()
    {
        return $this->duracao;
    }


    /**
     * @return mixed obter id da disciplina
     */
    public function getIdDisciplina()
    {
        return $this->id_disciplina;
    }


    /**
     * @return mixed obter id do teste
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed obter chave associada ao teste
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return mixed obter estado do teste - stado 1 (passado)
     *estado 2 (a decorrer)
     *estado 3 (futuro)
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado definir estado do teste
     */
    public function setEstado($estado): void
    {
        $this->estado = $estado;
    }




}