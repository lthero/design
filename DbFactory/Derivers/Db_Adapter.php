<?php

interface Db_Adapter{
    public function connect($config);

    public function query($query,$handle);
}