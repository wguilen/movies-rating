<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model
{

    /** @var $collection MongoCollection */
    private $collection;

    public function __construct()
    {
        parent::__construct();
        $this->collection = $this->mongo_db->db->selectCollection('usuario');
    }

    public function cadastrar(array $dados)
    {
        if (!isset($dados['email']))
        {
            throw new \Exception('E-mail não informado para o cadastro do usuário.');
        }

        try
        {
            $this->collection->insert($dados);
        }
        catch (MongoDuplicateKeyException $ex)
        {
            throw new MongoDuplicateKeyException("Já existe um usuário com e-mail {$dados['email']}.");
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    public function buscar($email)
    {
        return $this->collection->findOne(array('email' => $email));
    }

    public function buscarById($id)
    {
        return $this->collection->findOne(array('_id' => new MongoId($id)));
    }

    public function listar()
    {
        return $this->collection->find()->sort(array('email' => 1));
    }

    public function atualizar(array $dados)
    {
        try
        {
            $this->collection->update(array('_id' => new MongoId($dados['id'])), $dados);
        }
        catch (MongoDuplicateKeyException $ex)
        {
            throw new MongoDuplicateKeyException("Já existe um usuário com o e-mail {$dados['email']}.");
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    public function remover($id)
    {
        return $this->collection->remove(array('_id' => new MongoId($id)));
    }

}