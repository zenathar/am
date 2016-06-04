<?php
$options['features'] = SOAP_SINGLE_ELEMENT_ARRAYS;
$soap = new SoapClient('https://webapi.allegro.pl/service.php?wsdl', $options);
$off=$_GET["of"];
$i=$_GET["it"];
//pobranie przedmiotĂłw uĹĽytkownika
$dogetuserid_request = array(
   'countryId' => 1,
   'userLogin' => 'Amigo2003',
   'userEmail' => '',
   'webapiKey' => '980bfcf40c'
);

$userIdResponse=$soap->doGetUserID($dogetuserid_request);
$userId=$userIdResponse->userId;
$dogetitemslist_request = array(
   'webapiKey' => '980bfcf40c',
   'countryId' => 1,
   'filterOptions' => array(
          array( // tutaj ladujesz filtry dla przykladu aukcje uzytkownika
                      'filterId' => 'userId',
                      'filterValueId' => array($userId)
                      ),
          ),
   'resultSize' => 25,
   'resultOffset' => $off,
   'resultScope' => 0
);
$itemsResponse=$soap->doGetItemsList($dogetitemslist_request);
$items=$itemsResponse->itemsList->item;
$location=$items[$i]->itemId;
header('Location: http://allegro.pl/show_item.php?item='.urlencode($location) );



?>
