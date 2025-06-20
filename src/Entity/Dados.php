<?php

namespace ORM\Doctrine\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Dados
{

    #[Id]
    #[GeneratedValue]
    #[Column]
    private int $id;

    #[Column(type: 'string', length: 255)]
    private ?string $nome;
    #[Column(type: 'string', length: 11)]
    private ?string $cpf;
    #[Column]
    private ?int $numPedido;




    /**
     * @param string|null $nome
     * @param string|null $cpf
     * @param int|null $numPedido
     */
    public function __construct(?string $nome, ?string $cpf, ?int $numPedido)
    {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->numPedido = $numPedido;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(?string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getNumPedido(): ?int
    {
        return $this->numPedido;
    }

    public function setNumPedido(?int $numPedido): void
    {
        $this->numPedido = $numPedido;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }






}