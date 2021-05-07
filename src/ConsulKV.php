<?php

namespace KeyHoang\laravelConsulEnv;


class ConsulKV
{
    public function getKV(string $domain, string $path, string $token): array
    {
        $ch = curl_init($domain . "/v1/kv/" . $path . "?" . "recurse=true"); // such as http://example.com/example.xml
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Consul-Token:' . $token,'X-Consul-ContentHash']);
        $data = curl_exec($ch);
        curl_close($ch);

        return json_decode($data);
    }

}