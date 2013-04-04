<?php

namespace Simplex\Session;

class Secure implements \SessionHandlerInterface {
    protected $algo = MCRYPT_RIJNDAEL_128;
    protected $key;
    protected $auth;
    protected $path;
    protected $name;
    protected $ivSize;
    protected $keyName;



    protected function randomKey($length = 32) {
        if(defined('MCRYPT_DEV_URANDOM')) {
            return mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
        }
        else {
            throw new \Exception('Cannot generate a secure pseudo-random key. Please install Mcrypt extension');
        }
    }

    protected function getSessionFile($sessionId) {
        return $this->path . $this->name . '_' . $sessionId;
    }

    public function open($savePath, $sessionName) {
        $this->path    = rtrim($savePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->name    = $sessionName;
        $this->keyName = 'KEY_' . $sessionName;
        $this->ivSize  = mcrypt_get_iv_size($this->algo, MCRYPT_MODE_CBC);

        if(empty($_COOKIE[$this->keyName])
        || strpos($_COOKIE[$this->keyName], ':') === false) {
            $keyLength    = mcrypt_get_key_size($this->algo, MCRYPT_MODE_CBC);
            $this->key    = $this->randomKey($keyLength);
            $this->auth   = $this->randomKey(32);
            $cookie_param = session_get_cookie_params();

            setcookie(
                $this->keyName,
                base64_encode($this->key) . ':' . base64_encode($this->auth),
                $cookie_param['lifetime'],
                $cookie_param['path'],
                $cookie_param['domain'],
                $cookie_param['secure'],
                $cookie_param['httponly']
            );
        }
        else {
            list($this->key, $this->auth) = explode(':', $_COOKIE[$this->keyName]);
            $this->key  = base64_decode($this->key);
            $this->auth = base64_decode($this->auth);
        }

        return true;
    }

    public function close() {
        return true;
    }

    public function read($sessionId) {
        $sessionFile = $this->getSessionFile($sessionId);

        if(!file_exists($sessionFile)) {
            return false;
        }

        $data      = file_get_contents($sessionFile);
        list($hmac, $iv, $encrypted) = explode(':', $data);
        $iv        = base64_decode($iv);
        $encrypted = base64_decode($encrypted);
        $newHmac   = hash_hmac('sha256', $iv . $this->algo . $encrypted, $this->auth);

        if($hmac !== $newHmac) {
            return false;
        }

        $decrypt = mcrypt_decrypt(
            $this->algo,
            $this->key,
            $encrypted,
            MCRYPT_MODE_CBC,
            $iv
        );

        return rtrim($decrypt, "\0");
    }

    public function write($sessionId, $data) {
        $sessionFile = $this->getSessionFile($sessionId);

        $iv        = mcrypt_create_iv($this->ivSize, MCRYPT_DEV_URANDOM);
        $encrypted = mcrypt_encrypt(
            $this->algo,
            $this->key,
            $data,
            MCRYPT_MODE_CBC,
            $iv
        );
        $hmac  = hash_hmac('sha256', $iv . $this->algo . $encrypted, $this->auth);
        $bytes = file_put_contents($sessionFile, $hmac . ':' . base64_encode($iv) . ':' . base64_encode($encrypted));

        return ($bytes !== false);
    }

    public function destroy($sessionId) {
        $sessionFile = $this->getSessionFile($sessionId);

        setcookie ($this->keyName, '', time() - 3600);

        return(@unlink($sessionFile));
    }

    public function gc($maxlifetime) {
        foreach(glob($this->getSessionFile('*')) as $filename) {
            if(filemtime($filename) + $maxlifetime < time()) {
                @unlink($filename);
            }
        }

        return true;
    }
}