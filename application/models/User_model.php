<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Listar todos os usuários
     * 
     * @param int|null $limit
     * @param int|null $offset
     * @return array[}
     */
    public function get_all(?int $limit = null, ?int $offset = null): array
    {
        $this->db->select('id, name, email, phone, status, created_at, updated_at')
            ->order_by('created_at', 'DESC');

        if ($limit !== null) $this->db->limit($limit, $offset ?? 0);

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Obter usuário por ID
     * 
     * @param int $id 
     * @return array|null
     */
    public function get_by_id(int $id): ?array
    {
        $user = $this->db->select('id, name, email, phone, status, created_at, updated_at')
            ->where('id', $id)
            ->get($this->table)
            ->row_array();

        return $user ?: null;
    }

    /**
     * Criar novo usuário
     *
     * @param array $data
     * @return int|false
     */
    public function create(array $data): int|false
    {
        if (isset($data['password'])) $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $now = date('Y-m-d H:i:s');
        $data['created_at'] = $now;
        $data['updated_at'] = $now;

        if ($this->db->insert($this->table, $data)) return $this->db->insert_id();

        return false;
    }

    /**
     * Atualizar usuário
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->db->where('id', $id)->update($this->table, $data);
    }

    /**
     * Deletar usuário por ID
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    /**
     * Verificar se email já existe
     * 
     * @param string $email
     * @param int|null $exclude_id
     * @return bool
     */
    public function email_exists(string $email, ?int $exclude_id = null): bool
    {
        $this->db->where('email', $email);

        if ($exclude_id !== null) $this->db->where('id !=', $exclude_id);

        return $this->db->get($this->table)->num_rows() > 0;
    }

    /**
     * Contar total de usuários
     * 
     * @return int
     */
    public function counts(): int
    {
        return $this->db->count_all($this->table);
    }
}
