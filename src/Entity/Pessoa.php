<?php

namespace ORM\Doctrine\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

use Doctrine\ORM\Mapping\OneToMany;
use Exception;

#[Entity]
class Pessoa
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    protected int $id;

    #[Column]
    protected string $nome;

    #[Column]
    protected string $cpf;

    #[OneToMany(targetEntity: Pedido::class, mappedBy: "pessoa", cascade: ["persist", "remove"])]
    protected Collection $pedidos;



    public function __construct(string $nome, string $cpf)
    {
        if ($nome != null || $cpf != null )
        {
            if ( $nome != ""  || $nome != " "){
                $this->nome = $nome;
            }
            else{
                echo ("\n--Campo nome é nulo ou inválido\n");
            }
            if ( $cpf != ""  && $cpf != " ")
            {
                if (strlen($cpf) == 11)
                {
                    $this->cpf = $cpf;
                }

                else
                {
                    echo ("\n--CPF maior ou menor que 11 digitos\n");
                }
            }
        }
        else
        {
            echo "\n--Campo nome e CPF são null ou inválidos\n";
        }
        $this->pedidos = new ArrayCollection();
    }


    public function getId(): int { return $this->id; }

    public function setId(int $id): void { $this->id = $id; }

    public function getNome(): string { return $this->nome; }

    public function setNome(string $nome): void
    {
        if($nome != null && $nome != "" && $nome != " ")
        {
            $this->nome = $nome;
        }
        else
        {
            echo ("\n--Campo nome é nulo ou inválido\n");
        }

    }

    public function getCpf(): string { return $this->cpf; }

    public function setCpf(string $cpf): void
    {
        if ($cpf != "" && $cpf != null && $cpf != " ")
        {
            if (strlen($cpf) == 11)
            {
                $this->cpf = $cpf;
            }
            else
            {
                echo "\n--CPF maior ou menor que 11 digitos\n";
            }
        }
        else
        {
            echo "\n--Campo CPF é nulo ou inválido\n";
        }
    }

    public function getPedidos(): iterable { return $this->pedidos; }

    public function addPedidos(Pedido $pedido): void
    {
        $this->pedidos->add($pedido);
        $pedido->setPessoa($this);
    }
}
