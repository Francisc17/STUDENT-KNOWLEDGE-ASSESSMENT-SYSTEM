<?php

class User
{
    private $nome;
    private $email;
    private $password;
    private $tipo;
    private $foto;
    private $valido;


    /**
     * User constructor.
     * @param $nome nome do utilizador
     * @param $email email do utilizador
     * @param $password password do utilizador
     * @param $tipo tipo do utilizador
     * @param $foto foto do utilizaodr
     * @param $valido valido indica se o utilizador pode ou n aceder à plataforma
     */
    public function __construct ($nome, $email, $password, $tipo, $foto, $valido) {
        $this->nome = $nome;
        $this->email = $email;
        $this->password = $password;
        $this->foto = $foto;
        $this->tipo = $tipo;
        $this->valido = $valido;
    }

    /**
     * @return mixed obter nome
     */
    public function getNome()
    {
        return $this->nome;
    }

}