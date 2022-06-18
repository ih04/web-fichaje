<?php

function cabecera(string $titulo = "Ejemplo") // el archivo actual
{
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>
				<?php
    echo $titulo;
    ?>
			
			</title>
<meta charset="utf-8" />
</head>
<body>
<?php
}

function pie()
{
    echo "</body>
	</html>";
}

function sinTildes(string $frase)
{
    $no_permitidas = array(
        "á",
        "é",
        "í",
        "ó",
        "ú",
        "Á",
        "É",
        "Í",
        "Ó",
        "Ú",
        "à",
        "è",
        "ì",
        "ò",
        "ù",
        "À",
        "È",
        "Ì",
        "Ò",
        "Ù"
    );
    $permitidas = array(
        "a",
        "e",
        "i",
        "o",
        "u",
        "A",
        "E",
        "I",
        "O",
        "U",
        "a",
        "e",
        "i",
        "o",
        "u",
        "A",
        "E",
        "I",
        "O",
        "U"
    );
    $texto = str_replace($no_permitidas, $permitidas, $frase);
    return $texto;
}

/* Permite quitar todos los espacios si le pasamos como segundo parámetro la cadena vacía */
function sinEspacios(string $frase, string $espacio = " ")
{
    $texto = trim(preg_replace('/ +/', $espacio, $frase));
    return $texto;
}

function recoge(string $var, bool $espacios = FALSE)
{
    $espacios = $espacios ? "" : " ";
    if (isset($_REQUEST[$var]))
        $tmp = strip_tags(sinEspacios($_REQUEST[$var]), $espacios);
    else
        $tmp = "";

    return $tmp;
}

function recogeCheck(string $text)
{
    if (isset($_REQUEST[$text]))
        return true;
    else
        return false;
}

function recogeRadio(string $text)
{
    if (isset($_REQUEST[$text]))
        return strip_tags(sinEspacios($_REQUEST[$text]));
    else
        return false;
}

//función para mover fotos


function moverFoto($value,$ruta,&$err){

    if(move_uploaded_file($_FILES[$value]['tmp_name'],$ruta)){
        return true;
    }else{
            $err[]="el archivo no se pudo mover!";
            return false;
    }
}

/*
 * Función que pásandole la expresión regular nos hace la validación. Sirve para casos de validaciones especiales
 *
 */

//mi función para comprobar las fotos y sus formatos
function cFot($foto,&$err,$formatos=array('png','jpeg','jpg','gif')){
  
    $formato;
    $valid=false;
        
    $foto=explode(".",$foto);
        
    

    foreach($formatos as $form){
        if($foto[1]===$form){

           $valid=true;
        }
    }
    

    if($valid==false)$err[]="el formato de la foto NO es valido!";
    return $valid;

    if($_FILES[$foto]['size']>3000)$err[]="La imágen pesa demasiado";
}




function cGeneral(string $cadena,string $campo, array &$errores,string $regex)
{
    if (preg_match($regex, $cadena))
        return true;
    else {
        $errores[$campo] = "$campo incorrecto/a";
        return false;
    }
}

/*
 * Validación del teléfono permitiendo de manera opcional ponerle el prefijo internacional español
 */
function cTelefono(string $cadena, string $campo, array &$errores)
{
    $regex = "/^(\+34|0034){0,1}[1-9][0-9]{8}$/";
    if (preg_match($regex, preg_replace("/[\s-]+/", "", $cadena)))
        return true;
    else {
        $errores[$campo] = "El $campo no es correcto";
        return false;
    }
}

function cMail(string $text, string $campo, array &$errores)
{
    if ((filter_var($text, FILTER_VALIDATE_EMAIL))) {

        return true;
    }
    $errores[$campo] = "El $campo no es correcto";
    return false;
}

/* Cadenas numéricas por defecto entre 1 y 10 dígitos */
function cNum(string $num, string $campo, string &$errores, int $max = 10,int $min = 1)
{
    if ((preg_match("/^[0-9]{" . $min . "," . $max . "}$/", $num))) {

        return true;
    }
    $errores[$campo] = "El $campo solo puede contener números de $min a $max dígitos";
    return false;
}

/* Por defecto es no sensible a mayúsculas, permite un espacio entre palabras y cadenas de longitud entre 1 y 30 */
function cTexto(string $text, string $campo, array &$errores, int $max = 30, int $min = 1, string $espacios = " ", string $case = "i")
{
    if ((preg_match("/^[A-Za-zÑñ$espacios]{" . $min . "," . $max . "}$/u$case", sinTildes($text)))) {

        return true;
    }
    if($min>0 && $text==""){$errores[$campo]="$campo es obligatorio"; return false;}

    $errores[$campo] = "$campo sólo puede contener letras";
    return false;
}


/*función que valida el contenido de un textarea. Podemos seleccionar el número máximo de caracteres
 * 
 * 
*/
function cTextarea(string $text, string $campo, array &$errores, int $max = 300, int $min = 1)
{
    if ((mb_strlen($text) >=$min && mb_strlen($text)<=$max)) {
        
        return true;
    }
    $errores[$campo] = "El $campo permite de $min hasta $max caracteres";
    return false;
}

/*
 * Función que valida fechas.
 * Por defecto en formato dd-mm-aaa. Caso 1 mm/dd/aaaa. Caso 2 aaaa/mm/dd
 * Ponemos como caso por defecto el que utilice nuestro formulario
 * Permite separador / o -
 */
function cFecha(string $text, string $campo, array &$errores, string $formato = "0")
{
    $arrayFecha = preg_split("/[\/-]/", $text);
    
    if (count($arrayFecha) == 3) {
        switch ($formato) {
            case ("0"):
                return checkdate($arrayFecha[1], $arrayFecha[0], $arrayFecha[2]);
                break;

            case ("1"):
                return checkdate($arrayFecha[0], $arrayFecha[1], $arrayFecha[2]);
                break;

            case ("2"):
                return checkdate($arrayFecha[1], $arrayFecha[2], $arrayFecha[0]);
                break;
            default:
                $errores[$campo] = "El $campo tiene errores";
                return false;
        }
    } else {
        $errores[$campo] = "El $campo tiene errores";
        return false;
    }
}

function cDni(string $text, string $campo, array &$errores)
{
    $text = strtoupper($text);
    $regex = "/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/";
    if (preg_match($regex, $text))

        return cLetraDni($text);
    else {
        $errores[$campo] = "El $campo no es correcto";
        return false;
    }
}

function cNie(string $text, string $campo, array &$errores)
{
    $text = strtoupper($text);
    $regex = "/^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKE]$/";
    if (preg_match($regex, $text)) {
        switch ($text[0]) {

            case "X":
                $text[0] = 0;
                return cLetraDni($text);
                break;
            case "Y":
                $text[0] = 1;
                return cLetraDni($text);
                break;
            case "Z":
                $text[0] = 2;
                return cLetraDni($text);
                break;
            default:
                $errores[$campo] = "El $campo no es correcto";
                return false;
        }
    } else {
        $errores[$campo] = "El $campo no es correcto";
        return false;
    }
}

function cLetraDni(string $text)
{
    $letras = array(
        "T",
        "R",
        "W",
        "A",
        "G",
        "M",
        "Y",
        "F",
        "P",
        "D",
        "X",
        "B",
        "N",
        "J",
        "Z",
        "S",
        "Q",
        "V",
        "H",
        "L",
        "C",
        "K",
        "E"
    );
    $numeros = intval($text);
    $resto = $numeros % 23;
    return $letras[$resto] == $text[8] ? true : false;
}



