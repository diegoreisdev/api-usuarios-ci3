<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->set_cors_headers();
    }

    /**
     * Configurar cabeçalhos CORS
     */
    private function set_cors_headers()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Max-Age: 86400');
    }

    /**
     * Listar todos os usuários (GET /api/users)
     */
    public function index()
    {
        try {
            $page = (int)$this->input->get('page') ?: 1;
            $per_page = (int)$this->input->get('per_page') ?: 10;
            $per_page = min($per_page, 20);

            $offset = ($page - 1) * $per_page;

            $users = $this->User_model->get_all($per_page, $offset);
            $total = $this->User_model->counts();
            $message = !$users ? 'Nenhuma usuário cadastrado' :  'Usuários listados com sucesso';

            $this->api_response->paginated(
                $users,
                $total,
                $page,
                $per_page,
                $message
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao listar usuários: ' . $e->getMessage());
            $this->api_response->error('Erro interno do servidor', 500);
        }
    }
}
