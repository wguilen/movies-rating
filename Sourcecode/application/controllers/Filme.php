<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filme extends CI_Controller
{

    /** @var $model Filme_model */
    private $model;

    /** @var $usuarioModel Usuario_model */
    private $usuarioModel;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('filme_model');
        $this->load->model('usuario_model');
        $this->model = &$this->filme_model;
        $this->usuarioModel = &$this->usuario_model;
    }

    public function index()
    {
        $this->load->helper('form');
        $viewData = array('filmes' => $this->model->listar());
        $baseContent = $this->load->view('filme/index', $viewData, true);
        loadBaseView($baseContent, 'Filmes', 'filmes');
    }

    public function cadastrar()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

        if (($postData = $this->input->post()) && $this->form_validation->run('filme'))
        {
            try
            {
                if (strcasecmp($postData['tags'], '') !== 0)
                {
                    $tags = explode(',', $postData['tags']);
                    asort($tags);
                    $postData['tags'] = $tags;
                }

                $this->model->cadastrar($postData);
                redirect('filme');
            }
            catch (\Exception $ex)
            {
                show_error($ex->getMessage(), 500, 'Cadastro de filme');
            }
        }

        $viewData = array(
            'faixasEtarias' => $this->model->obterFaixasEtarias(),
            'generos'       => $this->model->obterGeneros());

        $baseContent = $this->load->view('filme/new', $viewData, true);
        return loadBaseView($baseContent, 'Filme: Inclusão', 'filmes');
    }

    public function editar($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

        if (($postData = $this->input->post()) && $this->form_validation->run('filme'))
        {
            try
            {
                if (strcasecmp($postData['tags'], '') !== 0)
                {
                    $tags = explode(',', $postData['tags']);
                    asort($tags);
                    $postData['tags'] = $tags;
                }

                $this->model->atualizar($postData);
                redirect('filme');
            }
            catch (\Exception $ex)
            {
                show_error($ex->getMessage(), 500, 'Edição de filme');
            }
        }

        if (is_null($id) || !($filme = $this->model->buscar($id)))
        {
            show_error('Filme não encontrado.', 404, 'Filme');
        }

        $this->load->helper('form');
        
        $viewData = array(
            'filme'         => $filme,
            'faixasEtarias' => $this->model->obterFaixasEtarias(),
            'generos'       => $this->model->obterGeneros());

        $baseContent = $this->load->view('filme/edit', $viewData, true);
        return loadBaseView($baseContent, 'Filme: Edição', 'filmes');
    }

    public function remover()
    {
        if (($postData = $this->input->post()) && isset($postData['_id']))
        {
            if (!$this->model->remover($postData['_id']))
            {
                show_error('O filme não pôde ser removido.', 500, 'Exclusão de filme');
            }
        }

        redirect('filme');
    }

    public function avaliar()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

        if (($postData = $this->input->post()) && $this->form_validation->run('filme-avaliacao'))
        {
            try
            {
                $this->model->avaliar($postData);
                redirect('filme/avaliar');
            }
            catch (\Exception $ex)
            {
                show_error($ex->getMessage(), 500, 'Avaliação de filme');
            }
        }

        $this->load->helper('form');
        $viewData = array('usuarios' => $this->usuarioModel->listar());
        $baseContent = $this->load->view('filme/rating', $viewData, true);
        return loadBaseView($baseContent, 'Filmes - Avaliação', 'filmes');
    }

    public function indicacao()
    {
        $viewData = array('usuarios' => $this->usuarioModel->listar());
        $baseContent = $this->load->view('filme/discover', $viewData, true);
        return loadBaseView($baseContent, 'Filmes - Indicação', 'filmes');
    }

    public function obterIndicacoesUsuario()
    {
        if (!$this->input->is_ajax_request())
        {
            show_error('Acesso não autorizado.', 500, 'Erro');
        }
        
        $filmes = array();

        if (($postData = $this->input->post()) && isset($postData['email']))
        {
            if (!is_null($usuario = $this->usuarioModel->buscar($postData['email'])))
            {
                $filmes = $this->model->gerarIndicacao($usuario);
            }
        }

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($filmes));
    }

    public function obterFilmesUsuario()
    {
        if (!$this->input->is_ajax_request())
        {
            show_error('Acesso não autorizado.', 500, 'Erro');
        }

        $filmes = array();

        if (($postData = $this->input->post()) && isset($postData['email']))
        {
            $filmesPendentes = $this->model->buscarFilmesParaAvaliacao($postData['email']);
            if ($filmesPendentes->count() > 0)
            {
                foreach ($filmesPendentes as $filmePendente)
                {
                    $filmes[] = $filmePendente;
                }
            }
        }

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($filmes));
    }

}
