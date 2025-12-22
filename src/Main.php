<?php

use Doctrine\DBAL\Logging\EchoSQLLogger;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use ORM\Doctrine\Entity\Dados;
use ORM\Doctrine\Entity\Pedido;
use ORM\Doctrine\Entity\Pessoa;
use Helper\EntityManagerCreator;

require_once __DIR__ . '/../vendor/autoload.php';
$entityManager = EntityManagerCreator::createEntityManager();

/**
 * @throws Exception
 */
function Main(): void
{
    global $entityManager;
    do
        {
            echo "\n------\n";
            echo "1 - Inserir novo pedido\n";
            echo "2 - Listar todos os pedidos\n";
            echo "3 - Deletar pessoa e seus pedidos\n";
            echo "4 - Atualizar dados de uma pessoa\n";
            echo "0 - Sair\n";
            echo "-----\n";
            $menu = readline("Escolha uma opção: ");
            try
            {
                switch ($menu)
                {
                    case 1:{
                        clearStdin();
                            echo "Cadsatro \n\n";

                           $conn = $entityManager->getRepository(Dados::class)->findAll();

                        if (!empty($conn))
                        {
                            recuperarDados();

                        }
                        else
                        {
                                echo "Nome: ";
                                $nomePessoa = readline();

                                echo "CPF: ";
                                $cpfPessoa = readline("");

                                echo "Num. Pedido: ";
                                $numPedido = (int) readline("");


                                if (validarDados($nomePessoa, $cpfPessoa, $numPedido) === true)
                                {
                                    insertPedidoCompleto($nomePessoa, $cpfPessoa, $numPedido);
                                }
                                else{
                                    insertDados($nomePessoa,$cpfPessoa,$numPedido);
                                }
                            break;
                        }
                    }
                    case 2:
                    {
                        clearStdin();
                        echo "Lista de pedidos\n";
                            listAll();
                        break;
                    }
                    case 3:
                    {
                        clearStdin();
                        echo "Deletar Pedido";

                            $idpessoa = (int) readline("\nID da pessoa: ");
                            if(!empty($idpessoa))
                            {
                                DeletePedido($idpessoa);
                                echo "Pedido Deletado com sucesso";
                            }
                            else
                            {
                                throw new Exception("Entrada ID inválida");
                            }
                        break;
                    }
                    case 4:
                    {
                        clearStdin();
                        echo "Atualizar pessoa";
                        $idPessoa = (int) readline("\nID da pessoa: ");
                        if (!empty($idPessoa))
                        {
                            upgradePessoa($idPessoa);
                            echo "Pedido Atualizado com sucesso";
                        }
                        else
                        {
                            throw new Exception("Entrada ID inválida");
                        }
                        break;
                    }
                    case 0:
                    {
                        $menu = 0;
                        echo "\nFinalizando programa\n";
                        break;
                    }
                    default:
                        echo "Opção inválida!";

                }
            }
            catch (ORMException|TypeError|Exception $e)
            {
                echo "\n\nErro: " . $e->getMessage()."\n\n";
            }
        }while($menu!=0);
};

function clearStdin(): void
{
    for ($i = 0; $i < 50; $i++)
    {
        echo ("\r\n");
    }
}

/**
 * @throws OptimisticLockException
 * @throws ORMException
 * @throws Exception
 */

function insertPedidoCompleto(string $nomePessoa, string $cpf, $numPedido):void
{

    global $entityManager;

    $pessoa = new Pessoa($nomePessoa, $cpf);
    $pedido = new Pedido($numPedido);

    $pedido->setPessoa($pessoa);
    $pessoa->addPedidos($pedido);

    $entityManager->persist($pedido);
    $entityManager->persist($pessoa);
    $entityManager->flush();
    echo "Pedido inserido com sucesso";

}

/**
 * @throws Exception
 * @throws ORMException
 */

function insertDados(?string $nomePessoa, ?string $cpf, ?int $numPedido):void
{

    global $entityManager;

    $dados = new Dados($nomePessoa, $cpf, $numPedido);

    $entityManager->persist($dados);
    $entityManager->flush();

}

