<?php

namespace zecchini;

interface InterfaceDAO {
    public function getArray(MyObject $o);
    public function getFormato();
    public function getObj($item);
    public function save(MyObject $o);
    public function getResults($where = null, $offset = null);
    public function update(MyObject $o);
    public function deleteByID($ID);
    public function exists(MyObject $o);
    public function search($query);
    public function getResultByID($ID);
    public function getArrayResult($resultQuery);
}
