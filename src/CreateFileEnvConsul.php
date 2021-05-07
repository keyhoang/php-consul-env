<?php


namespace KeyHoang\laravelConsulEnv;

class CreateFileEnvConsul
{
    protected string $fileName = '.env.consul';
    private function _getConsul(string $domain, string $path, string $token): array
    {
        $consul = new ConsulKV();

        return $consul->getKV($domain,$path, $token);
    }

    private function writeKV(array $consuls, string $path): string
    {
        $env = '';
        foreach ($consuls as $consul) {
            if ($consul->Value){
                $key = strtoupper(str_replace('/', '_', str_replace($path . "/",'',$consul->Key)));
                $value = str_replace('"','',base64_decode($consul->Value));
                $env .= $key . "=" . $value . "\n";
            }
        }
        return $env;
    }

    public function envConsulFile(string $domain, string $path, string $token)
    {
        if (file_exists($this->fileName)){
            unlink($this->fileName);
        }
        $consul = $this->_getConsul($domain,$path, $token);
        $env = $this->writeKV($consul,$path);
        $file   = $this->fileName;
        $person = $env;
        file_put_contents($file, $person, FILE_APPEND | LOCK_EX);
    }
}