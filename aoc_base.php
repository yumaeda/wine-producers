<?php

$curDirPath = dirname(__FILE__);
require_once("$curDirPath/functions.php");
require_once("$curDirPath/../../includes/defines.php");
require("$curDirPath/../includes/config.inc.php");
require(MYSQL);

$strJpnCountry  = $rgobjCountry[$strCountry];
$strJpnRegion   = $rgobjRegion[$strRegion];
$strJpnDistrict = $strDistrict ? $rgobjDistrict[$strDistrict] : '';
$strJpnVillage  = $strVillage  ? $rgobjVillage[$strVillage]   : '';

function generateHeaderInnerHtml()
{
    global $strCountry, $strJpnCountry, $strRegion, $strJpnRegion, $strDistrict, $strJpnDistrict, $strVillage, $strJpnVillage;

    $strCountryDir  = generateFolderName($strCountry, $strCountry);
    $strRegionDir   = generateFolderName($strRegion, $strCountry);
    $strDistrictDir = generateFolderName($strDistrict, $strCountry);

    $html = '
        <ul>
            <li><a href="//anyway-grapes.jp/producers"><img src="//anyway-grapes.jp/producers/images/home.png" alt="Home" style="width:25px;" /></a></li>
            <li><span class="arrow-span">&gt;</li>
            <li><a href="//anyway-grapes.jp/producers/' . $strCountryDir . '"><span class="jpnText">' . $strJpnCountry . '</span></a></li>';

    $fHasDistrict = !empty($strDistrict);
    $fHasVillage  = !empty($strVillage);
    if (!empty($strRegion))
    {
        $html .= '<li><span class="arrow-span">&gt;</li>';
        if ($fHasDistrict)
        {
            $html .= '
                <li><a href="//anyway-grapes.jp/producers/' . $strCountryDir . '/' . $strRegionDir . '"><span class="jpnText">' . $strJpnRegion . '</span></a></li>';
        }
        else
        {
            $html .= '
                <li><strong><span class="jpnText">' . $strJpnRegion . '</span></strong></li>';
        }
    }

    if ($fHasDistrict)
    {
        $html .= '<li><span class="arrow-span">&gt;</li>';
        if ($fHasVillage)
        {
            $html .= ' 
                <li><a href="//anyway-grapes.jp/producers/' . $strCountryDir . '/' . $strRegionDir . '/' . $strDistrictDir . '"><span class="jpnText">' . $strJpnDistrict . '</span></a></li>';
        }
        else
        {
            $html .= '
                <li><strong><span class="jpnText">' . $strJpnDistrict . '</span></strong></li>';
        }
    }

    if ($fHasVillage)
    {
        $html .= ' 
                <li><span class="arrow-span">&gt;</li>
                <li><strong><span class="jpnText">' . $strJpnVillage . '</span></strong></li>';
    }

    $html .= '</ul>';

    return $html;
}

function generateBodyHtml()
{
    global $dbc, $strCountry, $strRegion, $strDistrict, $strVillage;

    $html = '';

    $sqlRegion   = mysqli_real_escape_string($dbc, $strRegion);
    $sqlDistrict = mysqli_real_escape_string($dbc, $strDistrict);
    $sqlVillage  = mysqli_real_escape_string($dbc, $strVillage);
    $result = mysqli_query($dbc, "CALL get_html_page('$strCountry', '$sqlRegion', '$sqlDistrict', '$sqlVillage')");
    if (($result !== FALSE) && (mysqli_num_rows($result) === 1))
    {
        $row  = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $html = $row['contents'];
    }

    return $html;
}

