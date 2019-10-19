<?php


class Db_Adapter_Mysql implements Db_Adapter
{
    private $_dbLink;

    public function connect($config)
    {
        // TODO: Implement connect() method.
        if($this->_dbLink = @mysqli_connect($config->host.empty($config->port)?'':':',$config->user,$config->passwd, true)){
            if($config->charset) {
                mysqli_query("set names '{$config->charset}'",$this->_dbLink);
            }
            return $this->_dbLink;
        }
        throw new Db_Exception(@mysqli_error($this->_dbLink));
    }

    public function query($query, $handle)
    {
        // TODO: Implement query() method.
        if($resource = @mysqli_query($query, $handle)){
            return $resource;
        }
    }

}