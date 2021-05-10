<?php


namespace KeyHoang\PhpConsulEnv;

class ConsulEnv
{
    protected static string $fileName = '.env.consul';
    protected static string $domain;
    protected static string $path;
    protected static string $token;

    public function __construct(string $domain, string $path, string $token)
    {
        self::$domain = $domain;
        self::$path   = $path;
        self::$token  = $token;
    }

    private function _getConsul(): array
    {
        $consul = new ConsulKV();

        return $consul->getKV(self::$domain, self::$path, self::$token);
    }

    private function writeKV(array $consuls): string
    {
        $env = '';
        foreach ($consuls as $consul) {
            if ($consul->Value) {
                $key   = strtoupper(str_replace('/', '_', str_replace(self::$path . "/", '', $consul->Key)));
                $value = str_replace('"', '', base64_decode($consul->Value));
                $env   .= $key . "=" . $value . "\n";
            }
        }

        return $env;
    }

    public function createEnvConsulFile()
    {
        if (file_exists(self::$fileName)) {
            unlink(self::$fileName);
        }
        $consul = $this->_getConsul();
        $env    = $this->writeKV($consul);
        $file   = self::$fileName;
        $person = $env;
        file_put_contents($file, $person, FILE_APPEND | LOCK_EX);
    }
}