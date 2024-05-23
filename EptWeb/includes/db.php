<?php
$pdo = new PDO('mysql:dbname=ept_db;host=127.0.0.1;charset=utf8', 'root', '123456'); //Connexion
//Attention à modifier selon vos paramètres de connexion

//Une erreur provoquera une exception
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//Les résultats des requêtes sont sous forme d'objets
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); 

function addTableRecord($tableName, $fieldList, $valueList){
    global $pdo;

    $sql = "INSERT INTO " . $tableName . "(";
    for($i = 0; $i < count($fieldList); $i++){
        $sql .= $fieldList[$i];
        if($i == count($fieldList) - 1)
            $sql .= ") ";
        else
            $sql .= ", ";
    }
    $sql .= "VALUES(";
    for($i = 0; $i < count($valueList); $i++){
        $sql .= "?";
        if($i == count($valueList) - 1)
            $sql .= ");";
        else
            $sql .= ", ";
    }
    $req = $pdo->prepare($sql);
    $req->execute($valueList);
    if($req->rowCount() == 1)
        return true;
    else
        return false;

}

function updateTableRecord($tableName, $fieldList, $valueList, $keyList, $keyValues, $op = "AND"){
    global $pdo;

    $sql = "UPDATE " . $tableName . " SET ";
    for($i = 0; $i < count($fieldList); $i++){
        $sql .= $fieldList[$i] . " = ?";
        if($i == count($fieldList) - 1)
            $sql .= " ";
        else
            $sql .= ", ";
    }
    $sql .= "WHERE ";
    for($i = 0; $i < count($keyValues); $i++){
        $sql .= $keyList[$i] . " = ?";
        if($i == count($keyValues) - 1)
            $sql .= ";";
        else
            $sql .= " " . $op . " ";
    }

    $req = $pdo->prepare($sql);
    $req->execute(array_merge($valueList, $keyValues));
    return $req->rowCount();

}
