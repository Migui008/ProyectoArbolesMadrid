<?php
function getAllArboles()
{
    require_once("bbddconnect.php");

    $sql = "SELECT nombre FROM arboles";
    $result = $conn->query($sql);
    $arboles = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($arboles, $row['nombre']);
        }
        $_SESSION['arbolesAllNames'] = $arboles;
    }
}
function getAllParques()
{
    require_once("bbddconnect.php");

    $sql = "SELECT nombre FROM parques";
    $result = $conn->query($sql);
    $parques = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($parques, $row['nombre']);
        }
        $_SESSION['parquesAllNames'] = $parques;
    }
}
function mostVisited()
{
    require_once("bbddconnect.php");
    $sql = "
    (
        SELECT 'arboles' AS tipo, nombre, visitas
        FROM arboles
        ORDER BY visitas DESC
        LIMIT 5
    )
    UNION ALL
    (
        SELECT 'parques' AS tipo, nombre, visitas
        FROM parques
        ORDER BY visitas DESC
        LIMIT 5
    )
    ORDER BY visitas
    ";
    $result = $conn->query($sql);
    $mostVisited = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($mostVisited, $row['nombre']);
        }
        $_SESSION['mostVisited'] = $mostVisited;
    }
}

function getInfoArbol($id)
{
    require_once("bbddconnect.php");

    try {
        $sql_arbol = "
        SELECT a.`nombre`, a.`nombre_cientifico`, a.`familia`, a.`clase`, a.`imagen`
        FROM `arboles` AS a
        WHERE a.`id_arbol` = :id;
        ";

        $stmt = $conn->prepare($sql_arbol);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $info_arbol = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($info_arbol) {
            $parques_relacionados = array();

            $sql_parques_ids = "
            SELECT r.`relaciones`
            FROM `relacion` AS r
            WHERE r.`id_relacion` = :id;
            ";

            $stmt = $conn->prepare($sql_parques_ids);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            $row_parques_ids = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row_parques_ids) {
                $parques_ids_relacion = explode(" ", $row_parques_ids['relaciones']);

                foreach ($parques_ids_relacion as $id_relacion) {
                    $sql_parque = "
                    SELECT p.`id_parque`, p.`nombre`
                    FROM `parques` AS p
                    JOIN `relacion` AS r ON FIND_IN_SET(p.`id_parque`, r.`relaciones`) > 0
                    WHERE r.`id_relacion` = :id_relacion
                    ";

                    $stmt = $conn->prepare($sql_parque);

                    $stmt->bindParam(':id_relacion', $id_relacion, PDO::PARAM_INT);

                    $stmt->execute();

                    $parque = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($parque) {
                        $parques_relacionados[] = $parque;
                    }
                }
            }

            $info_arbol['parques_relacionados'] = $parques_relacionados;
        } else {
            return array();
        }

        incrementarVisitasArbol($id);
        $_SESSION[$info_arbol['nombre']] = $info_arbol;
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
}

function incrementarVisitasArbol($arbol_id)
{
    require_once("bbddconnect.php");

    try {

        $stmt = $conn->prepare("CALL incrementarVisitasArbol(?)");

        $stmt->bindParam(1, $arbol_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
}

function getInfoParque($id)
{
    require_once("bbddconnect.php");

    try {
        $sql_parque = "
	SELECT p.`nombre`,p.`direccion`,p.`transporte_bus`,p.`transporte_metro`,p.`transporte_renfe`,p.`latitud`,p.`longitud`,p.`imagen` 
	FROM p.`parques` AS p 
	WHERE p.`id_parque` = :id;        
        ";

        $stmt = $conn->prepare($sql_parque);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $info_parque = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($info_parque) {
            $parques_relacionados = array();

            $sql_arboles_ids = "
            SELECT r.`relaciones`
            FROM `relacion` AS r
            WHERE r.`id_relacion` = :id;
            ";

            $stmt = $conn->prepare($sql_arboles_ids);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            $row_arboles_ids = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row_arboles_ids) {
                $arboles_ids_relacion = explode(" ", $row_arboles_ids['relaciones']);

                foreach ($arboles_ids_relacion as $id_relacion) {
                    $sql_arbol = "
                    SELECT a.`id_arbol`, a.`nombre`
                    FROM `arboles` AS a
                    JOIN `relacion` AS r ON FIND_IN_SET(p.`id_arbol`, r.`relaciones`) > 0
                    WHERE r.`id_relacion` = :id_relacion
                    ";

                    $stmt = $conn->prepare($sql_arbol);

                    $stmt->bindParam(':id_relacion', $id_relacion, PDO::PARAM_INT);

                    $stmt->execute();

                    $arbol = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($arbol) {
                        $arboles_relacionados[] = $arbol;
                    }
                }
            }

            $info_parque['arboles_relacionados'] = $arboles_relacionados;
        } else {
            return array();
        }

        incrementarVisitasParque($id);
        $_SESSION[$info_parque['nombre']] = $info_parque;
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
}

function incrementarVisitasParque($parque_id)
{
    require_once("bbddconnect.php");

    try {

        $stmt = $conn->prepare("CALL incrementarVisitasParque(?)");

        $stmt->bindParam(1, $parque_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
}
