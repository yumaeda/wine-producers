//
// Dependencies
//     - seiya.producers-0.1.js
//
function registerAlphabetClick(strRegion)
{
    $('div.contents').on('click', 'td.alphabetCol', function()
    {
        $('td.alphabetCol').removeClass('selected');
        $(this).addClass('selected');

        var chLetter = $(this).html();

        $.ajax(
        {
            url: '//anyway-grapes.jp/laravel5.3/public/api/v1/producers/' + strRegion,
            data: { initial: chLetter },
            dataType: 'json',
            success: function(data)
            {
                var rgobjProducer = data.producers,
                    cProducer     = rgobjProducer.length,
                    objProducer   = '',
                    strUrl        = '',
                    html          = '<ul class="producerList">';

                for (var i = 0; i < cProducer; ++i)
                {
                    objProducer = rgobjProducer[i];
                    strUrl      = UrlUtility.generateProducerPageUrl(objProducer);

                    html +=
                        '<li>' +
                            '<a href="' + strUrl + '" target="_blank"><span class="jpnText" style="font-size:12px;">' + objProducer.name_jpn + '</span>&nbsp;(&nbsp;' + objProducer.name + '&nbsp;)</a>' +
                        '</li>';
                }

                if (cProducer == 0)
                {
                    html = '該当する生産者が見つかりません。';
                }
                else
                {
                    html += '</ul>';
                }

                $('div#producerPane').html(html);
            },

            error: function() {}
        });

        return false;
    });
}

// Google Analytic Code
//----------------------------------------------------------------------------
(function(i,s,o,g,r,a,m)
{
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function()
    {
        (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date();

    a = s.createElement(o),
    m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a,m)
})(window,document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

ga('create', 'UA-44746254-1', 'auto');
ga('send', 'pageview');
//----------------------------------------------------------------------------

$(document).ready(function()
{
    var strRegion = $('input#regionFld').val(),
        apiUrl    = '//anyway-grapes.jp/laravel5.3/public/api/v1/region-details/' + strRegion;

    $.ajax(
    {
        url: apiUrl,
        dataType: 'json',
        success: function(data)
        {
            $('div#inner-contents').html(data.details[0].contents);
        },

        error: function() {}
    });

    registerAlphabetClick(strRegion);
});

