<?php
include "Column.php";
include("Connection.php");
class ManageTable{
//Properties
private $tableName;
private $nbColumn;
private $columns;
private $commentaireDeTable;
private $interclassement;
private $moteurDeStockage;

//Getters & Setters

public function getTableName(){
    return $this -> tableName;
}
public function setTableName($tableName){
    $this -> tableName = $tableName;
}
public function getNbColumn(){
    return $this -> nbColumn;
}
public function setNbColumn($nbColumn){
    if($nbColumn <= 0){
        return "Entrer un nombre valide";
    }
    $this -> nbColumn = $nbColumn;
}
public function getColumns(){
    return $this -> columns;
}
public function setColumns($columns){
    //if(count($columns) =! $this -> nbColumn){
      //  return "Le nombre de columns entrée faut étre egaux au nbColumns";
    //}
    $this -> columns = $columns;
}
public function getCommentaireDeTable(){
    return $this -> commentaireDeTable;
}
public function setCommentaireDeTable($commentaireDeTable){
    $this -> commentaireDeTable = $commentaireDeTable;
}
public function getInterclassement(){
    return $this -> interclassement;
}
public function setInterclassement($interclassement){
    $this -> interclassement = $interclassement;
}
public function getMoteurDeStockage(){
    return $this -> moteurDeStockage;
}
public function setMoteurDeStockage($moteurDeStockage){
    $this -> moteurDeStockage = $moteurDeStockage;
}
//Constructior
public function __construct($tableName, $nbColumn,$columns,$commentaireDeTable, $interclassement, $moteurDeStockage){
    $this -> setTableName($tableName);
    $this -> setNbColumn($nbColumn);
    $this -> setColumns($columns);
    $this -> setCommentaireDeTable($commentaireDeTable);
    $this -> setInterclassement($interclassement);
    $this -> setMoteurDeStockage($moteurDeStockage);
}
//Methods
//Get Connection  to realtime database
public function executeQuery($query){
    $connection = mysqli_connect("localhost","root","","PFM");
    $executeQuery = mysqli_query($connection, $query);
    return $executeQuery;    
}
//Afficher
        public function afficher($selectedData,$keyword,$condition,$creteria){
            $query= "select ";
            //Construct Que echo $query;  
            $countSelectedData = count($selectedData);
            if($countSelectedData> 0){
                for ($i=0; $i <$countSelectedData; $i++) { 
                    $query .= $selectedData[$i] .",";
                }
            }else{
                $query .="*";
            }
            $query .= " FROM ". $this -> tableName . " "  . $keyword;
        
        $creteriaCount = count($creteria);
                echo $creteria;
            if($creteriaCount > 0){
                echo $query;       
                foreach ($creteria as $key => $value) {
                    $query .= " $key $condition $value";
                }
            }
            $result = $this ->executeQuery($query);
                $raws = [];
            if($result->num_rows >0 ){
                    $raws = $result->fetch_all(MYSQLI_ASSOC);
            }
            if(!empty($raws)){
                foreach ($raws as $data ) {
                        foreach ($data as $key => $value) {
                            echo $key ." " .$value. "<br> " ;
                        }
                    
                    }
            }

        }
        //Ajouter
        public function ajouter($data){
        
            $query = "insert INTO  " .$this -> getTableName() . " (Name) VALUES (" . implode(',',$data). ")"; 
            echo $query;
            $this -> executeQuery($query);
        }
        //Modifier
        public function modifier($fields,$clause){
            //UPDATE table_name SET field1 = new-value1, field2 = new-value2    //[WHERE Clause]    
            
            $fieldOnString ="";  $i =0;
                foreach ($fields as $key => $value) {
                    if ($i == count($fields) - 1) {
                        $fieldOnString .= ' ' . $key . '= "' . $value . '"';
                    } else {
                        $fieldOnString .= ' ' . $key . '= "' . $value . '",';
                        $i++;
                    }
                    if ($clause == "") {
                        $query = "update " . $this->getTableName() . " SET $fieldOnString";
                    } else {
                        $query = "update " . $this->getTableName() . " SET $fieldOnString WHERE $clause";
                    }
                    echo $query . "<br>";
                    $this->executeQuery($query);
                
                }
        }
        //Suprimer
        public function Suprimer($clause){
        //DELETE FROM table_name WHERE condition;
            if($clause== ""){
                $query = "Delete FROM ". $this -> getTableName();
            }else{
                $query = "Delete FROM ". $this -> getTableName() . " WHERE $clause"; 
            } 
            $this -> executeQuery($query);
        }
     
}

//$column = new Column();
//$columns = array($column);
//$creteria = array("id" => 1);
//$table = new ManageTable("Test", 1,$columns,"test", "test", "test");
//$table ->afficher(array(),"where","=",$creteria);
//$enregistrement = array("'test5'");
//$table -> ajouter($enregistrement);
//$fields = array("name" => "test4");
//$table -> modifier($fields,"Id = 4");
//$table->Suprimer("");
?>