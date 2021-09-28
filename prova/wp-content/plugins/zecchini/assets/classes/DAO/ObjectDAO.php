<?php
namespace zecchini;
/**
 * Description of ObjectDAO
 * Classe generica dedita alle operazioni base sul database
 *
 * @author Alex
 */
class ObjectDAO {
    
    private $wpdb;
    private $table;
    
    function __construct($table) {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->wpdb->prefix = DB_PREFIX;
        $this->table = $this->wpdb->prefix.$table;
    }
    
    /**
     * Funzione di salvataggio di un oggetto nel database
     * @param type $campi
     * @param type $formato
     * @return boolean
     */
    protected function saveObject($campi, $formato){   
        //salvo un oggetto generico
        try{
            $this->wpdb->insert(
                    $this->table,
                    $campi,
                    $formato
            );
            //$this->wpdb->show_errors();
            //$this->wpdb->print_error();
            return $this->wpdb->insert_id;
            
        } catch (Exception $ex) {
            print_r($ex);
            return false;
        }
    }
    
    /**
     * La funzione restituisce un array di oggetti a seconda se è stato impostato un offset
     * @param type $where
     * @param type $offset
     * @return type
     */
    protected function getObjectsDAO($where = null, $order = null, $offset = null){
        $temp = null;
        if($offset !== null){
            $temp = $this->getObjects(null, $where, $order, ELEMENTI_PER_PAGINA, $offset);            
        }
        else{
            $temp = $this->getObjects(null, $where, $order);
        }
        return $temp;
    }
    
    
    /**
     * La funzione passati i parametri di select, where e order restituisce un oggetto 
     * @param type $select --> array(...,...,...)
     * @param type $where --> array(array('campo' => x, 'valore' => y, 'formato' => z))
     * @param type $order --> array(array('campo' => x, 'ordine' => y) )
     * @return type
     */
    protected function getObjects($select = null, $where = null, $order = null, $limit = null, $offset = null){        
        
        //Vengono indicati i campi di select
        $query = "SELECT";
        if($select === null){
            $query.= " *";
        }
        else{
            $counter = 0;
            foreach($select as $value){
                $query.= " ".$value;
                if($counter == count($select) -1){
                    
                }
                else{
                    $query.=",";
                }
                $counter++;
            }
        }        
        //Viene indicata la tabella
        $query .= " FROM ".$this->table;    
        
        //Vegnono indicati i campi where (se ce ne sono)
        if($where !== null){
            $query.= " WHERE 1=1 ";
            $counter = 0;
            foreach($where as $item){
                if(!isset($item['operatore'])){
                    if($item['formato'] == 'INT'){
                        $query.=" AND ".$item['campo']." = ".$item['valore'];
                    }
                    else{
                        $queryValore = $item['valore'];
                        //controllo sul carattere di apostrofo "'" 
                        if ($item['valore'] !== false) {
                            $queryValore = str_replace("\'", "\\\''", $queryValore);                       
                        } 
                        $query.=" AND ".$item['campo']." = '".$queryValore."'";
                    }                    
                }
                else{
                    if($item['valore'] != ''){
                    
                        if($item['formato'] == 'NUM' && $item['valore'] != ''){
                            $query.=" AND ".$item['campo']." ".$item['operatore']." ".$item['valore'];
                        }
                        else{
                            $queryValore = $item['valore'];
                            //controllo sul carattere di apostrofo "'" 
                            if ($item['valore'] !== false) {
                                $queryValore = str_replace("\'", "\\\''", $queryValore);                       
                            }
                            if($queryValore != ''){
                            
                                if($item['operatore'] == 'LIKE'){
                                    $query.=" AND ".$item['campo']." LIKE '%".$queryValore."%'";
                                }
                                else{
                                    $query.=" AND ".$item['campo']." ".$item['operatore']." '".$queryValore."'";
                                }
                            }
                        }                        
                    }
                }
                               
            }
        } 
        
        //Vengono indicati i campi di order by (se ce ne sono)
        if($order !== null){
            $query.= " ORDER BY";
            $counter = 0;
            foreach($order as $item){
                $query.= " ".$item['campo']." ".$item['ordine'];
                if($counter == count($order) -1){
                    
                }
                else{
                    $query.=",";
                }
                $counter++;
            }
        }
        else{
            
        }
        
        if($limit !== null && $offset !== null){
            $query .= " LIMIT ".$offset.", ".$limit;
        }
        
        //print_r($query);
        
        try{  
            //restituisco un array associativo per mantenere le definizioni
            return $this->wpdb->get_results($query, ARRAY_A);                        
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }        
    }
    
    /**
     * La funzione esegue una query sul database, con query passata come parametro
     * @param type $query
     * @return type
     */
    protected function searchObjects($query){        
        try{
            return $this->wpdb->get_results($query, ARRAY_A);
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }
       
    /**
     * La funzione elimina un oggetto dal database
     * @param type $array
     * @return boolean
     */
    protected function deleteObject($array): bool{        
        try{
            $this->wpdb->delete($this->table, $array);
            return true;
        } catch (Exception $ex) {
            _e($ex);
            return false;
        }        
    }
    
    /**
     * Funzione che elimina un oggetto dal database per ID passato
     * @param type $ID
     * @return type
     */
    protected function deleteObjectByID($ID){
        $array = array('ID' => $ID);
        return $this->deleteObject($array);
    }
    
    /**
     * La funzione aggiorna un oggetto nel database
     * @param type $update
     * @param type $formatUpdate
     * @param type $where
     * @param type $formatWhere
     * @return boolean
     */
    protected function updateObject($update, $formatUpdate, $where, $formatWhere){             
        try{
            return $this->wpdb->update(
                    $this->table,
                    $update,
                    $where,
                    $formatUpdate,
                    $formatWhere
            );            
        } catch (Exception $ex) {
            _e($ex);
            return null;
        }
    }
    
    protected function getTable(){
        return $this->table;
    }
    
    protected function existsID($ID): bool {
        $where = array(
            array(
                'campo'     => DBT_ID,
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        
        if($this->getObjects(null, $where) != null ){
            return true;
        }
        return false;        
    }  
    
    /**
     * Restituisce un singolo oggetto dato uno specifico ID
     * @param type $ID
     * @return type
     */
    public function getResultByID($ID){
        $where = array(
            array(
                'campo'     => DBT_ID,
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );      
        $temp = $this->getObjects(null, $where);
        if($temp != null){
            return $temp[0];
        }
        return null;
    }
}
