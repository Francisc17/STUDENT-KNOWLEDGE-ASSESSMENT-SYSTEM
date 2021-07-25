<?php


class Topico
{
    private $id;
    private $nome;
    private $id_disciplina;

    /**
     * Topico constructor.
     * @param $id id do topico
     * @param $nome nome do topico
     * @param $id_disciplina id da disciplina, FK na bd para a disciplina associada
     */
    public function __construct($id, $nome, $id_disciplina)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->id_disciplina = $id_disciplina;
    }

    /**
     * @return mixed id do topico
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed id do nome
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @return mixed id da disciplina
     */
    public function getIdDisciplina()
    {
        return $this->id_disciplina;
    }

}