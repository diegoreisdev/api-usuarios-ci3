<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_validator
{
    private object $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * Validar dados de criação de usuário
     *
     * @param array $data
     * @return true|array
     */
    public function validate_create_user(array $data): array|bool
    {
        $this->CI->form_validation->set_data($data);

        $this->CI->form_validation->set_rules('name', 'Nome', 'required|min_length[2]|max_length[100]');
        $this->CI->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[100]');
        $this->CI->form_validation->set_rules('password', 'Senha', 'required|min_length[6]|max_length[50]');
        $this->CI->form_validation->set_rules('phone', 'Telefone', 'max_length[20]');
        $this->CI->form_validation->set_rules('status', 'Status', 'in_list[active,inactive]');

        return $this->CI->form_validation->run() ? true : $this->get_validation_errors();
    }

    /**
     * Validar dados de atualização de usuário
     *
     * @param array $data
     * @return true|array
     */
    public function validate_update_user(array $data): array|bool
    {
        $this->CI->form_validation->set_data($data);

        $this->CI->form_validation->set_rules('name', 'Nome', 'min_length[2]|max_length[100]');
        $this->CI->form_validation->set_rules('email', 'Email', 'valid_email|max_length[100]');
        $this->CI->form_validation->set_rules('password', 'Senha', 'min_length[6]|max_length[50]');
        $this->CI->form_validation->set_rules('phone', 'Telefone', 'max_length[20]');
        $this->CI->form_validation->set_rules('status', 'Status', 'in_list[active,inactive]');

        return $this->CI->form_validation->run() ? true : $this->get_validation_errors();
    }

    /**
     * Obter erros de validação formatados
     *
     * @return array
     */
    private function get_validation_errors(): array
    {
        $errors = [];

        foreach ($this->CI->form_validation->error_array() as $field => $error) {
            $errors[] = [
                'field' => $field,
                'message' => $error
            ];
        }

        return $errors;
    }

    /**
     * Sanitizar dados de entrada
     *
     * @param array $data
     * @return array
     */
    public function sanitize_input(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            $sanitized[$key] = is_string($value) ? trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8')) : $value;
        }

        return $sanitized;
    }
}
