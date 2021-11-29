<?php

$curDirPath = dirname(__FILE__);

function generateVintageTableHtml($iStart, $iEnd)
{
    global $strRegion;

    $html = '<table>';

    for ($i = $iStart; $i <= $iEnd; ++$i)
    {
        if (($i % 5) === 0)
        {
            if ($i > $iStart)
            {
                $html .= '</tr>';
            }

            $html .= '<tr>';
        }

        $html .=
            '<td>' .
                '<a href="//anyway-grapes.jp/laravel5.3/public/vintages/' . $strRegion . '/' . $i . '" target="vintage_window">' . $i . '年</a>' .
            '</td>';
    }

    $html .= '</tr></table>';

    return $html;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    echo '
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
        <title>' . $strRegion . ' / ' . $strJpnRegion . '</title>
        <meta name="viewport" content="width=device-width, user-scalable=no" />
        <link rel="stylesheet" type="text/css" href="//anyway-grapes.jp/producers/header.css" />
        <link rel="stylesheet" type="text/css" href="//anyway-grapes.jp/producers/common.css" />
        <link rel="stylesheet" type="text/css" href="//anyway-grapes.jp/producers/body.css" />
        <link rel="stylesheet" type="text/css" href="//anyway-grapes.jp/producers/footer.css" />
        <link rel="stylesheet" type="text/css" media="only screen and (max-device-width: 480px)" href="//anyway-grapes.jp/producers/header_mobile.css" />
        <link rel="stylesheet" type="text/css" media="only screen and (max-device-width: 480px)" href="//anyway-grapes.jp/producers/body_mobile.css" />
        <link rel="stylesheet" type="text/css" media="only screen and (max-device-width: 480px)" href="//anyway-grapes.jp/producers/footer_mobile.css" />
        <script type="text/javascript">

        document.createElement(\'header\');
        document.createElement(\'footer\');

        </script>
        <style>

        div.contents table
        {
            margin: 0 0 0 0;
            text-align: center;
        }

        div.contents table td
        {
            padding: 15px;
        }

        div#producerPane
        {
            margin: 20px 0 0 0;
        }

        td.alphabetCol
        {
            cursor: pointer;
        }

        .selected
        {
            background-color: black;
            color: white;
        }

        </style>
    </head>
    <body>
        <header>
            <ul>
                <li><a href="//anyway-grapes.jp/store/index.php"><img src="//anyway-grapes.jp/producers/images/home.png" alt="Home" style="width:25px;" /></a></li>
                <li>&nbsp;&nbsp;&gt;</li>
                <li><a href="//anyway-grapes.jp/producers/france/index.html">France / <span class="jpnText">フランス</span></a></li>
                <li>&nbsp;&nbsp;&gt;</li>
                <li><strong>' . $strRegion . ' / <span class="jpnText">' . $strJpnRegion . '</span></strong></li>
            </ul>
            <input type="hidden" id="regionFld" value="' . $strRegion . '" />
        </header>
        <div class="contents">
            <h1>' . $strRegion . ' / ' . $strJpnRegion . '</h1>
            <br /><br />
            <div id="inner-contents">
            </div>
            <h2>ヴィンテージ情報</h2>' .
            generateVintageTableHtml(2000, 2013) . '
            <br /><br /><br />
            <h2>' . $strJpnRegion . 'の生産者（アルファベット順）</h2>
            <table>
                <tr>
                    <td class="alphabetCol">A</td>
                    <td class="alphabetCol">B</td>
                    <td class="alphabetCol">C</td>
                    <td class="alphabetCol">D</td>
                    <td class="alphabetCol">E</td>
                    <td class="alphabetCol">F</td>
                    <td class="alphabetCol">G</td>
                </tr>
                <tr>
                    <td class="alphabetCol">H</td>
                    <td class="alphabetCol">I</td>
                    <td class="alphabetCol">J</td>
                    <td class="alphabetCol">K</td>
                    <td class="alphabetCol">L</td>
                    <td class="alphabetCol">M</td>
                    <td class="alphabetCol">N</td>
                </tr>
                <tr>
                    <td class="alphabetCol">O</td>
                    <td class="alphabetCol">P</td>
                    <td class="alphabetCol">Q</td>
                    <td class="alphabetCol">R</td>
                    <td class="alphabetCol">S</td>
                    <td class="alphabetCol">T</td>
                    <td class="alphabetCol">U</td>
                </tr>
                <tr>
                    <td class="alphabetCol">V</td>
                    <td class="alphabetCol">W</td>
                    <td class="alphabetCol">X</td>
                    <td class="alphabetCol">Y</td>
                    <td class="alphabetCol">Z</td>
                </tr>
            </table>
            <div id="producerPane"></div>
        </div>
        <footer>
            <a href="http://anyway-grapes.jp"><img src="http://sei-ya.jp/anyway-grapes/images/logo.png" alt="ワインのAnyway-Grapes｜世田谷区 経堂" class="shopLogo" /></a>
        </footer>
    </body>
</html>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="//anyway-grapes.jp/producers/producer_list.min.js"></script>'; 
}
