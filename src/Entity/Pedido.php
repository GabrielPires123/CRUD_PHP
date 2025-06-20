<?php

namespace ORM\Doctrine\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Exception;

#[Entity]
class Pedido
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    protected int $id;

    #[Column]
    protected int $num;

    #[ManyToOne(targetEntity: Pessoa::class, inversedBy: "pedidos")]
    #[JoinColumn(name: "pessoa_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    protected ?Pessoa $pessoa = null;

    /**
     * @throws Exception
     */
    public function __construct(int $num)
    {
        if ($num != null && $num != "" && $num != " ")
        {
            if($num >0)
            {
                $this->num = $num;
            }
            else{
                echo "\n--Campo Num. Pedido deve ser maior que 0\n";
            }

        }
        else
        {
            echo "\n--Campo Num. Pedido é nulo ou inválido\n";
        }
    }

    public function getPessoa(): ?Pessoa { return $this->pessoa; }
    public function setPessoa(Pessoa $pessoa): void { $this->pessoa = $pessoa; }

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getNum(): int { return $this->num; }
    public function setNum(int $num): void { $this->num = $num; }

}
