<?php

session_start();

$userName = '';
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_name']))
{
    $userId   = $_SESSION['user_id'];
    $userName = $_SESSION['user_name'];
}

$curDirPath = dirname(__FILE__);
include_once("$curDirPath/../types.php");
include_once("$curDirPath/functions.php");

class Wine
{
    public $id;
    public $name;
    public $jpnName;
    public $producer;
    public $type;
    public $vintage;
    public $country;
    public $region;
    public $cepage;
    public $capacity;
    public $importer;
    public $point;
    public $price;
    public $memberPrice;
    public $stock;
    public $apply;
    public $etc;
}

// Copied from full-winelist.php.
function startsWith($str, $find)
{
    return ($find === '' || strpos($str, $find) === 0);
}

function isPurchasable($objWine)
{
    $fPurchasable = FALSE;

    return (($objWine->apply != 'SO') && ($objWine->stock > 0) && ($objWine->price > 0)); 
}

function generatePriceHtml($objWine)
{
    $priceHtml = '';
    $fMember   = (isset($_SESSION['user_id']) && isset($_SESSION['user_name']));

    $fHidePrice = FALSE;
    if (!isset($_REQUEST['hide_price']) || ($_REQUEST['hide_price'] != 1))
    {
        if (!isPurchasable($objWine))
        {
            $priceHtml = 
                '<span class="engMediumText" style="color:red;">Sold Out</span>';
        }
        else
        {
            if ($fMember)
            {
                $priceHtml =
                    '<span class="jpnFont">通常価格：<span class="engMediumText">¥' . number_format($objWine->price) . '</span> (税抜)</span>' .
                    '<br />' .
                    '<span style="color:red;"><span class="jpnFont">会員価格：<span class="engLargeText">¥' . number_format($objWine->memberPrice) . '</span> (税抜)</span></span>';
            }
            else
            {
                $priceHtml =
                    '<span><span class="jpnFont">通常価格：<span class="engLargeText">¥' . number_format($objWine->price) . '</span> (税抜)</span></span>' .
                    '<br />' .
                    '<span class="jpnFont" style="color:red">会員価格：<span class="engMediumText">¥' . number_format($objWine->memberPrice) . '</span> (税抜)</span>';
            }
        }
    }

    return $priceHtml;
}