function generateProducerListHtml()
{
    global $dbc, $strCountry, $strRegion, $strJpnRegion, $strDistrict, $strJpnDistrict, $strVillage, $strJpnVillage;

    $html = '<ul class="link-list">';

    $sqlRegion   = mysqli_real_escape_string($dbc, $strRegion);
    $sqlDistrict = mysqli_real_escape_string($dbc, $strDistrict);
    $sqlVillage  = mysqli_real_escape_string($dbc, $strVillage);
    $result = mysqli_query($dbc, "CALL get_producers_by_aoc('$sqlRegion', '$sqlDistrict', '$sqlVillage')");
    if ($result !== FALSE)
    {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $strProducer    = $row['name'];
            $strJpnProducer = $row['name_jpn'];
            $strProducerDir = generateFolderName($row['short_name'], $strCountry);

            $html .= "<li><a href=\"./$strProducerDir/index.php\"><span>$strJpnProducer</span>&nbsp;($strProducer)</a></li>";
        }
    }

    $html .= '</ul>';

    return $html;
}

$jpnTitle = $strJpnVillage;
$engTitle = $strVillage;
if (!$engTitle)
{
    $engTitle = $strDistrict;
    $jpnTitle = $strJpnDistrict;
    if (!$engTitle)
    {
        $engTitle = $strRegion;
        $jpnTitle = $strJpnRegion;
    }
}

$strVillageDir   = generateFolderName($engTitle, $strCountry);
$headerInnerHtml = generateHeaderInnerHtml();
$bodyHtml        = generateBodyHtml();

prepareNextQuery($dbc);
$producerListHtml = generateProducerListHtml();

echo '
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
        <title>' . $engTitle . ' / ' . $jpnTitle . '</title>
        <meta name="viewport" content="width=device-width, user-scalable=no" />
        <meta name="author" content="Conceptual Wine Boutique Anyway-Grapes">
        <script type="text/javascript">

        document.createElement("header");
        document.createElement("footer");

        </script>
        <style type="text/css">

        * { padding: 0; margin: 0; }

        body
        {
            font-family: "メイリオ", "ヒラギノ角ゴ Pro W3", "MS PGothic", "MS UI Gothic", Helvetica, Arial, sans-serif;
            font-size: 15px;

            color: rgb(80, 80, 80);
            background-color: white;
        }

        div.container
        {
            width: 950px;
            margin-left: auto;
            margin-right: auto;
        }

        header
        {
            font-size: 12px;
            padding-top: 15px;
            padding-bottom: 15px;
        }

        div.contents { margin-top: 15px; }

        header, div.contents, footer { width: 100%; }

        h2, h3 { margin-bottom: 15px; }

        table
        {
            font-size: 14px;
            border-collapse: collapse;
            width: 100%;
        }

        td
        {
            border: 1px solid rgb(224, 224, 224);
            padding: 10px;
        }

        p { padding-bottom: 15px; line-height: 150%; }

        td.label-td
        {
            width: 100px;
            color: slategrey;
            background-color: snow;
        }

        tfoot tr td { text-align: center; }

        header ul li { display: inline; }

        div.contents ul { list-style-type: none; }

        div.contents h2 { font-size: 18px; }

        footer img.shopLogo
        {
            padding: 10px;
            width: 125px;
            border: 0;
            float: right;
        }

        div.imagePane { height: 238px; margin-bottom: 30px; }

        div.imagePane img
        {
            border: none;
            width: 33.3%;

            float: left;
        }

        img.clickable-img:hover { cursor: pointer; opacity: 0.5; }

        ul.link-list
        {
            background-color: snow;
            border: 1px solid rgb(224,224,224);
            
            padding: 15px;
        }

        ul.link-list > li { padding-bottom: 15px; }

        ul.link-list li a { color: slategrey; }

        ul.vintage-switcher
        {
            margin-top: 30px;

            height: 50px;
            list-style-type: none;
            border: 1px solid rgb(224,224,224);
            background-color: lavender;
        }

        ul.vintage-switcher a
        {
            font-size: 18px;
            text-decoration: none;
            color: slategrey;
        }

        ul.vintage-switcher a:hover { color: darkred; }

        .engLargeText
        {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 22px;
        }

        .engMediumText
        {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 18px;
        }

        .arrow-span { padding: 0 15px 0 15px; }

        /* Facebook Button*/
        div.fb-like { display: inline; padding: 0 10px 0 10px; }

        /* Facebook Button*/
        div.fb-like span { vertical-align: top !important; }

        /* LINE Button */
        a.line-share-button img { height: 20px; vertical-align: top; }

        /* Twitter Button */
        iframe#twitter-widget-0, iframe.twitter-share-button { vertical-align: top; }

        a.favoriteLnk
        {
            color: white;
            background-color: black;

            vertical-align: top;
            margin-left: 12px;
            padding: 4px;
            text-decoration: none;
            font-size: 9px;
            font-weight: bold;
        }

        @media only screen and (max-device-width: 480px)
        {
            div.container
            {
                width: 100%;

                margin: 0;
                padding: 15px 0 15px 0;
            }

            header ul li
            {
                display: block;
                padding: 10px 0 0 0;
            }

            iframe#twitter-widget-0
            {
                margin-right: 20px;
            }

            a.line-share-button img
            {
                margin-left: 20px;
            }

            tfoot tr td span
            {
                display: block;
            }

            div.contents ul
            {
                width: 100%;
            }

            div.contents ul img
            {
                max-width: 100px;
            }

            div.imagePane
            {
                height: auto;
            }

            div.imagePane img
            {
                float: none;
                width: 100%;
            }
        }

        </style>
    </head>
    <body>
        <div class="container">
            <header>' .
                $headerInnerHtml . '
            </header>
            <div class="contents">
                <h1 class="engLargeText">' . $engTitle . '</h1>
                <span>' . $jpnTitle . '</span>
                <br /><br />';

