<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filme_model extends CI_Model
{

    /** @var $collection MongoCollection */
    private $collection;

    public function __construct()
    {
        parent::__construct();
        $this->collection = $this->mongo_db->db->selectCollection('filme');
    }

    // ---- CRUD

    public function cadastrar(array $dados)
    {
        if (!isset($dados['nome']))
        {
            throw new \Exception('Nome não informado para o cadastro do filme.');
        }

        try
        {
            $dados = array_replace(
                array_merge($dados, array('avaliacoes' => array())),
                array('ano' => intval($dados['ano']), 'imdb' => intval($dados['imdb'])));

            $this->collection->insert($dados);
        }
        catch (MongoDuplicateKeyException $ex)
        {
            throw new MongoDuplicateKeyException("Já existe um filme com nome {$dados['nome']}.");
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    public function buscar($id)
    {
        return $this->collection->findOne(array('_id' => new MongoId($id)));
    }

    public function listar()
    {
        return $this->collection->find()->sort(array('nome' => 1));
    }

    public function remover($id)
    {
        return $this->collection->remove(array('_id' => new MongoId($id)));
    }

    public function atualizar(array $dados)
    {
        try
        {
            $dados = array_replace(
                array_merge($dados, array('avaliacoes' => array())),
                array('ano' => intval($dados['ano']), 'imdb' => intval($dados['imdb'])));

            $this->collection->update(array('_id' => new MongoId($dados['id'])), $dados);
        }
        catch (MongoDuplicateKeyException $ex)
        {
            throw new MongoDuplicateKeyException("Já existe um filme com nome {$dados['nome']}.");
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    // ---- Avaliação

    public function buscarFilmesParaAvaliacao($emailUsuario)
    {
        return $this->collection->find(
            array('avaliacoes.usuario' => array('$ne' => $emailUsuario)),
            array('_id' => true, 'nome' => true)
        )->sort(array('nome' => 1));
    }

    public function buscarFilmesAvaliados($emailUsuario)
    {
        return $this->collection->find(
            array('avaliacoes.usuario' => $emailUsuario),
            array('_id' => true, 'nome' => true)
        )->sort(array('nome' => 1));
    }

    public function avaliar(array $dados)
    {
        if (!isset($dados['filme']) || !isset($dados['usuario']) || !isset($dados['avaliacao']))
        {
            throw new \Exception('Parâmetros incorretos para avaliação do filme.');
        }

        try
        {
            $this->collection->update(
                array('_id' => new MongoId($dados['filme'])),
                array('$push' => array(
                    'avaliacoes' => array(
                        'usuario'   => $dados['usuario'],
                        'avaliacao' => intval($dados['avaliacao'])
                    )
                ))
            );
        }
        catch (MongoDuplicateKeyException $ex)
        {
            throw new MongoDuplicateKeyException("Este filme já foi avaliado pelo usuário {$dados['usuario']}.");
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    public function gerarIndicacao(array $usuario)
    {
        $map = new MongoCode(
            'function() {
                var filme = this;
                filme.avaliado = false;
                
                if (this.avaliacoes.length > 0)
                {
                    var soma = 0;
                    
                    for (var av in this.avaliacoes)
                    {
                        soma += this.avaliacoes[av].avaliacao;
                         
                        if (!filme.avaliado && this.avaliacoes[av].usuario === usuario.email)
                        {
                            filme.avaliado = true;
                        }
                    }
                    
                    if (soma > 0)
                    {
                        filme.media = soma / this.avaliacoes.length;
                    }
                }    
                
                emit(1, filme);
            }'
        );

        $reduce = new MongoCode(
            'function(key, filmes) {
                var avaliados = [],
                    indicados = [],
                      retorno = [];
                    
                for (var f in filmes)
                {
                    filmes[f].avaliado ? avaliados.push(filmes[f]) : indicados.push(filmes[f]);
                }
                
                for (var i = 0; i < indicados.length; ++i)
                {
                    var filme = indicados[i];
                    
                    // Filmes não avaliados (sem média calculada) e com média inferior à 3 não são indicados
                    if (!filme.hasOwnProperty("media") || filme.media < 3)
                    {
                        delete indicados[i];
                    }
                    // Se o usuário não tiver idade suficiente para assistí-lo, o filme não é indicado
                    else if (parseInt(filme.faixaEtaria) && parseInt(usuario.idade) < parseInt(filme.faixaEtaria))
                    {
                        delete indicados[i];
                    }
                    else
                    {   
                        // Se o filme possuir as mesmas tags de outros filmes de preferência do usuário (avaliados), 
                        // é-lhe atribuído um peso (somatória da quantidade de tags idênticas)
                        var somaTags = 0;
                        for (var f in avaliados)
                        {
                            for (var ft in avaliados[f].tags)
                            {
                                for (var t in filme.tags)
                                 {
                                    if (filme.tags[t] === avaliados[f].tags[ft])
                                    {
                                        somaTags++;
                                    }
                                 }
                            }
                        }
                        
                        indicados[i].pesoTags = somaTags;
                        
                        // Se o filme possuir o gênero de preferência do usuário, possui um peso maior na indicação
                        // Caso contrário, desconta-se 1 do peso da avaliação geral
                        var encontrado = false;
                        for (var g in usuario.generosFilmes)
                        {
                            if (filme.genero === usuario.generosFilmes[g])
                            {
                                encontrado = true;
                                break;
                            }
                        }
                        
                        indicados[i].pesoGenero = encontrado ? 1 : -1;
                    }
                }
                
                for (var f in indicados)
                {
                    if (indicados[f] !== null)
                    {
                        retorno.push({
                            filme: {
                                _id:            indicados[f]._id,
                                nome:           indicados[f].nome,
                                ano:            indicados[f].ano,
                                diretor:        indicados[f].diretor,
                                genero:         indicados[f].genero,
                                imdb:           indicados[f].imdb,
                                tags:           indicados[f].tags,
                                faixaEtaria:    indicados[f].faixaEtaria,
                            },
                            pesos: {
                                avaliacao:      indicados[f].media,
                                genero:         indicados[f].pesoGenero,
                                tags:           indicados[f].pesoTags
                            }
                        });
                    }
                }
                
                // Efetua a ordenação do mais indicado (com maior score) para o
                //  menos indicado (com menor score)
                if (retorno.length > 0)
                {
                    retorno.sort(function(a, b) {
                        var mediaA = (a.pesos.avaliacao * 4 + a.pesos.genero * 2 + a.pesos.tags * 4 + a.filme.imdb * 3) / 13;
                        var mediaB = (b.pesos.avaliacao * 4 + b.pesos.genero * 2 + b.pesos.tags * 4 + b.filme.imdb * 3) / 13;
                        return mediaB - mediaA;
                    });
                }
            
                return { filmes: retorno };
            }'
        );

        $finalize = new MongoCode(
            'function(key, filmes) {
                var retorno = [];
                
                for (var fi in filmes)
                {
                    for (var f in filmes[fi])
                    {
                        var filme = filmes[fi][f].filme;
                        filme.avaliacaoMedia = filmes[fi][f].pesos.avaliacao;
                        retorno.push(filme);
                    }
                }
                
                return retorno;
            }'
        );

        try {
            /** @var $db MongoDB */
            $db = &$this->mongo_db->db;
            $filmes = $db->command(array(
                'mapreduce' => 'filme',
                'map'       => $map,
                'reduce'    => $reduce,
                'finalize'  => $finalize,
                'scope'     => array('usuario' => $usuario),
                'out'       => array('inline' => true)));

            return $filmes['results'][0]['value'];
        } catch(\Exception $ex) {
            return array();
        }
    }

    // ---- Auxiliares

    public function obterFaixasEtarias()
    {
        return array(
            'L'     => 'Livre',
            '10'    => 'Não recomendado para menores de dez anos',
            '12'    => 'Não recomendado para menores de doze anos',
            '14'    => 'Não recomendado para menores de quatorze anos',
            '16'    => 'Não recomendado para menores de dezesseis anos',
            '18'    => 'Não recomendado para menores de dezoito anos');
    }

    public function obterGeneros()
    {
        return array(
            'Ação',
            'Animação',
            'Aventura',
            'Comédia',
            'Drama',
            'Romance',
            'Suspense',
            'Terror');
    }

}