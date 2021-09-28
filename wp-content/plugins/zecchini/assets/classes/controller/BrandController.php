<?php

namespace zecchini;

class BrandController implements InterfaceController {
    private $DAO;    
    private $cantiere;
    private $azienda;    
    
    function __construct(){
        $this->DAO = new BrandDAO();        
        $this->cantiere = new CantiereController();
        $this->azienda = new AziendaController();        
    }

    /********************** BRAND **********************/
    
    public function delete($ID) {
        return $this->DAO->deleteByID($ID);
    }

    public function save(MyObject $o) {
        return $this->DAO->save(updateToBrand($o));
    }

    public function update(MyObject $o) {
        return $this->DAO->update(updateToBrand($o));
    }
    
    /**
     * Ottengo un Brand e i cantieri associati
     * @param type $ID
     * @return type
     */
    public function getBrandByID($ID){        
        $result = null;
        $temp = $this->DAO->getResultByID($ID);
        if($temp != null){
            $result = updateToBrand($temp);
            $result->setCantieri($this->getCantieri($ID));
        }
        return $result;
    }
    
    public function getBrandByCliente($idCliente){
        $where = array(
            array(
                'campo'     => DBT_IDCLIENTE,
                'valore'    => $idCliente,
                'formato'   => 'INT'
            )
        );
        return $this->DAO->getResults($where);
    }
    
    public function getBrands($where){
        return $this->DAO->getResults($where);
    }
    
    public function getAllBrands(){
        return $this->DAO->getResults();
    }
    
    public function search($array, $offset = null){
        //ho 2 campi da controllare: nome, idcliente
        $result = array();
        $where = array(
            array(
                'campo'     => DBT_NOME,
                'valore'    => $array[FRM_BRAND_NOME],
                'formato'   => 'STRING',
                'operatore' => 'LIKE'
            ),
            array(
                'campo'     => DBT_IDCLIENTE,
                'valore'    => $array[FRM_BRAND_CLIENTE],
                'formato'   => 'NUM',
                'operatore' => '='
            )
        );
        
        $temp = $this->DAO->getResults($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $brand = updateToBrand($item);
                array_push($result, $brand);
            }
        }
        return $result;
    }
    
    
    /**************** CANTIERE ****************/
    
    /**
     * Restituisce un array di cantieri passato il brand
     * @param type $idBrand
     * @return type
     */
    private function getCantieri($idBrand){
        $where = array(
            array(
                'campo'     => DBT_IDBRAND,
                'valore'    => $idBrand,
                'formato'   => 'INT'
            )
        );
        return $this->cantiere->getCantieri($where);
    }
       
}
