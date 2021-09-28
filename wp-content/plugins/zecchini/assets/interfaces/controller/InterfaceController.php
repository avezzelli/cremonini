<?php


namespace zecchini;

interface InterfaceController {
    public function save(MyObject $o);    
    public function update(MyObject $o);
    public function delete($ID);
    
}
