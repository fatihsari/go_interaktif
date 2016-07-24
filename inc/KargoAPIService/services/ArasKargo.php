<?php
/**
 * Created by PhpStorm.
 * User: Musa ATALAY
 * Date: 7.08.2015
 * Time: 14:48
 */

namespace KargoAPIService\Service;

use \KargoAPIService\Library;

class ArasCargo{

    private $Configuration;
    private $Host, $Target, $Port, $Timeout = 60;
    private $SoapSocket, $SoapData;
    private $WebServices = false;

    public function __construct(\KargoAPIService\Library\Configuration $Configuration){

        $this->Configuration = $Configuration;

        $this->Host = $Configuration->host();
        $this->Target = $Configuration->target();
        $this->port = $Configuration->port();

        $this->SoapData = array(
            "userName"  => $Configuration->username(),
            "password"  => $Configuration->password()
        );

        try{

            $this->SoapSocket = new Library\CSoap(array(
                "host" => $this->Configuration->host(),
                "port" => $this->Configuration->port(),
                "timeout" => $this->Configuration->timeout()
            ));

            $this->SoapSocket->post($this->Configuration->target());

            $this->SoapSocket->curlSet(CURLOPT_CONNECTTIMEOUT, $this->Configuration->timeout());
            $this->SoapSocket->curlSet(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        }catch (Library\Exception $e){

            return $e;

            //echo $e->getReturn();

            //echo "\n\r\n\r";

            //echo $e->getMessage();

        }

        return $this;

    }

    public function postCargo(Array $Data){

        try{

            $this->SoapSocket->curlSet(CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8', 'SOAPAction: "http://tempuri.org/SetDispatch"'));

            $SoapRaw = new Library\APISoap;

            $this->SoapData = array(
                "UserName" => $this->Configuration->username(),
                "Password" => $this->Configuration->password(),
                "CargoKey" => "0000",
                "InvoiceKey" => "0000",
                "ReceiverCustName" => "0000",
                "ReceiverAddress" => "0000",
                "ReceiverPhone1" => "0000",
                "ReceiverPhone2" => "0000",
                "ReceiverPhone3" => "0000",
                "CityName" => "0000",
                "TownName" => "0000",
                "CustProdId" => "0000",
                "Desi" => "0000",
                "Kg" => "0000",
                "CargoCount" => "0000",
                "WaybillNo" => "0000",
                "SpecialField1" => "0000",
                "SpecialField2" => "0000",
                "SpecialField3" => "0000",
                "TtInvoiceAmount" => "0000",
                "TtCollectionType" => "0000",
                "TtDocumentSaveType" => "0000",
                "OrgReceiverCustId" => "0000",
                "Description" => "0000",
                "TaxNumber" => "0000",
                "TtDocumentId" => "0000",
                "TaxOfficeId" => "0000",
                "OrgGeoCode" => "0000",
                "PrivilegeOrder" => "0000",
                "LovPayortypeID" => "0000",
                "UnitID" => "0000",
                "AuthorizedPersonName" => "0000",
                "AuthorizedPersonPhone" => "0000",
                "AuthorizedPersonMobile" => "0000",
                "RegionNumber" => "0000",
                "IsExchangedOrder" => "0000"
            );

            $SoapRaw->SetDispatch(array(
                "attr" => array("xmlns" => "http://tempuri.org/"),
                "value" => array(
                    "shippingOrders"  => array(
                        "ShippingOrder" => array(
                            "value" => array_merge($this->SoapData, $Data)
                        )
                    ),
                    "userName"  => $this->Configuration->username(),
                    "password"  => $this->Configuration->password()
                )
            ));

            $this->SoapSocket->pushRaw($SoapRaw->render());

            $this->SoapSocket->execute();

            $this->SoapSocket->ns("http://tempuri.org/");

            $FetchResult = $this->SoapSocket->fetchObject(array(
                "xpath" => "//ns1:SetDispatchResponse/ns1:SetDispatchResult/ns1:DispatchResultInfo"
            ));

            return $FetchResult;

        }catch (Library\Exception $e){

            return $e;

            //echo $e->getReturn();

            //echo "\n\r\n\r";

            //echo $e->getMessage();

        }

    }

    public function getCargo(Array $Data){

        try{

            $this->SoapSocket->curlSet(CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8', 'SOAPAction: "http://tempuri.org/GetDispatchWithIntegrationCode"'));

            $SoapRaw = new Library\APISoap;

            $SoapRaw->GetDispatchWithIntegrationCode(array(
                "attr" => array("xmlns" => "http://tempuri.org/"),
                "value" => array_merge($this->SoapData, $Data)
            ));

            $this->SoapSocket->pushRaw($SoapRaw->render());

            $this->SoapSocket->execute();

            $this->SoapSocket->ns("http://tempuri.org/");

            $FetchResult = $this->SoapSocket->fetchObject(array(
                "xpath" => "//ns1:GetDispatchWithIntegrationCodeResponse/ns1:GetDispatchWithIntegrationCodeResult/ns1:ShippingOrder"
            ));

            return $FetchResult;

        }catch (Library\Exception $e){

            return $e;

            //echo $e->getReturn();

            //echo "\n\r\n\r";

            //echo $e->getMessage();

        }

    }

    public function fetchCargo(Array $Data){

        try{

            $this->SoapSocket->curlSet(CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8', 'SOAPAction: "http://tempuri.org/GetDispatch"'));

            $SoapRaw = new Library\APISoap;

            $SoapRaw->GetDispatch(array(
                "attr" => array("xmlns" => "http://tempuri.org/"),
                "value" => array_merge($this->SoapData, $Data)
            ));

            $this->SoapSocket->pushRaw($SoapRaw->utf8());

            $this->SoapSocket->execute();

            $this->SoapSocket->ns("http://tempuri.org/");

            $FetchResult = $this->SoapSocket->fetchObject(array(
                "xpath" => "//ns1:GetDispatchResponse/ns1:GetDispatchResult/ns1:ShippingOrder"
            ));

            return $FetchResult;

        }catch (Library\Exception $e){

            return $e;

            //echo $e->getReturn();

            //echo "\n\r\n\r";

            //echo $e->getMessage();

        }

    }

    public function cancelCargo(Array $Data){

        try{

            $this->SoapSocket->curlSet(CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8', 'SOAPAction: "http://tempuri.org/CancelDispatch"'));

            $SoapRaw = new Library\APISoap;

            $SoapRaw->CancelDispatch(array(
                "attr" => array("xmlns" => "http://tempuri.org/"),
                "value" => array_merge($this->SoapData, $Data)
            ));

            $this->SoapSocket->pushRaw($SoapRaw->render());

            $this->SoapSocket->execute();

            $this->SoapSocket->ns("http://tempuri.org/");

            $FetchResult = $this->SoapSocket->fetchObject(array(
                "xpath" => "//ns1:CancelDispatchResponse/ns1:CancelDispatchResult"
            ));

            return $FetchResult;

        }catch (Library\Exception $e){

            return $e;

            //echo $e->getReturn();

            //echo "\n\r\n\r";

            //echo $e->getMessage();

        }

    }

    public function WebServices($Type = "JSON"){

        $ResponseType = $this->Configuration->WebServicesType() ? $this->Configuration->WebServicesType() : $Type;

        if($this->WebServices===false){

            $this->WebServices = new ArasWebServices($ResponseType, $this->Configuration);

        }

        return $this->WebServices;

    }

}

class ArasWebServices{

    private $SoapData;
    private $ResponseType;
    private $SoapSocket;
    private $Handler;

    public function __construct($Type = "JSON", $Conf){

        $this->Handler = new ArasCargoHandler;

        $XmlRaw = new Library\APIXml;

        $XmlRaw->pushArray(array(
            "LoginInfo" => array(
                "value" => array(
                    "UserName" => $Conf->username(),
                    "Password" => $Conf->password(),
                    "CustomerCode" => $Conf->customerKey()
                )
            )
        ));

        $this->SoapData = array(
            "loginInfo" => $XmlRaw->html_encode()->render()
        );

        $this->ResponseType = $Type;

        try{

            $this->SoapSocket = new Library\CSoap(array(
                "host" => $Conf->host(),
                "port" => $Conf->port(),
                "timeout" => $Conf->timeout()
            ));

            $this->SoapSocket->post($Conf->target());

            $this->SoapSocket->curlSet(CURLOPT_CONNECTTIMEOUT, $Conf->timeout());
            $this->SoapSocket->curlSet(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        }catch (Library\Exception $e){

            return $e;

        }

        return $this;

    }

    public function push(Array $Data){

        $XmlRaw = new Library\APIXml;

        $XmlRaw->pushArray(array(
            "QueryInfo" => $Data
        ));

        $MergeData = array("queryInfo" => $XmlRaw->render()->html_encode());

        $this->SoapData = array_merge($this->SoapData, $MergeData);

        return $this;

    }

    public function exec(){

        switch(strtolower($this->ResponseType)){

            case "json":
                return $this->GetQueryJSON();;
                break;
            case "xml":
                return $this->GetQueryXML();
                break;
            case "ds":
                return $this->GetQueryDS();
                break;
            case "string":
                return $this->GetQueryDS();
                break;
            default:
                return $this->GetQueryJSON();

        }

    }

    private function GetQueryJSON(Array $Data = null){

        $PostData = $this->SoapData;

        if($Data&&count($Data)>=1){

            $XmlRaw = new Library\APIXml;

            $XmlRaw->pushArray(array(
                "QueryInfo" => $Data
            ));

            $MergeData = array("queryInfo" => $XmlRaw->render()->html_encode());

            $PostData = array_merge($PostData, $MergeData);

        }

        try{

            $this->SoapSocket->curlSet(CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8', 'SOAPAction: "http://tempuri.org/IArasCargoIntegrationService/GetQueryJSON"'));

            $SoapRaw = new Library\APISoap;

            $SoapRaw->GetQueryJSON(array(
                "attr" => array("xmlns" => "http://tempuri.org/"),
                "value" => $PostData
            ));

            $this->SoapSocket->pushRaw($SoapRaw->render());

            $this->SoapSocket->execute();

            //return $this->SoapSocket->responseText();

            $this->SoapSocket->ns("http://tempuri.org/");

            $FetchResult = $this->SoapSocket->fetchObject(array(
                "xpath" => "//ns1:GetQueryJSONResponse /ns1:GetQueryJSONResult"
            ));

            $Object = json_decode((string) $FetchResult[0][0])->QueryResult;

            if($Object === null){

                return $Object;

            }

            /**
             * returns Cargo Handler Object as ArasCargoHandler instance
             */
            return $this->Handler->dataSet($Object);

            /**
             * returns Cargo DataSet Object as stdClass instance
             */
            return json_decode((string) $FetchResult[0][0])->QueryResult;

        }catch (Library\Exception $e){

            return $e;

        }

        return $this;

    }

    private function GetQueryXML(Array $Data = null){

        $PostData = $this->SoapData;

        if($Data&&count($Data)>=1){

            $PostData = array_merge($PostData, $Data);

        }

        try{

            $this->SoapSocket->curlSet(CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8', 'SOAPAction: "http://tempuri.org/IArasCargoIntegrationService/GetQueryXML"'));

            $SoapRaw = new Library\APISoap;

            $SoapRaw->GetQueryXML(array(
                "attr" => array("xmlns" => "http://tempuri.org/"),
                "value" => $PostData
            ));
            //header("Content-Type: application/xml; charset=utf8;");
            //exit($SoapRaw->render());

            //$this->SoapSocket->pushRaw($SoapRaw->render());

            /*$xml_build = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
            $xml_build = "<GetQueryXML>";
            $xml_build .= "<LoginInfo><UserName>tibetpazarlama</UserName><Password>sampo1202</Password><CustomerCode>1514941931551</CustomerCode></LoginInfo>";
            $xml_build .= "<QueryInfo><QueryType>1</QueryType><IntegrationCode>58254</IntegrationCode></QueryInfo>";
            $xml_build .= "</GetQueryXML>";*/

            //$XmlRaw = new Library\APIXml;
            /*$XmlRaw->pushArray(array(
                "GetQueryJSON" => array(
                    "LoginInfo" => array(
                        "UserName" => "tibetpazarlama",
                        "Password" => "sampo1202",
                        "CustomerCode" => "1514941931551"
                    ),
                    "QueryInfo" => array(
                        "QueryType" => "1",
                        "IntegrationCode" => "58254"
                    )
                )
            ));*/
            /*$XmlRaw->pushArray(array(
                "GetQueryXML" => array(
                    "loginInfo" => "<LoginInfo><UserName>tibetpazarlama</UserName><Password>sampo1202</Password><CustomerCode>1514941931551</CustomerCode></LoginInfo>",
                    "queryInfo" => "<QueryInfo><QueryType>1</QueryType><IntegrationCode>58254</IntegrationCode></QueryInfo>"
                )
            ));*/
            /*header("Content-Type: application/xml; charset=utf8;");
            exit($XmlRaw->render());*/

            $this->SoapSocket->pushRaw($SoapRaw->render());

            $this->SoapSocket->execute();

            //return $this->SoapSocket->responseText();

            $this->SoapSocket->ns("http://tempuri.org/");

            $FetchResult = $this->SoapSocket->fetchObject(array(
                "xpath" => "//ns1:GetQueryXMLResponse /ns1:GetQueryXMLResult"
            ));

            return $FetchResult;

        }catch (Library\Exception $e){

            return $e;

            //echo $e->getReturn();

            //echo "\n\r\n\r";

            //echo $e->getMessage();

        }

        return $this;

    }

    private function GetQueryDS(Array $Data = null){

        return "@This method still on developting.";

        $this->SoapSocket->curlSet(CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8', 'SOAPAction: "http://tempuri.org/GetQueryJSON"'));

        return $this;

    }

}

class ArasCargoHandler{

    private $Stack;
    private $DataSet;
    private $Transaction;
    private $TypeCodes = array(
        0 => null,
        1 => ":Normal",
        2 => ":Yönlendirildi",
        3 => ":İade edildi"
    );
    private $StatusCodes = array(
        0 => null,
        1 => "Çıkış Şubesinde",
        2 => "Yolda",
        3 => "Teslimat Şubesinde",
        4 => "Teslimatta",
        5 => "Parçalı Teslimat",
        6 => "Teslim Edildi",
        7 => "Yönlendirildi"
    );

    public function __construct(\stdClass $DataSet = null){

        if($DataSet != false){

            return $this->dataSet($DataSet);

        }

        return $this;

    }

    public function dataSet(\stdClass $DataSet){

        $this->Stack = (object) ((array) $DataSet);

        $this->DataSet = $DataSet;

        if(@is_array($this->DataSet->Cargo)){

            $this->DataSet->Cargo = $this->DataSet->Cargo[count($this->DataSet->Cargo)-1];

        }

        /*if(@is_array($this->Stack->CargoTransaction)){

            $this->Transaction = $this->Stack->CargoTransaction[count($this->Stack->CargoTransaction)-1];

        }*/

        return $this;

    }


    /**
     * @Cargo
     */

    public function status(){

        $Status = new \stdClass;

        @$Status->code = $this->DataSet->Cargo->DURUM_KODU;
        @$Status->statusType = $this->StatusCodes[$this->DataSet->Cargo->DURUM_KODU];
        @$Status->type = $this->StatusCodes[$this->DataSet->Cargo->DURUM_KODU];
        @$Status->status = $this->DataSet->Cargo->DURUMU;

        return $Status;

    }

    public function trackNo(){

        return $this->DataSet->Cargo->KARGO_TAKIP_NO;

    }

    public function type(){

        $Type = new \stdClass;

        @$Type->code = $this->DataSet->Cargo->TIP_KODU;
        @$Type->status = $this->TypeCodes[$this->DataSet->Cargo->TIP_KODU];

        return $Type;

    }

    public function out(){

        $Out = new \stdClass;

        @$Out->branch = $this->DataSet->Cargo->CIKIS_SUBE;

        @$Out->branch = $this->DataSet->Cargo->CIKIS_TARIH;

        return $Out;

    }

    public function delivery(){

        $Delivery = new \stdClass;

        @$Delivery->name = $this->DataSet->Cargo->TESLIM_ALAN ;

        @$Delivery->date = $this->DataSet->Cargo->TESLIM_TARIHI;

        @$Delivery->time = $this->DataSet->Cargo->TESLIM_SAATI;

        return $Delivery;

    }

    public function isDelivered(){

        if($this->DataSet->Cargo->DURUM_KODU === 6 && $this->DataSet->Cargo->TIP_KODU != 3){

            return true;

        }

        return false;

    }

    public function isExtradited(){

        if($this->DataSet->Cargo->DURUM_KODU === 7 && $this->DataSet->Cargo->TIP_KODU === 3){

            return true;

        }else if($this->DataSet->Cargo->TIP_KODU === 3){

            return true;

        }

        return false;

    }

    public function extradition(){

        return $this->DataSet->Cargo->IADE_SEBEBI;

    }


    /**
     * @CargoTransaction
     */

    public function transactions(){

        return $this->Stack->CargoTransaction;

    }

    public function transaction(){

        /*$render = $this->renderArray();

        $Transactions = $render["CargoTransaction"];*/
        $render = $this->renderObject();

        $Transactions = $render->CargoTransaction;

        $return = null;

        if(@isset($Transactions["ISLEM_TARIHI"])){

            $Tr = new \stdClass();

            foreach($Transactions AS $var => $value){

                @$Tr->$var = $value->scalar;

            }

            $return = $Tr;

        }else{

            $return = $Transactions[count($Transactions)-1];

        }

        return $return;

    }

    public function filter(Array $filters, $Strict = false){

        $SearchIn = $this->renderArray();

        $Found = array();

        foreach($SearchIn["CargoTransaction"] AS $Transaction){

            $f = false;

            $continue = false;

            foreach($filters AS $column => $filter){

                if($continue){

                    continue;

                }

                if(is_numeric($column)&&in_array($filter, $Transaction)){

                    $f = true;

                }else if(!is_numeric($column)&&$Transaction[$column]==$filter){

                    $f = true;

                }else{

                    if($Strict===true){

                        $f = false;

                        $continue = true;

                        break;

                    }

                }

            }

            if($f){

                $Found[] = $Transaction;

            }

        }

        return $Found;

    }

    public function search(Array $filters, $Strict = false){

        return $this->filter($filters, $Strict);

    }


    /**
     * @CargoTransactionDetails
     */

    public function branch(){

        $transaction = $this->transaction();

        return $transaction->BIRIM;

    }

    public function process(){

        $transaction = $this->transaction();

        return $transaction->ISLEM;

    }

    public function date($format = "Y-m-d H:i:s", $options = null){

        $return = null;

        $transaction = $this->transaction();

        $strTime = $transaction->ISLEM_TARIHI;

        $return = date($format, strtotime($strTime));

        if($options == null){

            $return = date($format, strtotime($strTime));

        }else{

            if(is_numeric($options)){

                $return = date($format, strtotime($strTime.", ".($options/1000)." seconds"));

            }else if(!is_numeric($options)&&is_string($options)){

                $return = date($format, strtotime($strTime.", ".$options));

            }else if(is_array($options)){

                if(count($options)==1){

                    if(is_numeric($options[0])){

                        $return = date($format, strtotime($strTime.", ".($options[0]/1000)." seconds"));

                    }else if(!is_numeric($options[0])&&is_string($options[0])){

                        $return = date($format, strtotime($strTime.", ".$options[0]));

                    }

                }else{

                    $opt = null;

                    foreach($options AS $index => $option){

                        $opt .= $option . $index .", ";

                    }

                    $opt = rtrim($opt, ", ");

                    $return = date($format, strtotime($strTime.", ".$opt));

                }

            }

        }

        return $return;

    }

    public function description(){

        $transaction = $this->transaction();

        return $transaction->ACIKLAMA;

    }

    /**
     * @GeneralMethods
     */

    public function renderArray(){

        return json_decode(json_encode($this->Stack), true);

    }

    public function renderObject(){

        foreach($this->Stack AS $var => $Stack){

            $return = array();

            foreach($Stack AS $key => $Cargo){

                $return[$key] = (object) $Cargo;

            }

            $this->Stack->$var = $return;

        }

        return $this->Stack;

    }

}