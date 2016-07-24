<?php
/**
 * Created by PhpStorm.
 * User: Musa ATALAY
 * Date: 19.08.2015
 * Time: 18:38
 */

namespace KargoAPIService\Library;


class APIXml{

    private $XmlHeader;
    private $XmlData = array();
    private $charset;
    private $toString;
    private $rendered = false;

    public function __construct(Array $Config = array()){

        $this->XmlHeader();

        return $this;

    }

    private function XmlHeader(){

        $this->XmlHeader = '';

    }

    public function pushArray(Array $Data){

        $this->XmlData = array_merge($this->XmlData, $Data);

        return $this;

    }

    public function charset($charset){

        return "This method still on developting";

        $this->charset = $charset;

        $this->XmlHeader();

    }

    public function render(){

        if($this->rendered){

            return $this->toString;

        }

        $this->toString = $this->parseXml();

        $this->rendered = true;

        return $this;

    }

    public function utf8_render(){

        $this->charset = 'utf-8';

        $this->XmlHeader();

        if($this->rendered){

            $this->toString = utf8_encode($this->toString);

            return $this->toString;

        }else{

            $this->toString = utf8_encode($this->parseXml());

        }

        $this->rendered = true;

        return $this;

    }

    public function html_encode(){

        if($this->rendered){

            $this->toString = htmlentities($this->toString);

            return $this->toString;

        }else{

            $this->toString = htmlentities($this->parseXml());

        }

        $this->rendered = true;

        return $this;

    }

    private function parseXml(Array $Data = null, $Heading = true){

        $ParsingData = $Data ? $Data : $this->XmlData;

        $Return = null;

        if($Heading){

            $Return = $this->XmlHeader;

        }

        foreach($ParsingData as $FieldName => $FieldValue){
            $Return .= "<" . $FieldName . ">";
            if(is_numeric($FieldValue)||is_string($FieldValue)){

                //$Return .= "<" . $FieldName . ">";

                $Return .= $FieldValue;

                //$Return .= "</" . $FieldName . ">";

            }elseif(is_array($FieldValue)){

                foreach($FieldValue as $FvIndex => $FvValue){

                    if(is_numeric($FvValue)||is_string($FvValue)){

                        $Return .= "<" . $FvIndex . ">";

                        $Return .= $FvValue;

                        $Return .= "</" . $FvIndex . ">";

                        /*if(is_numeric($FvIndex)||is_integer($FvIndex)){

                            $Return .= "<" . $FieldName . ">";

                            $Return .= $FvValue;

                            $Return .= "</" . $FieldName . ">";

                        }elseif(is_string($FvIndex)){

                            $Return .= "<" . $FvIndex . ">";

                            $Return .= $FvValue;

                            $Return .= "</" . $FvIndex . ">";

                        }*/

                    }elseif(is_array($FvValue)){

                        //$Return .= "<" . $FieldName . ">";

                        $Return .= $this->parseXml(array($FvIndex => $FvValue), false);

                        //$Return .= "</" . $FieldName . ">";

                    }

                }

            }
            $Return .= "</" . $FieldName . ">";
        }

        return $Return;

    }

    private function parseXmlCanceled(Array $Data = null, $Heading = true){

        return "This method isn´t available.";

        return $this->parseXml($Data, $Heading);

        $ParsingData = $Data ? $Data : $this->XmlData;

        $Return = null;

        if($Heading){

            //$Return = $this->XmlHeader;

        }

        foreach($ParsingData as $FieldName => $FieldValue){

            if(is_string($FieldName)){

                if(is_array($FieldValue)){

                    $Index = null;

                    foreach($FieldValue as $FvIndex => $FvValue){
                        $Index = $FvIndex;
                        break;
                    }

                    if(!is_numeric($Index)&&is_string($Index)){

                        $Return .= "<" . $FieldName . ">";

                        $Return .= $this->parseXml($FieldValue, false);

                        $Return .= "</" . $FieldName . ">";

                    }else if(is_numeric($Index)){

                        foreach($FieldValue as $FvIndex => $FvValue){

                            #$Return .= "<" . $FieldName . ">";

                            $Return .= $this->parseXml(array($FieldName => $FvValue), false);

                            #$Return .= "</" . $FieldName . ">";

                        }

                        //$Return .= $this->parseXml(array($FieldName => $FieldValue), false);

                    }

                }else if(is_string($FieldValue)||is_numeric($FieldValue)){

                    $Return .= "<" . $FieldName . ">" . $FieldValue . "</" . $FieldName . ">";

                }

            }

            /*if(is_string($FieldValue)){

                $Return .= "<" . $FieldName . ">" . $FieldValue . "</" . $FieldName . ">";


            }else if(is_array($FieldValue)){

                foreach($FieldValue as $ValueName => $ValueValue){

                    if(is_string($ValueValue)){

                        $Return .= "<" . $ValueName . ">";

                        if(is_array($ValueValue)){

                            $Return .= $this->pushArray($ValueValue, false);

                        }else{

                            $Return .= $ValueValue;

                        }

                        $Return .= "</" . $ValueName . ">";

                    }else if(is_numeric($ValueName)){

                        $Return .= $this->parseXml(array($FieldName => $ValueValue), false);

                    }

                }

            }*/

        }

        return $Return;

    }

    public function __set($property, $args){

        $this->pushArray(array($property => $args));

    }

    public function __call($function, Array $args){

        $args = count($args) <= 1 ? $args[0] : $args;

        $this->pushArray(array($function => $args));

    }

    public function __toString(){

        return $this->toString;

    }

}