if (isset($fShowPageImg) && $fShowPageImg)
{
    echo '<img src="./' . $strVillageDir . '.png" alt="' . $jpnTitle . 'の地図" />';
}

echo "<br />$bodyHtml";

if (isset($fRenderVintageChart) && $fRenderVintageChart)
{
    $vintageRootDir =
        "//anyway-grapes.jp/laravel5.3/public/vintages/$strRegion/";

    echo '
                <br /><br />
                <h2>ヴィンテージ情報</h2>
                <table>
                    <tr>
                        <td><a href="' . $vintageRootDir . '2000" target="_blank">2000年</a></td>
                        <td><a href="' . $vintageRootDir . '2001" target="_blank">2001年</a></td>
                        <td><a href="' . $vintageRootDir . '2002" target="_blank">2002年</a></td>
                        <td><a href="' . $vintageRootDir . '2003" target="_blank">2003年</a></td>
                        <td><a href="' . $vintageRootDir . '2004" target="_blank">2004年</a></td>
                        <td><a href="' . $vintageRootDir . '2005" target="_blank">2005年</a></td>
                        <td><a href="' . $vintageRootDir . '2006" target="_blank">2006年</a></td>
                    </tr>
                    <tr>
                        <td><a href="' . $vintageRootDir . '2007" target="_blank">2007年</a></td>
                        <td><a href="' . $vintageRootDir . '2008" target="_blank">2008年</a></td>
                        <td><a href="' . $vintageRootDir . '2009" target="_blank">2009年</a></td>
                        <td><a href="' . $vintageRootDir . '2010" target="_blank">2010年</a></td>
                        <td><a href="' . $vintageRootDir . '2011" target="_blank">2011年</a></td>
                        <td><a href="' . $vintageRootDir . '2012" target="_blank">2012年</a></td>
                        <td><a href="' . $vintageRootDir . '2013" target="_blank">2013年</a></td>
                    </tr>
                </table>';
}

echo '
                <br /><br />
                <h2>生産者一覧</h2>' .
                $producerListHtml . '
            </div>
            <footer>
                <a href="//anyway-grapes.jp"><img src="//sei-ya.jp/anyway-grapes/images/logo.png" alt="ワインのAnyway-Grapes｜世田谷区 経堂" class="shopLogo" /></a>
            </footer>
        </div>
    </body>
</html>
';

?>
