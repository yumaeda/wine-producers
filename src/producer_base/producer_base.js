function encodeHtmlForm(data)
{
    var params = [];

    for (var name in data)
    {
        var value = data[name];
        var param = encodeURIComponent(name) + '=' + encodeURIComponent(value);

        params.push(param);
    }

    return params.join('&').replace(/%20/g, '+');
}

function getXmlHttpRequest(successMsg)
{
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == XMLHttpRequest.DONE)
        {
            if (xmlhttp.status == 200)
            {
                alert(successMsg);
            }
            else if (xmlhttp.status == 400)
            {
                console.error('ERROR 400');
            }
            else
            {
                console.error('UNKNOWN ERROR');
            }
        }
    }

    return xmlhttp;
}

function registerProducer(strProducer)
{
    var xmlhttp               = getXmlHttpRequest(strProducer + 'がお気に入りに追加されました。'),
        data                  = { action: 'add', producer: strProducer },
        doubleEscapedProducer = strProducer.replace(/'/g, "\\\\\'");

    xmlhttp.open('POST', '//anyway-grapes.jp/producers/favorite_list_manager.php', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send(encodeHtmlForm(data));

    document.getElementsByClassName('favoriteLnk')[0].outerHTML =
        '<a href="#" class="favoriteLnk" onclick="unregisterProducer(\'' + doubleEscapedProducer + '\');return false;">お気に入りから外す。</a>';
}

function unregisterProducer(strProducer)
{
    var xmlhttp               = getXmlHttpRequest(strProducer + 'がお気に入りから外されました。'),
        data                  = { action: 'remove', producer: strProducer },
        doubleEscapedProducer = strProducer.replace(/'/g, "\\\\\'");

    xmlhttp.open('POST', '//anyway-grapes.jp/producers/favorite_list_manager.php', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send(encodeHtmlForm(data));

    document.getElementsByClassName('favoriteLnk')[0].outerHTML =
        '<a href="#" class="favoriteLnk" onclick="registerProducer(\'' + doubleEscapedProducer + '\');return false;">お気に入りに追加する。</a>';
}

function addItemToCart(itemId, itemName, cartType)
{
    var xmlhttp = getXmlHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == XMLHttpRequest.DONE)
        {
            if (xmlhttp.status == 200)
            {
                var footerTd =
                    document.getElementById(itemId).getElementsByClassName('itemFooterCol');

                if (cartType == 0)
                {
                    footerTd[0].innerHTML =
                        '<span style="color:green;">カートに追加されました。</span>' +
                        '<a href="https://anyway-grapes.jp/cart.php"><img src="//anyway-grapes.jp/campaign/cash_register.png" title="レジへ進む" /> レジへ進む。</a>';
                }
                else
                {
                    footerTd[0].innerHTML =
                        '<span style="color:green;">福袋に追加されました。</span>' +
                        '<a href="https://anyway-grapes.jp/cart.php"><img src="//anyway-grapes.jp/campaign/cash_register.png" title="レジへ進む" /> レジへ進む。</a>';
                }
            }
            else if (xmlhttp.status == 400)
            {
                console.error('ERROR 400');
            }
            else
            {
                console.error('UNKNOWN ERROR');
            }
        }
    };

    var data = { action: 'add', cart_type: cartType, pid: itemId, qty: 1 }; 
    xmlhttp.open('POST', '//anyway-grapes.jp/cart.php', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send(encodeHtmlForm(data));
}


// Google Analytic Code
//----------------------------------------------------------------------------
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-44746254-1', 'auto');
ga('send', 'pageview');


// Facebook Code
//----------------------------------------------------------------------------
(function(d, s, id)
{
    var js,
        fjs = d.getElementsByTagName(s)[0];

    if (!d.getElementById(id))
    {
        js     = d.createElement(s);
        js.id  = id;
        js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.4";

        fjs.parentNode.insertBefore(js, fjs);
    }
}(document, 'script', 'facebook-jssdk'));


// Twitter function
//----------------------------------------------------------------------------
!function(d, s, id)
{
    var js,
        fjs = d.getElementsByTagName(s)[0],
        p   = /^http:/.test(d.location) ? 'http' : 'https';

    if (!d.getElementById(id))
    {
        js     = d.createElement(s);
        js.id  = id;
        js.src = p + '://platform.twitter.com/widgets.js';

        fjs.parentNode.insertBefore(js,fjs);
    }
}(document, 'script', 'twitter-wjs');


$(document).ready(function()
{
    $('div.contents').on('click', 'a.vintageLnk', function()
    {
        var $this   = $(this),
            tableId = $this.attr('id').replace('_lnk', '');

        $this.closest('ul').siblings('table').fadeOut();
        $('table#' + tableId).fadeIn();

        return false;
    });

    if (window.location.hash)
    {
        $targetTable = $('table' + window.location.hash);
        $targetTable.siblings('table').fadeOut();
        $targetTable.fadeIn();

        $('html, body').animate({ scrollTop: $targetTable.closest('li').offset().top }, 2000);
    }
});

