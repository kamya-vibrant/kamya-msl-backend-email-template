<?php
header('Access-Control-Allow-Origin: *');

require_once 'scripts/script_config.php';

$logger = require 'service/logger.php';

$requestbody = file_get_contents("php://input");

if (isset($_REQUEST["model"])) {
    $logger->debug("request begin: model -> " . $_REQUEST["model"]);

    $modelClass = refineClass($_REQUEST["model"]) ?? null;
    if ($modelClass == 'Class') {
        $modelClass = 'Class_';
    }
    $method = trim($_REQUEST["action"]) ?? null;
    $response = [];

    $logger->debug("modelclass: " . $modelClass);

    if (!is_null($modelClass)) {
        if (file_exists("controllers/{$modelClass}Controller.php")) {
            include "controllers/{$modelClass}Controller.php";
            include "transformers/Query.php";
            $QueryTransformer = new QueryTransformer();
            $controller = "{$modelClass}Controller";
            $actionClass = new $controller();
            $frontend = $_REQUEST['referer'];
            $origin = getOriginUrl($_REQUEST['origin']);
            $logger->debug($controller . "::" . $method);

            switch ($modelClass) {
                case 'Auth':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'User':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Supplier':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Organiser':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Address':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Location':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Pantry':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'School':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Setting':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Unit':
                    $response = $actionClass->$method($_REQUEST);
                case 'Assoc':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Category':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Optionset':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Customer':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Student':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Finance':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Class_' /** What's this for? **/:
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Allergen':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Image' /** What's this for? **/:
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Ingredient':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Product':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Recipe':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Listing':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Sizing':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Option':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Addons':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Menu':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Mealdeal':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Booking':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Order':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Menu':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Fatzebra':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Purchaseorder':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'Channel':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                case 'EmailDelivery':
                    $response = $actionClass->$method($_REQUEST);
                    break;
                default:
                    $response = array('code'=>200, 'message'=>'Model not recognized.','data'=>array());
                    break;
            }
        }
    }

    echo json_encode($response);
} else {
    $logger->debug("Model is empty");
}

function getOriginUrl($origin)
{
    $parse = parse_url($origin);
    $port = isset($parse['port']) ? $parse['port'] : "";
    $origin = trim($port) == "" ? $parse['scheme'] . "://" . $parse['host'] . "/" : $parse['scheme'] . "://" . $parse['host'] . ":" . $parse['port'] . "/";

    return $origin;
}

function refineClass($model)
{
    $model = trim($model);
    if (str_contains($model, '_')) {
        $refine = explode("_", $model);
        $model = '';
        foreach ($refine as $key => $val) {
            $model .= ucfirst($val);
        }
    }
    return ucfirst($model);
}

?>
