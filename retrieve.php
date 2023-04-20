<?php

$zId        = ($argv[1]) ??  'M';
$year       = ($argv[2]) ??  date('Y');
$month      = ($argv[3]) ??  date('m');
$day        = ($argv[4]) ??  '01';

$zurnali = [
    'M' => (object) [
        'name'      => 'Medības',
        'folder'    => 'Medibas',
        'urlId'     => 'md',
        'pages'     => 84
    ],
    'LL' => (object) [
        'name'      => 'Lielais Loms',
        'folder'    => 'LielaisLoms',
        'urlId'     => 'll',
        'pages'     => 64
    ],
];

$zurnals = $zurnali[$zId];

$lppCount   = ($argv[5]) ?? $zurnals->pages;

if($day === '01')
{
    print("Žurnāls ".$zurnals->name."  ".$year."/".$month." \n");
}
else
{
    print("Žurnāls ".$zurnals->name." ".$year."/".$month." specizlaidums ".$day." \n");
}



print("Izveduma mape ".$zurnals->folder." \n");
if(is_dir($zurnals->folder))
{
    print("✅ Izdevuma mape jau eksistē \n");
}
else
{
    print("Izdevuma mapes nav, mēģinu izveidot \n");
    
    if(mkdir($zurnals->folder))
    {
        print("✅ Izdevuma mapes izveidota \n");
    }
    else
    {
        print("Izdevuma mapi nevar izveidot \n");
        die('❌');
    }
}



$yearDir = $zurnals->folder.DIRECTORY_SEPARATOR.$year;

print("Gada mape ".$yearDir." \n");
if(is_dir($yearDir))
{
    print("✅ Gada mape jau eksistē \n");
}
else
{
    print("Gada mapes nav, mēģinu izveidot \n");
    
    if(mkdir($yearDir))
    {
        print("✅ Gada mapes izveidota \n");
    }
    else
    {
        print("Gada mapi nevar izveidot \n");
        die('❌');
    }
}


$imgDir = $yearDir.DIRECTORY_SEPARATOR.'img';

print("Attēlu mape ".$imgDir." \n");
if(is_dir($imgDir))
{
    print("✅ Attēlu mape jau eksistē \n");
}
else
{
    print("Attēlu mapes nav, mēģinu izveidot \n");
    
    if(mkdir($imgDir))
    {
        print("✅ Attēlu mape izveidota \n");
    }
    else
    {
        print("Attēlu mapi nevar izveidot \n");
        die('❌');
    }
}

$monthDir = $imgDir.DIRECTORY_SEPARATOR.$month;

print("Mēneša mape ".$monthDir." \n");
if(is_dir($monthDir))
{
    print("✅ Mēneša mape jau eksistē \n");
}
else
{
    print("Mēneša mapes nav, mēģinu izveidot \n");
    
    if(mkdir($monthDir))
    {
        print("✅ Mēneša mape izveidota \n");
    }
    else
    {
        print("Mēneša mapi nevar izveidot \n");
        die('❌');
    }
}

$dayhDir = $monthDir.DIRECTORY_SEPARATOR.$day;

print("Dienas mape ".$dayhDir." \n");
if(is_dir($dayhDir))
{
    print("✅ Dienas mape jau eksistē \n");
}
else
{
    print("Dienas mapes nav, mēģinu izveidot \n");
    
    if(mkdir($dayhDir))
    {
        print("✅ Dienas mape izveidota \n");
    }
    else
    {
        print("Dienas mapi nevar izveidot \n");
        die('❌');
    }
}


print("Lappušu skaits ".$lppCount." \n \n");

$convertTxt = 'convert ';

for($i = 1; $i <= $lppCount; $i++)
{
    
    print("Lappuse ".$i." \n");
    
    $lppLocalPath = $dayhDir.DIRECTORY_SEPARATOR.$i.'.jpg';
    
    if(is_file($lppLocalPath))
    {
        print("✅ Lappuse jau ir arhīvā \n");
        $convertTxt .= ' '.$lppLocalPath;
        continue;
    }
    else
    {
        $remoteUrl = 'https://mod.latvijasmediji.lv/avizes/'.$zurnals->urlId.'/'.$year.'/'.$month.'/'.$day.'/'.$i.'l.jpg';
        print("Iegūstu lappusi ".$remoteUrl." \n");
        if(copy($remoteUrl, $lppLocalPath))
        {
            print("✅ Lappuse iegūta \n");
            $convertTxt .= ' '.$lppLocalPath;
        }
        else
        {
            print("❌❌❌ Lappusi nevar nolādēt! \n");
        }
        
        /*
        $secs = rand(2,5);
        print("\n");
        print("🕑 Uzgaidam ".$secs." sekundes \n");
        sleep($secs);*/
    }
    
    print("\n");
    
}

$pdfName = $yearDir.DIRECTORY_SEPARATOR.$month.'_'.$day.'.pdf';
if(!is_file($pdfName))
{
    print("Veidojam PDF ".$pdfName." \n");
    $convertTxt .= ' '.$pdfName;
    $a = exec($convertTxt);
    
    if(is_file($pdfName))
    {
        print("✅ PDF jābūt izveidotam \n");
    }
    else
    {
        print("❌ Iespe'jams kautkas nogāja greizi... \n");
    }
}
else
{
    print("✅ PDF jau ir bijis uzģenerēts \n");
}