function generateWineDetailHtml($objWine, $fMulti, $fHide)
{
    global $wineTypeHash;

    $barcode  = $objWine->id;
    $imgUrl   = "//anyway-grapes.jp/images/wines/200px/$barcode.png";
    $noImgUrl = "//anyway-grapes.jp/images/wines/200px/no_wine_photo.png";

    $strCountry = $objWine->country;
    $strVintage = $objWine->vintage;
    $strRegion  = $objWine->region;
    switch ($strRegion)
    {
    case 'Champagne':
    case 'Alsace':
    case 'Bordeaux':
    case 'Bourgogne':
    case 'Sud-Ouest':
    case 'Vallée de la Loire':
    case 'Jura':
    case 'Savoie':
    case 'Vallée du Rhône':
    case 'Provence':
    case 'Corse':
    case 'Languedoc':
    case 'Roussillon':
    case 'Western Cape':
        $strVintage = '<a target="_blank" href="//anyway-grapes.jp/laravel5.3/public/vintages/' . $strRegion . '/' . $strVintage . '">' . $strVintage . '年</a>';
        break;
    }

    $strImporter = $objWine->importer;
    if (($strImporter === 'TAKAHASHI COLLECTION') ||
        ($strImporter === 'MATSUYA SAKETEN') ||
        ($strImporter === 'KANAI-YA') ||
        ($strImporter === 'ESPOA SHINKAWA') ||
        ($strImporter === 'LA VINÉE') ||
        ($strImporter === 'SENSHO') ||
        ($strImporter === 'BERRY BROS & RUDD') ||
        ($strImporter === 'LA TOUR D\'ARGENT') ||
        ($strImporter === 'TSUCHIURA SUZUKI-YA'))
    {
        $strImporter = '';
    }

    $strTableStyle = $fHide ? ' style="display:none"' : '';

    $html = '
        <table id="' . $barcode . '"' . $strTableStyle . '>
            <tbody>
            <tr>
                <td style="width:350px;text-align:center;">
                    <a href="http://anyway-grapes.jp/store/index.php?pc_view=1&submenu=wine_detail&id=' . $barcode . '" target="_blank"><img class="clickable-img" src="' . $imgUrl . '" alt="' . $objWine->jpnName . '" onerror="this.src=\'' . $noImgUrl . '\';" /></a>
                    <br />
                    <span style="color:lightcoral;">画像は実際とは異なる場合があります。</span>
                </td>
                <td>
                    <strong class="engMediumText">' . $objWine->name . '</strong>
                    <br />
                    <span id="' . $barcode . '">' . $objWine->jpnName . '</span>
                    <br /><br />
                    <table>';

    if ($fMulti)
    {
        $html .= '
                        <tr>
                            <td class="label-td">生産者</td>
                            <td>' . $objWine->producer . '</td>
                        </tr>';
    }

    $html .= '
                        <tr>
                            <td class="label-td">タイプ</td>
                            <td>' . $wineTypeHash[$objWine->type] . '</td>
                        </tr>
                        <tr>
                            <td class="label-td">生産年</td>
                            <td>' . $strVintage . '</td>
                        </tr>
                        <tr>
                            <td class="label-td">ブドウ品種</td>
                            <td>' . $objWine->cepage . '</td>
                        </tr>
                        <tr>
                            <td class="label-td">容量</td>
                            <td>' . $objWine->capacity . '&nbsp;ml</td>
                        </tr>
                        <tr>
                            <td class="label-td">輸入元</td>
                            <td>' . $strImporter . '</td>
                        </tr>
                        <tr>
                            <td class="label-td">評価</td>
                            <td>' . $objWine->point . '</td>
                        </tr>
                    </table>
                    <br />' .
                    $objWine->detail . '
                </td>
            </tr>
            </tbody>';

        $priceHtml = generatePriceHtml($objWine);
        $cartText  = 'カートに追加する';

        $strPattern = '/^[0-9]{1,2}\.[0-9]{1,2}$/';
        if (preg_match($strPattern, $objWine->etc))
        {
            $priceHtml .= '<br /><span style="font-size:12px;color:red;">' . $objWine->etc . '入荷予定。</span>';
            $cartText  .= '（入荷日の翌日以降の発送になります。）';
        }

        $html .= '
            <tfoot>
            <tr>
                <td>' . $priceHtml . '</td>
                <td class="itemFooterCol">';

        if (isPurchasable($objWine))
        {
            $html .= '
                    <span>
                        <a href="#" onclick="addItemToCart(\'' . $barcode . '\', \'' . $objWine->jpnName . '\', \'0\');return false;">
                            <img class="clickable-img" src="http://anyway-grapes.jp/campaign/add_to_cart.png" title="' . $cartText . '" />' . $cartText . '
                        </a>
                    </span>';

            if ($objWine->etc == 'Happy-Box')
            {
                $html .= '
                    <span>
                        <a href="#" onclick="addItemToCart(\'' . $barcode . '\', \'' . $objWine->jpnName . '\', \'1\');return false;">
                            <img class="clickable-img" src="http://anyway-grapes.jp/images/happy_box_icon.png" style="vertical-align:bottom;" title="福箱に追加する" />福箱に追加する。
                        </a>
                    </span>';
            }
        }

        $html .= '
                    <span>
                        <a href="https://anyway-grapes.jp/cart.php"><img class="clickable-img" src="http://anyway-grapes.jp/campaign/cash_register.png" title="レジへ進む" /> レジへ進む。</a>
                    </span>
                    <br />
                </td>
            </tr>
            </tfoot>
        </table>';

    return $html;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $disableSession = TRUE;
    require_once("$curDirPath/../../includes/defines.php");
    require("$curDirPath/../includes/config.inc.php");
    require(MYSQL);

    $producer = mysqli_real_escape_string($dbc, $producer);
    $result = mysqli_query($dbc, "CALL get_producer_detail('$producer')");
    if ($result !== FALSE)
    {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $engCountry      = $row['country'];
        $jpnCountry      = $rgobjCountry[$engCountry];
        $engState        = $row['region'];
        $jpnState        = $row['region_jpn'];
        $engProducerFull = $row['name'];
        $jpnProducerFull = $row['name_jpn'];
        $engProducer     = $row['short_name'] ? $row['short_name'] : $engProducerFull;
        $jpnProducer     = $row['short_name_jpn'] ? $row['short_name_jpn'] : $jpnProducerFull;
        $homeUrl         = $row['home_page'];
        $foundedYear     = $row['founded_year'] ? ($row['founded_year'] . '年') : '';
        $headquarter     = $row['headquarter'];
        $jpnHeadquarter  = $row['headquarter_jpn'];
        $managerName     = $row['family_head'];
        $jpnManagerName  = $row['family_head_jpn'];
        $totalHa         = $row['field_area'] ? ($row['field_area'] . 'ha') : '';
        $importer        = $row['importer'];

        $historyAndDetail   = $row['history_detail'];
        $fieldDetail        = $row['field_detail'];
        $fermentationDetail = $row['fermentation_detail'];
        $originalContents   = $row['original_contents'];
        $fOriginal          = $row['is_original'];
        $fMulti             = $row['is_multi'];

        if (isset($fOriginalContents) && $fOriginalContents)
        {
            $fOriginal = TRUE;
        }

        $engDistrict = isset($row['district'])     ? $row['district'] : '';
        $jpnDistrict = isset($row['district_jpn']) ? $row['district_jpn'] : '';
        $engVillage  = isset($row['village'])      ? $row['village'] : '';
        $jpnVillage  = isset($row['village_jpn'])  ? $row['village_jpn'] : '';

        mysqli_free_result($result);

        if ($historyAndDetail)
        {
            $historyAndDetail = nl2br($historyAndDetail);
        }
        else
        {
            $historyAndDetail = 'Not Available...';
        }

        if ($fieldDetail)
        {
            $fieldDetail = nl2br($fieldDetail);
        }
        else
        {
            $fieldDetail = 'Not Available...';
        }

        if ($fermentationDetail)
        {
            $fermentationDetail = nl2br($fermentationDetail);
        }
        else
        {
            $fermentationDetail = 'Not Available...';
        }

        $strKeywords    = "$engProducer,$jpnProducer,$engCountry,$jpnCountry,$engState,$jpnState,ワイン,wine";
        $topPageUrl     = 'http://anyway-grapes.jp/producers';
        $countryPageUrl = $topPageUrl . '/' . str_replace(' ', '-', strtolower($engCountry));

        $stateFolderName = generateFolderName($engState, $engCountry);
        $statePageUrl    = $countryPageUrl . '/' . $stateFolderName;

        echo '
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
        <title>' . $jpnProducer . ' (' . $engProducer . ')</title>
        <meta name="viewport" content="width=device-width, user-scalable=no" />
        <meta name="keywords" content="' . $strKeywords . '">
        <meta name="author" content="Conceptual Wine Boutique Anyway-Grapes">
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
        <div id="fb-root"></div>
        <script async="async" type="text/javascript" src="//anyway-grapes.jp/producers/producer_base.min.js"></script>
        <div class="container">
            <header>
                <ul>
                    <li><a href="//anyway-grapes.jp/store/index.php">Home</a></li>
                    <li><span class="arrow-span">&gt;</li>
                    <li><a href="' . $countryPageUrl . '"><span>' . $jpnCountry . '</span></a></li>
                    <li><span class="arrow-span">&gt;</li>
                    <li><a href="' . $statePageUrl . '"><span>' . $jpnState . '</span></a></li>
                    <li><span class="arrow-span">&gt;</li>';

    if (($engDistrict != '') && ($jpnDistrict != ''))
    {
        $districtFolderName = generateFolderName($engDistrict, $engCountry);
        $districtPageUrl    = $statePageUrl . '/' . $districtFolderName;

        echo '
                    <li><a href="' . $districtPageUrl . '"><span>' . $jpnDistrict . '</span></a></li>
                    <li><span class="arrow-span">&gt;</li>';

        if (($engVillage != '') && ($jpnVillage != ''))
        {
            $villageFolderName = generateFolderName($engVillage, $engCountry);
            $villagePageUrl    = $districtPageUrl . '/' . $villageFolderName;

            echo '
                    <li><a href="' . $villagePageUrl . '"><span>' . $jpnVillage . '</span></a></li>
                    <li><span class="arrow-span">&gt;</li>';
        }
    }

    $twitterHash = generateFolderName($engProducer, $engCountry);

    echo '
                    <li><strong>' . $jpnProducer . '</strong></li>
                </ul>
                <br />
                <a href="https://twitter.com/share" class="twitter-share-button" data-lang="ja" data-count="none" data-hashtags="' . $twitterHash . '">ツイート</a>
                <div class="fb-like" data-layout="button_count" data-show-faces="true" data-share="false"></div>
                <a class="line-share-button" href="http://line.me/R/msg/text/?' . urlencode($jpnProducer . ' (' . $engProducer . ')') . '%0D%0A' . urlencode('http://anyway-grapes.jp' . $_SERVER['PHP_SELF']) . '"><img src="http://anyway-grapes.jp/producers/images/line_button.png" alt="LINEで送る" /></a>';

    if (!empty($userName))
    {
        // The following producers cannot be added to the favorite list.
        if (($engProducerFull != 'Domaine Georges Roumier') && 
            ($engProducerFull != 'Domaine Emmanuel Rouget') && 
            ($engProducerFull != 'Château Rayas'))
        {
            prepareNextQuery($dbc);

            $sqlProducer = mysqli_real_escape_string($dbc, $engProducerFull);
            $jsProducer  = str_replace("'", "\\'", $engProducerFull);

            $rgobjProducer = mysqli_query($dbc, "CALL get_favorite_producer('$userId', '$sqlProducer')");
            if ($rgobjProducer !== FALSE)
            {
                $cProducer = mysqli_num_rows($rgobjProducer);
                if ($cProducer == 1)
                {
                    echo '<a href="#" class="favoriteLnk" onclick="unregisterProducer(\'' . $jsProducer . '\');return false;">お気に入りから外す。</a>';
                }
                else
                {
                    echo '<a href="#" class="favoriteLnk" onclick="registerProducer(\'' . $jsProducer . '\');return false;">お気に入りに追加する。</a>';
                }

                mysqli_free_result($rgobjProducer);
            }
        }
    }

$doubleEscapedProducer = str_replace("'", "\\\\\'", $engProducerFull);

echo '
            </header>
        <div class="contents">
            <h1 class="engLargeText">' . $engProducerFull . '</h1>
            <span>' . $jpnProducerFull . '</span>
            <br /><br />
            <div class="imagePane">
                <img src="./' . $twitterHash . '-1.jpg" alt="' . $jpnProducerFull . 'の写真1" onerror="this.src=\'http://anyway-grapes.jp/producers/images/unavailable-1.jpg\';" />
                <img src="./' . $twitterHash . '-2.jpg" alt="' . $jpnProducerFull . 'の写真2" onerror="this.src=\'http://anyway-grapes.jp/producers/images/unavailable-2.jpg\';" />
                <img src="./' . $twitterHash . '-3.jpg" alt="' . $jpnProducerFull . 'の写真3" onerror="this.src=\'http://anyway-grapes.jp/producers/images/unavailable-3.jpg\';" />
            </div>
            <table>
                <tr>
                    <td class="label-td">URL</td>
                    <td><a href="' . $homeUrl . '" rel="nofollow">' . $homeUrl . '</a></td>
                </tr>
                <tr>
                    <td class="label-td">設立</td>
                    <td>' . $foundedYear . '</td>
                </tr>
                <tr>
                    <td class="label-td">本拠地</td>
                    <td>' . $headquarter . '（' . $jpnHeadquarter . '）</td>
                </tr>
                <tr>
                    <td class="label-td">当主</td>
                    <td>' . $managerName . '（' . $jpnManagerName . '）</td>
                </tr>
                <tr>
                    <td class="label-td">畑の総面積</td>
                    <td>' . $totalHa . '</td>
                </tr>';

    if ($fOriginal)
    {
        echo '
            </table>
            <br /><br />
            <h2>❦ 詳細・歴史</h2>' . '<span style="line-height:1.4;">' . $historyAndDetail . '</p>
            <br />
            <h2>❦ 畑</h2>'         . '<span style="line-height:1.4;">' . $fieldDetail . '</p>
            <br />
            <h2>❦ 醸造</h2>'       . '<span style="line-height:1.4;">' . $fermentationDetail . '</p>';
    }
    else
    {
        echo '
                <tr>
                    <td class="label-td">資料提供</td>
                    <td>' . $importer . '</td>
                </tr>
            </table>
            <br /><br />
            <h2>❦ 詳細・歴史</h2>
            <blockquote style="line-height:1.4;">' . $historyAndDetail . '</blockquote>
            <br /><br />
            <h2>❦ 畑</h2>
            <blockquote style="line-height:1.4;">' . $fieldDetail . '</blockquote>
            <br /><br />
            <h2>❦ 醸造</h2>
            <blockquote style="line-height:1.4;">' . $fermentationDetail . '</blockquote>';
    }

    if ($originalContents)
    {
        echo $originalContents;
    }

    echo '<ul>';

    $i                 = 1;
    $wineName          = '';
    $wineJpnName       = '';
    $wineDetail        = '';
    $rgrgobjWineDetail = array();
    $rgobjSoldOutWine  = array();

    prepareNextQuery($dbc);

    if (!$fMulti)
    {
        $result = mysqli_query($dbc, "CALL get_wine_details('$producer')");
    }
    else
    {
        $result = mysqli_query($dbc, "CALL get_related_wine_details('$producer')");
    }

    if ($result !== FALSE)
    {
        $fMember = (isset($_SESSION['user_id']) && isset($_SESSION['user_name']));

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $barcode     = $row['barcode_number'];
            $wineName    = '';
            $wineJpnName = '';
            $strCepage   = '';

            if (isset($rgstrExcludedBarcode) && in_array($barcode, $rgstrExcludedBarcode))
            {
                continue;
            }

            $wineDetail = $row['detail'];
            if ($wineDetail)
            {
                $wineDetail = nl2br($wineDetail);
            }
            else
            {
                $wineDetail = '';
            }

            if ($barcode != '0000')
            {
                $intStock = intval($row['stock']);

                if ($row['availability'] == 'Online')
                {
                    $wineName        = $row['combined_name'];
                    $wineJpnName     = $row['combined_name_jpn'];
                    $strType         = $row['type'];
                    $strCepage       = $row['cepage'];
                    $intCapacity     = $row['capacity'];
                    $intPrice        = ($fMember == TRUE) ? $row['member_price'] : $row['price'];
                    $strPrice        = number_format($intPrice);
                    $strPriceWithTax = number_format(floor($intPrice * 1.08));
                    $strVintage      = $row['vintage'];
                    $strImporter     = $row['importer'];

                    // Generate review score string by removing WA point.
                    $strPoint   = '';
                    $rgstrScore = explode(',', $row['point']);
                    $cScore     = count($rgstrScore);
                    for ($i = 0; $i < count($rgstrScore); ++$i)
                    {
                        if (!startsWith($rgstrScore[$i], '○○'))
                        {
                            $strPoint .= $rgstrScore[$i];
                            if ($i < ($cScore - 1))
                            {
                                $strPoint .= ',&nbsp;&nbsp;&nbsp;&nbsp;';
                            }
                        }
                    }

                    $objWine = new Wine();
                    $objWine->id          = $row['barcode_number'];
                    $objWine->name        = $wineName;
                    $objWine->jpnName     = $wineJpnName;
                    $objWine->producer    = $row['producer'];
                    $objWine->type        = $strType;
                    $objWine->vintage     = $strVintage;
                    $objWine->country     = $row['country'];
                    $objWine->region      = $row['region'];
                    $objWine->cepage      = $strCepage;
                    $objWine->capacity    = $intCapacity;
                    $objWine->importer    = $strImporter;
                    $objWine->point       = $strPoint;
                    $objWine->price       = $row['price'];
                    $objWine->stock       = $row['stock'];
                    $objWine->memberPrice = $row['member_price'];
                    $objWine->etc         = $row['etc'];
                    $objWine->apply       = $row['apply'];

                    if (!$fOriginal)
                    {
                        $objWine->detail = "<blockquote>$wineDetail</blockquote>";
                    }
                    else
                    {
                        $objWine->detail = $wineDetail;
                    }

                    if (!isPurchasable($objWine))
                    {
                        $rgobjSoldOutWine[] = $objWine;
                    }
                    else
                    {
                        $key = ($objWine->type . ':' . $objWine->name);
                        if (!array_key_exists($key, $rgrgobjWineDetail))
                        {
                            $rgrgobjWineDetail[$key] = array();
                        }
                        $rgrgobjWineDetail[$key][] = $objWine;
                    }
                }
            }
            else
            {
                echo "
                            <li>&nbsp;</li>
                            <li>$wineDetail</li>";
            }

            ++$i;
        }

        mysqli_free_result($result);
    }

    $curYear        = date('Y'); 
    $currentPageUrl = "http://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');

    // Add soldout wines to the end of the array.
    foreach ($rgobjSoldOutWine as $objSoldOutWine)
    {

        $soldOutKey = ($objSoldOutWine->type . ':' . $objSoldOutWine->name);
        if (!array_key_exists($soldOutKey, $rgrgobjWineDetail))
        {
            $rgrgobjWineDetail[$soldOutKey] = array();
        }
        $rgrgobjWineDetail[$soldOutKey][] = $objSoldOutWine;
    }

    foreach ($rgrgobjWineDetail as $rgobjWineDetail)
    {
        $navInnerHtml = '';
        $detailHtml   = '';

        if (count($rgobjWineDetail) == 1)
        {
            $objWineDetail = $rgobjWineDetail[0];
            $navInnerHtml .= '<li style="float:left;padding:10px 0 10px 25px;"><a class="vintageLnk" id="' . $objWineDetail->id . '_lnk' . '" href="#">' . $objWineDetail->vintage . '</a></li>';
            $detailHtml = generateWineDetailHtml($objWineDetail, $fMulti, FALSE);
        }
        else
        {
            $i = 0;
            foreach ($rgobjWineDetail as $objWineDetail)
            {
                $navInnerHtml .= '<li style="float:left;padding:10px 0 10px 25px;"><a class="vintageLnk" id="' . $objWineDetail->id . '_lnk' . '" href="#">' . $objWineDetail->vintage . '</a></li>';
                $detailHtml   .= generateWineDetailHtml($objWineDetail, $fMulti, ($i > 0));

                ++$i;
            }
        }

        echo '
            <li>
                <ul class="vintage-switcher">' .
                    $navInnerHtml . '
                </ul>' .
                $detailHtml . '
            </li>';
    }

echo '
            </ul>
        </div>
        <footer>
            <div class="fb-comments" data-href="' . $currentPageUrl . '" data-width="100%" data-numposts="5"></div>
            <br /><br />
            <br />
            <div style="text-align:center;font-size:16px;font-style:italic;color:rgb(120,120,120);">
            &copy;' . $curYear . '&nbsp;Conceptual Wine Boutique Anyway-Grapes <br />
            <span class="jpnFont" style="font-size:12px;">資料提供の記載の無い生産者情報は全てAnyway-Grapes作成のものである為、商用目的での無断転載はご遠慮頂きますよう宜しくお願い致します。</span>
            </div>
            <a href="http://anyway-grapes.jp/store/"><img class="clickable-img shopLogo" src="http://anyway-grapes.jp/producers/logo.png" alt="ワインのAnyway-Grapes｜世田谷区 経堂" /></a>
        </footer>
    </body>
</html>
';

        mysqli_close($dbc);
    }
}

?>
