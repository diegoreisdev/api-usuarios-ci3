<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_response
{
    private object $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * Resposta de sucesso
     * 
     * @param object|null $data
     * @param string $message
     * @param int $status_code 
     * @return void
     */
    public function success(?object $data = null, string $message = 'Sucesso', int $status_code = 200): void
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        $this->respond($response, $status_code);
    }

    /**
     * Resposta de erro
     * 
     * @param mixed string
     * @param int $status_code
     * @param null $errors
     * @return void
     */
    public function error(string $message = 'Erro interno', int $status_code = 500, $errors = null): void
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors) $response['errors'] = $errors;

        $this->respond($response, $status_code);
    }

    /**
     * Resposta paginada
     * 
     * @param mixed $data
     * @param int $total
     * @param int $page
     * @param int $per_page
     * @param string $message
     * @return void
     */
    public function paginated($data, int $total, int $page, int $per_page, string $message = 'Sucesso'): void
    {
        $total_pages = (int) ceil($total / $per_page);

        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $per_page,
                'total' => $total,
                'total_pages' => $total_pages,
                'has_next' => $page < $total_pages,
                'has_prev' => $page > 1
            ]
        ];

        $this->respond($response);
    }

    /**
     * Método interno para resposta padronizada
     * 
     * @param array $response
     * @param int $status_code
     * @return void
     */
    private function respond(array $response, int $status_code = 200): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status_code);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
