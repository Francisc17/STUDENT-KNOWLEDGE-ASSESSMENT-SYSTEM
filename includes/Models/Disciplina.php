<?php


class disciplina
{
    private $id;
    private $nome;
    private $descricao;

    /**
     * disciplina constructor.
     * @param $id id da disciplina
     * @param $nome nome da disciplina
     * @param $descricao descricao da disciplina
     */
    public function __construct($id, $nome, $descricao)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    /**
     * @return mixed obter id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed obter nome
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @return mixed obter descricao
     */
    public function getDescricao()
    {
        return $this->descricao;
    }


    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }


}