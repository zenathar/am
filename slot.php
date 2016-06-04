<?php
header('Content-Type: image/jpeg;charset=utf-8');
$options['features'] = SOAP_SINGLE_ELEMENT_ARRAYS;
$soap = new SoapClient('https://webapi.allegro.pl/service.php?wsdl', $options);
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
   'resultSize' => 100,
   'resultOffset' => 0,
   'resultScope' => 0
);
$itemsResponse=$soap->doGetItemsList($dogetitemslist_request);


$off=$_GET["of"];
$i=$_GET["it"];
$parzystosc=$off*25+$i;
if((floor($parzystosc%2)%2)==0)
    $flagap=1;
else
    $flagap=0;
$items=$itemsResponse->itemsList->item;

            $cena= $items[$i]->priceInfo->item[0]->priceValue;
            $formatted = sprintf("%01.2f", $cena);
            

            $czas_js=$items[$i]->endingTime;
            $czaskonca=$items[$i]->endingTime;
            $czas = strtotime($czaskonca)-time();
            $dni=floor($czas/86400);
            $czas=$czas%86400;
            $godziny=floor($czas/3600);
            $czas=$czas%3600;
            $minuty=floor($czas/60);
            $czas=$czas%60;
            $koncowka=" dni ";
            
            if($dni==1)
                $koncowka=" dzień ";
            if(empty($czas_js))
              $czas="Do wyczerpania";
            else
              $czas="Pozostało: ".$dni.$koncowka.$godziny." h";
			
function LoadJpeg($color,$imgname,$name,$price,$time)
{
    /* Attempt to open */
        $im = imagecreatetruecolor(180,210);
        $pr=imagecreate(50, 50);
        imagefilledrectangle($pr, 0, 0, 90, 15, 0xEDE8E9);
        $bgc = imagecolorallocate($im, 200, 255, 255);
        $tc = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 200, 210, $color);
    $im2=imagecreatefromjpeg(utf8_decode($imgname)); 
   
    imagecopy($im,$im2,26,75,0,0,128,96);
    imagecopymerge($im, $pr, 60, 155, 0, 0, 90, 15, 40);
    $new=wordwrap($name,20,"\n");
   
    ImageTTFText($im, 11, 0, 26, 20, 0, './times.ttf', $new);
    ImageTTFText($im, 11, 0, 60, 165, 0xFFFFFF, './timesbd.ttf', $price);
    ImageTTFText($im, 11, 0, 26, 190, 0x000000, './timesbd.ttf', $time);
    /* See if it failed */
    if(!$im)
    {
         echo "Blad";
        /* Create a blank image */
        $im = imagecreatetruecolor(200, 50);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 200, 50, $bgc);
        
    }

    return $im;
}


if($flagap==0)
    $kolor=0x36620D;
else
    $kolor=0x629A15;
$img = LoadJpeg($kolor,$items[$i]->photosInfo->item[1]->photoUrl,$items[$i]->itemTitle,'Tylko '.$formatted.' zł!',$czas);
imagejpeg($img);

?>
