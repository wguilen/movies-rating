<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'filme' => array(
        array('field' => 'ano', 'label' => 'Ano', 'rules' => 'required|integer|greater_than_equal_to[1900]'),
        array('field' => 'diretor', 'label' => 'Diretor', 'rules' => 'required'),
        array('field' => 'faixaEtaria', 'label' => 'Faixa etária', 'rules' => 'required'),
        array('field' => 'genero', 'label' => 'Gênero', 'rules' => 'required'),
        array('field' => 'imdb', 'label' => 'IMDB', 'rules' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[10]'),
        array('field' => 'nome', 'label' => 'Nome', 'rules' => 'required'),
        array('field' => 'tags', 'label' => 'Tags', 'rules' => 'required')),
    'filme-avaliacao' => array(
        array('field' => 'usuario', 'label' => 'Usuário', 'rules' => 'required'),
        array('field' => 'filme', 'label' => 'Filme', 'rules' => 'required'),
        array('field' => 'avaliacao', 'label' => 'Avaliação', 'rules' => 'required|greater_than_equal_to[0]|less_than_equal_to[5]')),
    'usuario' => array(
        array('field' => 'email', 'label' => 'E-mail', 'rules' => 'required|valid_email'),
        array('field' => 'genero', 'label' => 'Gênero', 'rules' => 'required'),
        array('field' => 'generosFilmes[]', 'label' => 'Gêneros preferidos para filmes', 'rules' => 'required'),
        array('field' => 'idade', 'label' => 'Gênero', 'rules' => 'required|integer'),
        array('field' => 'nome', 'label' => 'Nome', 'rules' => 'required'))
);
