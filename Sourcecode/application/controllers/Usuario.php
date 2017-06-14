<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller
{

    /** @var $filmeModel Filme_model */
    private $filmeModel;

    /** @var $model Usuario_model */
    private $model;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('filme_model');
        $this->load->model('usuario_model');
        $this->filmeModel = &$this->filme_model;
        $this->model = &$this->usuario_model;
    }

    public function index()
    {
        $this->load->helper('form');
        $viewData = array('usuarios' => $this->model->listar());
        $baseContent = $this->load->view('usuario/index', $viewData, true);
        loadBaseView($baseContent, 'Usuários');
    }

    public function cadastrar()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

        if (($postData = $this->input->post()) && $this->form_validation->run('usuario'))
        {
            try
            {
                $this->model->cadastrar($postData);
                redirect('usuario');
            }
            catch (\Exception $ex)
            {
                show_error($ex->getMessage(), 500, 'Cadastro de usuário');
            }
        }

        $viewData = array('generosFilmes' => $this->filmeModel->obterGeneros());
        $baseContent = $this->load->view('usuario/new', $viewData, true);
        return loadBaseView($baseContent, 'Usuário: Inclusão');
    }

    public function editar($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

        if (($postData = $this->input->post()) && $this->form_validation->run('usuario'))
        {
            try
            {
                $this->model->atualizar($postData);
                redirect('usuario');
            }
            catch (\Exception $ex)
            {
                show_error($ex->getMessage(), 500, 'Edição de usuário');
            }
        }

        $this->load->helper('form');

        if (is_null($id) || !($usuario = $this->model->buscarById($id)))
        {
            show_error('Usuário não encontrado.', 404, 'Usuário');
        }

        $this->load->helper('form');

        $viewData = array(
            'usuario'       => $usuario,
            'generosFilmes' => $this->filmeModel->obterGeneros());

        $baseContent = $this->load->view('usuario/edit', $viewData, true);
        return loadBaseView($baseContent, 'Usuário: Edição');
    }

    public function remover()
    {
        if (($postData = $this->input->post()) && isset($postData['_id']))
        {
            $this->load->model('filme_model');

            if (is_null($this->model->buscar($postData['email'])))
            {
                show_error("Usuário com email {$postData['email']} não pôde ser encontrado.", 404, 'Usuário');
            }
            elseif ($this->filme_model->buscarFilmesAvaliados($postData['email'])->count() > 0)
            {
                show_error("Usuário com email {$postData['email']} não pode ser excluído por possuir vínculos com avaliações de filmes.", 500, 'Usuário');
            }
            elseif (!$this->model->remover($postData['_id']))
            {
                show_error('Usuário não pôde ser removido.', 500, 'Usuário');
            }
        }

        redirect('usuario');
    }

}
