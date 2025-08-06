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
            $page     = (int)$this->input->get('page') ?: 1;
            $per_page = (int)$this->input->get('per_page') ?: 10;
            $per_page = min($per_page, 20);

            $offset = ($page - 1) * $per_page;

            $users   = $this->User_model->get_all($per_page, $offset);
            $total   = $this->User_model->counts();
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

    /**
     * Obter usuário específico (GET /api/users/{id})
     * 
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function show(int $id): void
    {
        try {
            if (!is_numeric($id) || $id <= 0)
                $this->api_response->error('ID inválido', 400);

            $user = $this->User_model->get_by_id($id);

            if (!$user)
                $this->api_response->error('Usuário não encontrado', 404);

            $this->api_response->success($user, 'Usuário encontrado com sucesso');
        } catch (Exception $e) {
            log_message('error', 'Erro ao buscar usuário: ' . $e->getMessage());
            $this->api_response->error('Erro interno do servidor', 500);
        }
    }

    /**
     * Criar usuário (POST /api/users)
     * 
     * @return void
     * @throws Exception
     */
    public function create(): void
    {
        try {
            $input_data = $this->get_json_input();
            if ($input_data === false) return;

            $data = $this->api_validator->sanitize_input($input_data);

            $validation = $this->api_validator->validate_create_user($data);
            if ($validation !== true)
                $this->api_response->error('Dados inválidos', 422, $validation);

            if ($this->User_model->email_exists($data['email']))
                $this->api_response->error('Email já está em uso', 409);

            $user_id = $this->User_model->create($data);

            if (!$user_id)
                $this->api_response->error('Erro ao criar usuário', 500);

            $user = $this->User_model->get_by_id($user_id);

            $this->api_response->success($user, 'Usuário criado com sucesso', 201);
        } catch (Exception $e) {
            log_message('error', 'Erro ao criar usuário: ' . $e->getMessage());
            $this->api_response->error('Erro interno do servidor', 500);
        }
    }

    /**
     * Atualizar usuário (PUT /api/users/{id})
     * 
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function update(int $id): void
    {
        try {
            if (!is_numeric($id) || $id <= 0)
                $this->api_response->error('ID inválido', 400);

            $existing_user = $this->User_model->get_by_id($id);
            if (!$existing_user)
                $this->api_response->error('Usuário não encontrado', 404);

            $input_data = $this->get_json_input();
            if ($input_data === false) return;

            $data = $this->api_validator->sanitize_input($input_data);

            $validation = $this->api_validator->validate_update_user($data);
            if ($validation !== true)
                $this->api_response->error('Dados inválidos', 422, $validation);

            if (isset($data['email']) && $this->User_model->email_exists($data['email'], $id))
                $this->api_response->error('Email já cadastrado', 409);

            if (!$this->User_model->update($id, $data))
                $this->api_response->error('Erro ao atualizar usuário', 500);

            $user = $this->User_model->get_by_id($id);

            $this->api_response->success($user, 'Usuário atualizado com sucesso');
        } catch (Exception $e) {
            log_message('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
            $this->api_response->error('Erro interno do servidor', 500);
        }
    }

    /**
     * Obter dados JSON da requisição
     */
    private function get_json_input()
    {
        $input = file_get_contents('php://input');

        if (empty($input))
            $this->api_response->error('Corpo da requisição vazio', 400);

        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE)
            $this->api_response->error('JSON inválido: ' . json_last_error_msg(), 400);

        if (!is_array($data))
            $this->api_response->error('Formato de JSON inválido', 400);

        return $data;
    }
}
