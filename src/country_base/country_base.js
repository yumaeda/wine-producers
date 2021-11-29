$(document).ready(function()
{
    var strCountry = $('h1').html();

    $.ajax(
    {
        url: '//anyway-grapes.jp/laravel5.3/public/api/v1/regions/' + strCountry,
        dataType: 'json',
        success: function(data)
        {
            var rgobjRegion = data.regions,
                cRegion     = rgobjRegion.length,
                objRegion   = null,
                html        = "";

            for (var i = 0; i < cRegion; ++i)
            {
                objRegion = rgobjRegion[i];

                var strRegion     = objRegion.region,
                    strJpnRegion  = objRegion.region_jpn,
                    regionUrl     = UrlUtility.generateRegionPageUrl(strCountry, objRegion.region),
                    strAnchorText = '';

                if ((strRegion != '') && (strRegion != '------------'))
                {
                    if ((strRegion == 'Languedoc') ||
                        (strRegion == 'Roussillon'))
                    {
                        strAnchorText = 
                            'Languedoc et Roussillon / <span class="jpnText">ラングドックとルーション地方</span>';
                    }
                    else
                    {
                        strAnchorText = 
                                strRegion + '&nbsp;/&nbsp;<span>' + strJpnRegion + '</span>';
                    }

                    html +=
                        '<li style="padding-bottom:15px;">' +
                            '<a href="' + regionUrl + '">' +
                                strAnchorText +
                            '</a>' +
                        '</li>';
                }
            }

            $('ul.link-list').html(html);
        },

        error: function(){}
    });
});