function listAll(): void
{
    global $entityManager;

    $conn = $entityManager->getRepository(Pedido::class);
    $Pedidos = $conn->findAll();

    if($Pedidos != null)
    {
        foreach($Pedidos as $pedido)
        {
            $pessoa = $pedido->getPessoa();
            echo "\n\nId Pedido:{$pedido->getId()}\n";
            echo "Num. Pedido:{$pedido->getNum()}\n";
            echo "Id Pessoa:{$pessoa->getId()}\n";
            echo "Nome Pessoa:{$pessoa->getNome()}\n";
           echo " CPF Pessoa:{$pessoa->getCpf()}\n\n";
        }
    }
    else
    {
        throw new Exception("Nenhum pedido encontrado\n\n");
    }
}

/**
 * @throws ORMException
 * @throws Exception
 */

function DeletePedido($idPessoa): void
 {
     global $entityManager;

     $connP = $entityManager->find(Pessoa::class,$idPessoa);
     $connD = $entityManager->find(Dados::class,$idPessoa);

     if (!empty($connP) && !empty($connD))
     {
         $entityManager->remove($connP);
         $entityManager->remove($connD);
         $entityManager->flush();
     }

     else{
         throw new Exception("Nenhuma pessoa encontrada\n\n");
     }

 }

/**
 * @throws OptimisticLockException
 * @throws ORMException
 */

function upgradePessoa($idPessoa):void
{
     global $entityManager;
     $conn = $entityManager->find(Pessoa::class,$idPessoa);

     if($conn != null)
     {
         $pessoaNome = readline("Nome: ");
         $pessoaCpf = readline("CPF: ");
         $conn->setNome($pessoaNome);
         $conn->setCpf($pessoaCpf);

         $entityManager->flush();

     }else
     {
         throw new Exception("Nenhuma pessoa encontrada\n\n");
     }
}

function validarDados(string $nome, string $cpf, int $numPedido): bool
{

    $erro = [];
        if (empty($nome))
        {
            $erro[] = "\nErro: Campo nome é nulo ou inválido\n";

        }

        if (empty($cpf))
        {
            $erro[] = "\nErro: Campo CPF é nulo ou inválido\n";

        }

        if (empty($numPedido) || $numPedido <= 0) {
            $erro[] = "ERRO: Campo Num. Pedido é nulo ou inválido\n";

        }

        if (!empty($erro)) {
            foreach ($erro as $Erro) {
                echo $Erro;
            }
            return false;
        }

    return true;
}

/**
 * @throws OptimisticLockException
 * @throws ORMException
 */

function recuperarDados(): void
{
    global $entityManager;

    $temporarios  = $entityManager->getRepository(Dados::class)->findAll();

        foreach ($temporarios as $dados)
        {
            echo "\n--- Dados recuperados ---";
            echo "\nNome: {$dados->getNome()}";
            echo "\nCPF: {$dados->getCpf()}";
            echo "\nNum. Pedido: {$dados->getNumPedido()}";

            echo "\n\nInserir dados:\n";
            if (!empty($dados->getNome()))
            {
                $nome = $dados->getNome();
            }
            else{
                echo "\nNome: ";
                $nome = readline("");
            }
            if (!empty($dados->getCpf()) ){
                $cpf = $dados->getCpf();
            }
            else{
                echo "\nCPF: ";
                $cpf = readline("");
            }
            if (!empty($dados->getNumPedido())){
                $num = $dados->getNumPedido();
            }
            else{
                echo "\nNum Pedido: ";
                $num = readline("");
            }

            if (validarDados($nome, $cpf, $num)) {
                insertPedidoCompleto($nome, $cpf, $num);
            } else {
                echo "\nErro: dados inválidos\n";
                insertDados($nome, $cpf, $num);
            }

            $entityManager->remove($dados);
            $entityManager->flush();
        }
}

 Main();