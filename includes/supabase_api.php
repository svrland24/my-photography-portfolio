<?php
// ========================================================
// Supabase REST API Client Helper (No DB Host/Password needed!)
// Uses Project URL & anon key from Settings -> API Keys
// ========================================================

class SupabaseClient {
    private $url;
    private $key;

    public function __construct($url, $key) {
        $this->url = rtrim($url, '/');
        $this->key = $key;
    }

    private function request($endpoint, $method = 'GET', $data = null) {
        $ch = curl_init($this->url . '/rest/v1/' . ltrim($endpoint, '/'));
        
        $headers = [
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function select($table, $query = '') {
        $endpoint = $table . ($query ? '?' . $query : '');
        return $this->request($endpoint, 'GET');
    }

    public function insert($table, $data) {
        return $this->request($table, 'POST', $data);
    }

    public function update($table, $data, $query) {
        return $this->request($table . '?' . $query, 'PATCH', $data);
    }

    public function delete($table, $query) {
        return $this->request($table . '?' . $query, 'DELETE');
    }
}
