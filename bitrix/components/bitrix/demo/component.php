<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("webservice") || !CModule::IncludeModule("iblock"))
    return;

// наш новый класс наследуется от базового IWebService
class CAddNewsWS extends IWebService
{
    function AddNews($NAME)
    {

        $client = new SoapClient("http://www.xmethods.net/sd/2001/CurrencyExchangeService.wsdl");

        $result = $client->getRate("us", "russia");

        if($response->isFault()){
            return new CSOAPFault( 'Server Error', 'Error: '.$response->faultCode(). ' - ' . $response->faultString() . '<br />'  );
        }else{
            return Array("id"=>'Текущий курс доллара: '. $result. ' рублей');
        }


        //
    }
    // метод GetWebServiceDesc возвращает описание сервиса и его методов
    function GetWebServiceDesc()
    {
        $wsdesc = new CWebServiceDesc();
        $wsdesc->wsname = "bitrix.webservice.addnews"; // название сервиса
        $wsdesc->wsclassname = "CAddNewsWS"; // название класса
        $wsdesc->wsdlauto = true;
        $wsdesc->wsendpoint = CWebService::GetDefaultEndpoint();
        $wsdesc->wstargetns = CWebService::GetDefaultTargetNS();

        $wsdesc->classTypes = array();
        $wsdesc->structTypes = Array();
        $wsdesc->classes = array(
            "CAddNewsWS"=> array(
                "AddNews" => array(
                    "type"      => "public",
                    "input"      => array(
                        "NAME" => array("varType" => "string"),
                    ),
                    "output"   => array(
                        "id" => array("varType" => "integer")
                    ),
                    "httpauth" => "Y"
                ),
            )
        );

        return $wsdesc;
    }
}

$arParams["WEBSERVICE_NAME"] = "bitrix.webservice.addnews";
$arParams["WEBSERVICE_CLASS"] = "CAddNewsWS";
$arParams["WEBSERVICE_MODULE"] = "";

// передаем в компонент описание веб-сервиса
$APPLICATION->IncludeComponent(
    "bitrix:webservice.server",
    "",
    $arParams
);

die();
?>