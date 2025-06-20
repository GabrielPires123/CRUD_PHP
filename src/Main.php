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
                    case 1:
                    {

                        clearStdin();
                            echo "Cadsatro \n\n";

                            $conn = $entityManager->getRepository(Dados::class);
                            $N=  null;
                            $C=  null;
                            $P1=  null;
                            $P = $conn->findAll();
                            DeeleteDados();

                            if ($P != null) {
                                foreach ($P as $dados)
                                {

                                    echo "Dados recuperados: ";
                                    echo "\nNome: {$dados->getNome()}  \n";
                                    echo "\nCPF: {$dados->getCpf()} \n";
                                    echo "\nNum. Pedido: {$dados->getNumPedido()}  \n";

                                    if ($dados->getNome() == null)
                                    {
                                        echo "\nNome: ";
                                        $N =  readline("");
                                    }
                                    else{
                                        $N = $dados->getNome();
                                    }

                                    if ($dados->getCpf() == null || (strlen($dados->getCpf()) != 11))
                                    {
                                        echo "\nCpf: ";
                                        $C = (string) readline("");
                                    }
                                    else{
                                        $C = $dados->getNome();
                                    }

                                    if ($dados->getNumPedido() == null){
                                        echo "\nNum. Pedido: ";
                                        $P1 = (int) readline("");
                                    }
                                    else{
                                        $P1 = $dados->getNumPedido();
                                    }
                                    insertPedidoCompleto($N,$C,$P1);
                                }
                            }
                            else
                            {
                                echo "Nome: ";
                                $nomePessoa = readline();
                                echo "CPF: ";
                                $cpfPessoa = readline("");
                                echo "Num. Pedido: ";
                                $numPedido = (int) readline("");

                                insertDados($nomePessoa,$cpfPessoa,$numPedido);
                                insertPedidoCompleto($nomePessoa, $cpfPessoa, $numPedido);

                            }

                            break;
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
                            if($idpessoa != null && $idpessoa != "" && $idpessoa > 0)
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
                        if ($idPessoa != null && $idPessoa != "" && $idPessoa > 0)
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

    $pessoa = new Dados($nomePessoa, $cpf,$numPedido);

    $entityManager->persist($pessoa);
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

     if ($connP != null) {
         $entityManager->remove($connP);
         $entityManager->flush();

     }

     if ($connD != null) {
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

/**
 * @throws ORMException
 */
function DeeleteDados(): void
{
    global $entityManager;

    $P = $entityManager->getRepository(Dados::class)->findAll();

    if ($P != null)
    {
        foreach ($P as $dados)
        {
            $id = $dados->getId();
            DeletePedido($id);
        }
    }

}

 Main();
