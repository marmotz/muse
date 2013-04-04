<?php

namespace Simplex\Sql\Logger;

use Doctrine\DBAL\Logging\SQLLogger;

class File implements SQLLogger {
    protected $fp;

    protected $sql;
    protected $params;
    protected $types;
    protected $start;
    protected $length;


    public function __construct($filename) {
        $this->fp = fopen($filename, 'a');
    }

    public function __destruct() {
        fclose($this->fp);
    }


    public function startQuery($sql, array $params = null, array $types = null) {
        $this->sql    = $sql;
        $this->params = $params;
        $this->types  = $types;
        $this->start  = microtime(true);
    }

    public function stopQuery() {
        $this->length = microtime(true) - $this->start;

        $this->log(
            sprintf(
                '[%s] SQL: (%d ms) %s',
                date('Y-m-d H:i:s'),
                $this->length,
                $this->sql
            )
        );

        if($this->params) {
            $this->log(
                sprintf(
                    '    PARAMS: %s',
                    print_r($this->params, true)
                )
            );
        }

        if($this->types) {
            $this->log(
                sprintf(
                    '    TYPES: %s',
                    print_r($this->types, true)
                )
            );
        }
    }


    protected function log($txt) {
        fwrite(
            $this->fp,
            $txt . "\n"
        );

    }
}