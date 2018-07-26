<?php

$curDirPath = dirname(__FILE__);
require_once("$curDirPath/functions.php");
require_once("$curDirPath/../../includes/defines.php");
require("$curDirPath/../includes/config.inc.php");
require(MYSQL);

$strJpnCountry = $rgobjCountry[$strCountry];

function generateHeaderInnerHtml()
{
    global $strCountry, $strJpnCountry;

    $html ='
        <ul>
            <li><a href="//anyway-grapes.jp/producers"><img src="//anyway-grapes.jp/producers/images/home.png" alt="Home" style="width:25px;" /></a></li>
            <li><span class="arrow-span">&gt;</li>
            <li>
                <strong><span>' . $strJpnCountry . '</span></strong>
            </li>
        </ul>';

    return $html;
}

function generateBodyHtml()
{
    global $dbc, $strCountry; 

    $html = '';

    $sqlCountry = mysqli_real_escape_string($dbc, $strCountry);
    $result = mysqli_query($dbc, "CALL get_html_page('$strCountry', '', '', '')");
    if (($result !== FALSE) && (mysqli_num_rows($result) === 1))
    {
        $row  = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $html = $row['contents'];
    }

    return $html;
}

/*
function generateRegionListHtml()
{
    global $dbc, $strCountry; 

    $html = '<ul class="link-list">';

    $sqlCountry = mysqli_real_escape_string($dbc, $strCountry);

    $result = mysqli_query($dbc, "CALL get_regions('', '$sqlCountry')");
    if ($result !== FALSE)
    {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $strRegion     = $row['region'];
            $strJpnRegion  = $row['region_jpn'];
            $strCountryDir = generateFolderName($strCountry, $strCountry);
            $strRegionDir  = generateFolderName($strRegion, $strCountry);

            if (($strRegionDir != '') && ($strRegionDir != '------------'))
            {
                if ($strRegion == 'Languedoc')
                {
                    $html .= '
                        <li style="padding-bottom:15px;">
                            <a href="//anyway-grapes.jp/producers/' . $strCountryDir . '/languedoc-et-roussillon/index.html">' .
                                'Languedoc et Roussillon / <span class="jpnText">ラングドックとルーション地方</span>' .
                            '</a>
                        </li>';
                }
                else if ($strRegion == 'Roussillon')
                {
                }
                else
                {
                    $html .= '
                        <li style="padding-bottom:15px;">
                            <a href="//anyway-grapes.jp/producers/' . $strCountryDir . '/' . $strRegionDir . '/index.html">' .
                            $strRegion . '&nbsp;/&nbsp;<span>' . $strJpnRegion . '</span></a>
                        </li>';
                }
            }
        }
    }

    $html .= '</ul>';

    return $html;
}

$regionListHtml = generateRegionListHtml();
prepareNextQuery($dbc);
*/
$bodyHtml = generateBodyHtml();

echo '
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
        <title>' . $strCountry . '&nbsp;/&nbsp;' . $strJpnCountry . '</title>
        <meta name="viewport" content="width=device-width, user-scalable=no" />
        <script type="text/javascript">

        document.createElement(\'header\');
        document.createElement(\'footer\');

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
            <header>' .  generateHeaderInnerHtml() . '</header>
            <div class="contents">
                <h1>' . $strCountry . '</h1>
                <span>' . $strJpnCountry . '</span>
                <br /><br />
                <iframe src="' . $iframeSrc . '" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
                <br /><br />
                <p>
                    <ul class="link-list"></ul>
                </p>
                <br /><br />' .
                $bodyHtml . '
            </div>
            <footer>
                <a href="//anyway-grapes.jp"><img src="//sei-ya.jp/anyway-grapes/images/logo.png" alt="ワインのAnyway-Grapes｜世田谷区 経堂" class="shopLogo" /></a>
            </footer>
        </div>
    </body>
</html>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="//anyway-grapes.jp/producers/country_base.min.js?ver=20180120_001"></script>
';
