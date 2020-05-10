jQuery(document).ready(function($) {
    $(".nano").nanoScroller({
        scroll: 'top',
    });

    $(".feedback").nanoScroller({
        scroll: 'top',
        alwaysVisible: true
    });

    $(".nano-pane").css("display", "block");
    $(".nano-slider").css("display", "block");

});


var clickd_tags;
var post_files = {};
/* @@Rage --- function to remove element from object */
function arrayRemove(arr, value) {
    return arr.filter(function(ele) {
        return ele != value;
    });
}

jQuery(document).ready(function($) {
    /* @@Rage -- Tag click function */
    var tags = [""];
    var opts = [];

    $(document).on('click', '.nom', function(x) {

        $(this).toggleClass('selFilter');

        var nom_val = $(this).text().trim();
        if ($(this).hasClass("selFilter")) {
            opts.push(nom_val);
        } else {
            var resul = arrayRemove(opts, nom_val);
            opts = resul;
        }

        let res = JSON.stringify(opts);
        clickd_tags = btoa(res);
        /*comments(btoa(res));*/
        comments(clickd_tags);
    });

});



let def_lat = -1.2967913;
let def_lng = 36.8598615;

var map = L.map('map', {
    center: [def_lat, def_lng],
    minZoom: 0,
    zoom: 11,
    maxZoom: 80,
    // gestureHandling: true
    scrollWheelZoom: true
});

// Prevent map from zooming on mouse move
/*map.on('focus', function() {if (map.scrollWheelZoom.enabled()) {map.scrollWheelZoom.disable();} else {map.scrollWheelZoom.enable();}});*/

// Add MarkerClusters - Kevin
function getRandomLatLng(map) {
    var bounds = map.getBounds(),
        southWest = bounds.getSouthWest(),
        northEast = bounds.getNorthEast(),
        lngSpan = northEast.lng - southWest.lng,
        latSpan = northEast.lat - southWest.lat;

    return new L.LatLng(
        southWest.lat + latSpan * Math.random(),
        southWest.lng + lngSpan * Math.random());
}

var markers = L.markerClusterGroup();

var mopt = {
    url: '//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    options: {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        subdomains: ['a', 'b', 'c'],
        id: 'mapbox.light'
    }
};

var mq = L.tileLayer(mopt.url, mopt.options);
mq.addTo(map);

var layer_main = L.layerGroup();
var layer_streets = L.layerGroup();

// Define polyline options
// http://leafletjs.com/reference.html#polyline		
var polyline_options = {
    color: '#9F5CCB',
    weight: 7,
    opacity: 0.8
};


let map_data;

var layer_postsMarkersList = [];
let postsMarkersObject = {};
let mapPosition;

function getColor(d) {
    return d > 80 ? 'violet' : d > 60 ? 'green' : d > 40 ? 'yellow' : d > 20 ? 'orange' : 'red';
}

function mapCenter() {
    map.setView([def_lat, def_lng], 11);
}


function AddMarkerToMap(ma_point, ma_layer, ma_color = 'grey') {

    //console.log(ma_point);
    //var ma_color = (ma_type === 'indoor') ? 'blue' : 'green';


    var my_coord = '' + ma_point.lat + ', ' + ma_point.lng + '';
    var my_photo_link = (ma_point.photo !== '') ? '' + ma_point.photo_original + '' : '';
    var my_photo = (my_photo_link !== '') ? '<hr class="pop_line"><a href="' + my_photo_link + '" target="_blank" class="pop_photo"><img src="' + my_photo_link + '" style="width:50px;" /></a>' : '';
    var my_comments = (ma_point.comments !== '') ? '<b>Details:</b> ' + ma_point.comments + '' : '';
    var my_icon = '';
    var my_link = ' data-href="maps/nuru_point.php?id=' + ma_point.id + '" id="marka_' + ma_point.id + '" title="View Point Details" rel="modal:open"';

    var my_popup = '<div><b class="pop_title"> ' + ma_point.name + '</b><br /><b>Entry Tags:</b> ' + ma_point.tags + '<br /><b>From:</b> ' + ma_point.post_by + '<br />' + my_comments + '  ' + my_photo + ' </div>';


    var greenIcon = new L.Icon({
        iconUrl: 'assets/image/marker-icon-green.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34]
            /*,
                          shadowSize: [41, 41]*/
    });
    // Li.circleMarker 

    /* @@ Rage -- Add Title, riseOnHover, markerID, classname */
    var newMarker = new L.marker([parseFloat(ma_point.lat), parseFloat(ma_point.lng)], {
            icon: greenIcon,
            title: ma_point.name,
            riseOnHover: 1,
            markerID: ma_point.id,
            className: 'marker_' + ma_point.id
        })
        .bindPopup(my_popup).on('click', clickZoom);
    /*.on('click', clickZoom)*/

    /* @@ Rage -- Add Markers to markersArray */
    postsMarkersObject['marker_' + ma_point.id] = newMarker;
    layer_postsMarkersList.push(newMarker);
    ma_layer.addLayer(newMarker);

    /*newMarker._popup.setLatLng(map.getBounds().getCenter());*/

    /* @@ Rage -- Add layer to map */
    return map.addLayer(ma_layer);
}


/* @@ Rage -- POST CLICK IN-MAP POPUP */
function clickZoom(e) {
    map.setView(e.target.getLatLng(), 11);
}

function openPopupCustom(marker_id) {
    jQuery(document).ready(function($) {
        postsMarkersObject["" + marker_id + ""].openPopup();
        /*jQuery("#map").animate({scrollTop: 0}, 800);*/
        var feedbackDetailContent = $('#feedbackDetailContent').offset().top;
        mapPosition = feedbackDetailContent - $('#custom-map').offset().top;
    });
}




function rageMappa(de_file, de_layer, de_color) {

    jQuery(document).ready(function($) {

        $.ajax({
            type: 'get',
            url: de_file,
            dataType: 'json',
            success: function(data) {

                var mapsData = data; /*//JSON.parse(data);*/
                var markers_a = mapsData.features;
                map_data = mapsData.table;

                for (var i = 0; i < markers_a.length; i++) {
                    var ma_point = markers_a[i].properties;
                    var m_color = getColor(ma_point['perc_access']);

                    AddMarkerToMap(ma_point, de_layer, m_color);
                }

                rageTable(map_data, "Posts List", 1);

            }
        });
    });

}

rageMappa('maps/nuru_json.php', layer_main, 'indoor');


function rageTable(tbl_data, tbl_title, $level) {

    jQuery(document).ready(function($) {
        var table_res = $.makeTable(tbl_data, tbl_title, $level);
        $("#box_res_table").html('');
        $(table_res).appendTo("#box_res_table");
        zul_DataTable();
    });
}


// Display the results as comments - Kevin 30th Mar 2020
var data = '';

function comments(data, nchi) {

    var country_sel = $('#countryFormControlSelect1 option:selected').val();
    let tg = (clickd_tags !== undefined) ? clickd_tags : '';
    let e = (typeof(data) !== 'undefined') ? data : tg;
    let country = (typeof(nchi) !== 'undefined') ? nchi : country_sel;

    let link = "maps/nuru_json.php?tag=" + e + "&country=" + country + "&tk=" + Math.random();
    // alert(link);

    jQuery(document).ready(function($) {
        $.ajax({
            type: 'get',
            url: link,
            dataType: 'json',

            success: function(output) {
                // console.log(output);
                let data = output.features;
                let len = data.length;
                let content = '';

                let lat = '';
                let lng = '';

                for (var c = 0; c < len; c++) {
                    let record_id = data[c].properties.id;
                    /*let comment = data[c].properties.comments;*/
                    let comment = data[c].properties.comments_trim;
                    var comment_full = data[c].properties.comments;
                    let author = data[c].properties.post_by;
                    let time = data[c].properties.name;

                    let lat = data[c].properties.lat;
                    let lng = data[c].properties.lng;
                    let tags = data[c].properties.tags;

                    let photo = data[c].properties.photo;
                    let photo_original = data[c].properties.photo_original;

                    let country = data[c].properties.country;
                    let flag = data[c].properties.country_flag;
                    // Find audio files
                    let attachment = JSON.parse(data[c].properties.otherfiles);

                    if (attachment.length > 0) {
                        // console.log("attacho", attachment);
                        post_files['comment_' + record_id] = attachment;
                    }
                    /**/
                    // Iterate through second level array
                    var masauti = '';

                    for (var at = 0; at < attachment.length; at++) {

                        masauti += attachment[at]['3gp'];
                    }
                    // console.log(masauti);

                    // alert(photo.length);
                    var img = '';
                    var imgx = '';

                    if (photo.length !== 0) {
                        /*img = '<img src="'+ photo +'" alt="Image from '+ author +'" style="width: 90%; margin: auto">';*/
                        img = '<a class="comment-img" href="' + photo_original + '" target="_blank"> <img src="' + photo + '" alt="Image from ' + author + '" width="50" height="50"></a>';
                    }

                    var findOnMap = '';
                    var findOnMapx = '';


                    //console.log("lat", lat.length + " - " + data[c].properties.lat + " - long: " + data[c].properties.lng);

                    if (lat != "0") {
                        var lbl_country = '';
                        if (country.length !== 0) {
                            lbl_country = '<span class="country_meta"><span class="ico_flag" style="background: url(' + flag + ') no-repeat;background-size:contain;"></span> ' + country + '</span> &middot ';
                        }
                        findOnMap = '<p>' + lbl_country + '<a class="feed_markers" lat="' + lat + '" lng="' + lng + '" data-parent-id="comment_' + record_id + '" data-id="marker_' + record_id + '" ><em> <strong><i class="far fa-dot-circle"></i> Find on map</strong></em></a>';
                    }

                    var tags_text = '';
                    if (tags !== undefined && tags.length > 3) {
                        tags_text = '<p class="padd10_t txt12"> <smallx><em><strong>Tags: </strong><span class="tagisi"> ' + tags + '</span></em></smallx> </p>';
                    }

                    // check if we have audio files - Kevin update May 2020
                    // var audio_file = '';

                    // if (masauti !== undefined && masauti.length > 1) {

                    //     audio_file = '&middot; <span class="padd10_t txt12 audio" data-href="' + masauti + '"> <smallx><em> <strong><i class="fas fa-volume-up"></i> Audio </strong></em></smallx> </span></p>';
                    // }




                    /* <hr/> <p>'+ img +'</p> */
                    content += '<article class="comment" id="comment_' + record_id + '">' + img + '<div class="comment-body"> <div class="text"><p>' + comment + '<br/><a class="more"> More Details</a> </p>  </div> <p class="attribution"> <span class="date"><i class="far fa-clock"></i> ' + time + '</span> &middot; <i class="fas fa-user-alt"></i> <a href="#non">' + author + '</a>   ' + findOnMapx + '</a></p> ' + tags_text + '</div> <div class="hide full_comment">' + comment_full + '</div> ' + findOnMap + ' </article>';
                }

                // console.log(data);
                $('section.comments').html(content + '<p>&nbsp;</p><p>&nbsp;</p>');

                // alert(data);
                // alert(data.features.0.properties.name);

            }
        });
    });

}

/*console.log('post_files', post_files);*/
//setInterval(comments(data), 100);

// Pan to selected coordinate




jQuery(document).ready(function($) {

    comments("");
    jQuery(document).on('click', '.feed_markers', function(e) {
        var parent_id = jQuery(this).attr("data-parent-id");
        var marker_id = jQuery(this).attr("data-id");
        jQuery("#" + parent_id).click();
        openPopupCustom(marker_id);
    });

    /* Kevin Update - Change the way content is revealed on the map side on click	*/
    jQuery(document).on('click', '.comment', function(data) {

        let mapColPos = $('#mapCol').offset().top;

        let op = $(this).find('.attribution a').first().text(); /*feedback from original poster*/
        let message = $(this).find('.full_comment').html();
        // let message = comment_full;
        let date = $(this).find('.date').html();
        let tags = $(this).find('.tagisi').text();
        let country_meta = $(this).find('.country_meta').html();

        let img = $(this).find('.comment-img').attr('href');
        let marker = $(this).find('.feed_markers').attr('data-id');
        let marker_comm_id = $(this).find('.feed_markers').attr('data-parent-id');

        let attachos = '';
        let attachos_arr = (post_files[marker_comm_id] !== undefined) ? post_files[marker_comm_id] : '';
        //console.log("clicked ", post_files[marker_comm_id]);

        if (attachos_arr !== '') {
            attachos += '<h4>Attached Files</h4>';
            attachos_arr.forEach(function(i, j) {
                var f_url = attachos_arr[j]._url;
                var f_type = attachos_arr[j]._type;

                if (f_type == 'a') {
                    attachos += '<div class="imageDetail_b"><figure><figcaption>...</figcaption><audio controls autoplay src="' + f_url + '">  Your browser does not support the <code>audio</code> element. </audio></figure></div>';
                } else {
                    attachos += '<div class="imageDetail_b"><a href="' + f_url + '" target="_blank"><img src="' + f_url + '" width="200px" /></a> </div>';
                }

            });
        }

        if (attachos == '' && img !== undefined) {

            attachos += '<div class="imageDetail_b"><a href="' + img + '" target="_blank"><img src="' + img + '" width="200px" /></a> </div>';
        }

        let image = '';
        let p_country = '';
        if (img !== undefined) {
            image = '<div class="imageDetail"><a href="' + img + '" target="_blank"><img src="' + img + '" /></a> </div> <hr/>';
        };

        if (country_meta !== undefined) {
            p_country = '<div class=""><span><strong>Country: </strong></span><span>' + country_meta + '</span></div>';
        };

        if (tags !== undefined && tags.length > 3) {
            tags = '<span class="tags"><strong>Tags: </strong>' + tags + '</span><br/>';
        };

        let lat = $(this).find('.feed_markers').attr('lat');
        let lng = $(this).find('.feed_markers').attr('lng');

        let mapLocator = '';

        if (lat !== undefined || lng !== undefined) {
            mapLocator = '<div id="custom-map"></div> <hr/>';
        } else {
            mapLocator = 'Location information for ' + op + ' is unavailable';
        }

        // Get audiofile from extra params
        let audiofile = $(this).find('.audio').attr('data-href');

        /*if(audiofile !== undefined ){
            audiofile = '<div id="audio"> <figure><figcaption>Listen to '+op+'\'s audio:</figcaption><audio controls autoplay src="'+audiofile+'">  Your browser does not support the <code>audio</code> element. </audio></figure></div> <hr/>';
        }else{
            audiofile = '';
        }*/

        // Social media sharing
        // Twitter

        let tweetUrl = 'https://twitter.com/intent/tweet?text=' + message + '. Posted by: ' + op + '&url=https://nuru.live&hashtags=NuruLive';
        let tweet = encodeURI(tweetUrl);

        // Facebook
        let fbHref = encodeURI('https://nuru.live');
        let fbHashtag = encodeURI('NuruLive');
        let fbMsg = encodeURI(message + ' Posted by: ' + op);
        let fb = 'https://www.facebook.com/dialog/share?app_id=249749043050014&display=popup&href=' + fbHref + '&hashtag=' + fbHashtag + '&quote=' + fbMsg;


        let content = '';
        content += '<div class="containerx feedbackx nanox feedbackDetailWrap" >';
        content += '<div class="nano-content" id="feedbackDetailContent">';
        //content += '<div> <a class="back"> <i class="fas fa-long-arrow-alt-left"></i> Back to Map</a></div>';
        content += '<h4>' + op + ' wrote: </h4>';
        content += '<div class="row message"><p>' + message + '</p></div> <hr/>';
        content += '<div clas="row"><div clas="wrap_imageDetail">' + attachos + '</div></div>';
        //content += '' + audiofile + '';
        //content += image;
        content += '<div class="row metadata padd20_t">';
        content += '<span class="date"><strong>Date Posted: </strong>' + date + '</span><br/>';
        content += '' + tags + '';
        content += '' + p_country + '';
        content += '</div> <hr/>';
        content += '<h5> Location </h5>';
        content += mapLocator;
        content += '<div class="social-share">Share ' + op + '\'s post: <a href="' + tweet + '" target="_blank"><i class="fab fa-2x fa-twitter-square"></i></a> &nbsp; <a href="' + fb + '" target="_blank"><i class="fab fa-2x fa-facebook-square"></i></a></div>';
        content += '</div'; //close nano content
        content += '</div'; //close container

        $('.mapTitle').text('Feedback from ' + op);
        $('#feedbackDetailBack').html('<div> <a class="back txtgray txt14"> <i class="fas fa-long-arrow-alt-left"></i> Back to Map</a></div>');

        /*$('#map').css('background', '#ffffff');
        $('#map').html(content);*/
        $('#map').hide();

        $('#feedbackDetail').html(content).show();


        /* @@Rage -- GET latlng AND ADD TO MAP */
        let f_markers = $(this).find('.feed_markers');
        // let lat = '';
        // let lng = '';

        if (f_markers.length > 0) {
            lat = f_markers.attr('lat'); /*$(this).find('.feed_markers').attr('lat');*/
            lng = f_markers.attr('lng'); /*$(this).find('.feed_markers').attr('lng');*/

            // Add this to map
            var customMap = L.map('custom-map').setView([lat, lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(customMap);

            var markerIcon = new L.Icon({
                iconUrl: 'assets/image/marker-icon-green.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            });

            L.marker([lat, lng], {
                icon: markerIcon,
                title: op,
                riseOnHover: 1
            }).addTo(customMap).bindPopup(op + '\'s location').openPopup();
        }
        /* END @@Rage -- GET latlng AND ADD TO MAP */

        $(".comment").removeClass("commSelect");
        $(this).addClass("commSelect");

        jQuery(document).scrollTop(mapColPos);

        /* Get me out of here */
        $('.back').click(function() {
            // $(".comment").removeClass("commSelect"); //Removed this so user can go back and still see the class - Kevin

            $('#map').show();
            $('#feedbackDetail').html("").hide();
            mapCenter();
            $('.mapTitle').text('Nuru Map');

            // Hide the back to map title
            $('.back').hide();
            //if(marker != undefined) { openPopupCustom(marker); }
            /*location.reload();*/
        });

        // Change comment background color
        /*$(this).find('.text').css('background-color', 'rgba(57, 181, 74, .3)');*/


    });

});
// End of on click to change content display



jQuery(document).ready(function($) {

    $('.panel-heading span.clickable').each(function() {
        var $this = $(this);
        if ($this.hasClass('panel-collapsed')) {
            $this.parents('.panel').find('.panel-body').slideUp();
            $this.find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
        }
    });

    jQuery(document).on('click', '.panel-heading span.clickable', function(e) {
        var $this = $(this);
        if (!$this.hasClass('panel-collapsed')) {
            $this.parents('.panel').find('.panel-body').slideUp();
            $this.addClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
        } else {
            $this.parents('.panel').find('.panel-body').slideDown();
            $this.removeClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-plus').addClass('glyphicon-minus');
        }
    })

    /*$(document).on('change', '.gg_checks', function(e) {
        gg_data_search(layer_main, 'indoor');
    });*/

});

function gg_data_search(de_layer, de_color) {
    jQuery(document).ready(function($) {
        //alert($('#frm_search').serialize());
        //map.remove(de_layer);
        de_layer.clearLayers();
        /*map.eachLayer(function (layer) { console.log("layer", layer);
            //map.removeLayer(layer)
        }); */

        $(".gg_checks").each(function() {
            var label = $(this).parent();
            if ($(this).prop('checked')) {
                label.css('color', 'red');
            } else {
                label.css('color', '#777777');
            }
        });

        $.ajax({
            /*url: 'map_ajdata.php?tk='+Math.random(),*/
            url: 'maps/nuru_json.php?tk=' + Math.random(),
            type: 'get',
            dataType: 'json',
            data: $('#frm_search').serialize(),
            beforeSend: function() {
                $('#gg_data_result').html('loading <img src="assets/images/icons/a-loader.gif" alt="..."  />');
            },
            success: function(data) {

                $('#gg_data_result').html(data);

                var mapsData = data; //JSON.parse(data);

                var markers_a = mapsData.features;

                for (var i = 0; i < markers_a.length; i++) {
                    var ma_point = markers_a[i].properties;
                    var m_color = getColor(ma_point['perc_access']);
                    AddMarkerToMap(ma_point, de_layer, m_color);
                }

            }
        });
    });
}


function kbModalLoaded() {}



function zul_DataTable() {
    jQuery(document).ready(function($) {

        //alert("Ninii " + groupTotals);

        /*//DATA TABLE*/
        $.fn.dataTable.moment('MMM D YYYY');
        $.fn.dataTable.moment('YYYY-MMMM');
        $.fn.dataTable.moment('YYYY-MMM');
        $.fn.dataTable.moment('YYYY-MMM-DD');

        var tb_grouped = $('#gg_data_tb').length;

        if ($('table.display').length) {

            var col_filter_tag = (jQuery('#dt_example').length && jQuery('#dt_example').attr('data-col-filter') !== undefined) ? jQuery('#dt_example').attr('data-col-filter') : "";
            var col_filter = (col_filter_tag !== "") ? col_filter_tag.split(",") : "";

            var col_total_tag = (jQuery('#dt_example').length && jQuery('#dt_example').attr('data-col-total') !== undefined) ? jQuery('#dt_example').attr('data-col-total') : "";
            var col_total = (col_total_tag !== "") ? col_total_tag.split(",") : "";

            dta_table = jQuery('table.display').dataTable({
                "bProcessing": true,
                destroy: true,
                "bJQueryUI": true,
                "bInfo": true,
                "sPaginationType": "full_numbers",
                "bStateSave": false
                    /*, "columnDefs": [{"targets" : 'no-sort', "orderable": false }, { "render": function ( data, type, full, meta ){ return display_decimal(data) }, "targets" : 'ddt' }]*/
                    ,
                "aaSorting": [],
                "iDisplayLength": 10,
                "aLengthMenu": [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, "All"]
                ],
                "scrollX": true,
                dom: 'Blfrtip',
                buttons: ['print', 'csvHtml5'],
                initComplete: function() {
                    var num_cols = this.api().columns().nodes().length;

                    if (col_filter !== "") {
                        this.api().columns().every(function(tb_col) {
                            var column = this;
                            var col_id = col_filter.includes(tb_col.toString());

                            if (col_id === true) {
                                var select = $('<select><option value=""></option></select>')
                                    .appendTo($(column.footer()) /*.empty()*/ )
                                    .on('change', function() {
                                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                                    });

                                column.data().unique().sort().each(function(d, j) {
                                    select.append('<option value="' + d + '">' + d + '</option>')
                                });
                            }
                        });
                    }


                },
                drawCallback: function() {
                    var api = this.api();
                    if (col_total !== "") {
                        this.api().columns().every(function(tb_col) {
                            var column = this;
                            var col_id = col_total.includes(tb_col.toString());

                            if (col_id === true) {
                                var col_sum = api.column(tb_col.toString(), {
                                    page: 'current'
                                }).data().sum();
                                $(column.footer()).html(display_decimal(col_sum));
                                /*$( api.table().footer() ).html(
                                    api.column( tb_col.toString(), {page:'current'} ).data().sum()
                                  );*/
                            }
                        });
                    }
                }
            });

        }



        if ($('#check_all').length) {
            $('#check_all').on("change", function() {
                if ($(this).is(':checked')) {
                    $('.dtb_chk').each(function() {
                        $(this).attr("checked", true);
                    });
                } else {
                    $('.dtb_chk').each(function() {
                        $(this).attr("checked", false);
                    });
                }
            });
        }

    });
}

function responsiveDigs() {
    jQuery(document).ready(function($) {
        winwidth = $(window).width();
        if (winwidth < 1000) {
            jQuery('.accord-title').addClass("collapsed");
            jQuery('.panel-collapse').addClass("collapse").removeClass("in");
        }
    });
}



//On Change, select country and show coordinates for the capital city

jQuery(document).ready(function($) {
    // Pan to user's country of visit
    if ($('#countryFormControlSelect1').val() == 0) {
        $.ajax({
            url: 'https://api.ipgeolocation.io/getip',
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                $('.selCountry').html('loading country info...');
            },
            success: function(d) {
                // alert(d.ip);
                // Start reverse ip to get country then change
                $.ajax({
                    url: 'https://ipwhois.pro/json/' + d.ip + '?key=your-api-key-20200509',
                    type: 'get',
                    dataType: 'json',
                    success: function(ip) {
                        // console.log(ip);
                        // alert(ip.country_name);
                        // Change the viewing from to this country
                        $('.selCountry').text(ip.country);



                        let ipLat = ip.latitude;
                        let ipLng = ip.longitude;

                        if (ip.country === 'Kenya') {
                            map.setView(new L.LatLng(ipLat, ipLng), 6);
                        } else {
                            map.setView(new L.LatLng(ipLat, ipLng), 3);
                        }


                        // Send an ajax request to refresh an include

                        $.ajax({
                            url: 'map_filter_side_country.php?country=' + ip.country,
                            type: 'get',
                            success: function(k) {
                                // reload the page
                                $('.map_filters').html(k);
                            }
                        });

                        // Change viewing from country
                        $('.selCountry').text(ip.country);

                        // Change feedback to match country
                        comments('', ip.country);

                        // refresh in intervals
                        jQuery(window).on("load", function($) {
                            responsiveDigs();
                            jQuery(window).resize(function() { responsiveDigs(); });
                            var myVar = setInterval(comments('', ip.country), 10000);
                        });

                    }
                });
                // End reverse ip to get country then change


            }
        });


    }

    $('#countryFormControlSelect1').on('change', function() {
        var country = $(this).val();
        // alert(country);
        if (country == 'All') {
            $('.selCountry').text('All Countries');
            comments("", '%');

            // refresh in intervals
            jQuery(window).on("load", function($) {
                responsiveDigs();
                jQuery(window).resize(function() { responsiveDigs(); });
                var myVar = setInterval(comments('', country), 10000);
            });
        } else {

            // Gather map details from liquidIQ

            $.ajax({
                url: 'https://eu1.locationiq.com/v1/search.php?key=56ac2c8eb93fbb&q=' + country + '&format=json',
                type: 'get',
                dataType: 'json',
                data: $('#frm_search').serialize(),
                beforeSend: function() {
                    $('.selCountry').html('loading country info...');
                },
                success: function(d) {
                    // console.log(d);
                    // Change the viewing from to this country
                    $('.selCountry').text(country);


                    var countryData = d; //JSON.parse(d);

                    let countryLat = countryData[0].lat;
                    let countryLng = countryData[0].lon;

                    // alert(countryLat +','+ countryLng);

                    // Take our people to the new map center based on country
                    // Introduce a condition for smaller countries to be visible eg Kenya
                    if (country === 'Kenya') {
                        map.setView(new L.LatLng(countryLat, countryLng), 6);
                    } else if (country === 'All') {
                        map.setView(new L.LatLng(countryLat, countryLng), 1);
                    } else {
                        map.setView(new L.LatLng(countryLat, countryLng), 3);
                    }

                    // Send an ajax request to refresh an include

                    $.ajax({
                        url: 'map_filter_side_country.php?country=' + country,
                        type: 'get',
                        success: function(k) {
                            // reload the page
                            $('.map_filters').html(k);



                        }
                    });

                    comments('', country);

                    // refresh in intervals
                    jQuery(window).on("load", function($) {
                        responsiveDigs();
                        jQuery(window).resize(function() { responsiveDigs(); });
                        var myVar = setInterval(comments('', country), 10000);
                    });



                }
            });

            //end if statement
        }
        //end if statement
    });

});


//  jQuery(window).on("load", function ($) { 
//  	responsiveDigs();
//  	jQuery(window).resize(function() { responsiveDigs(); });
// 	var myVar = setInterval(comments, 10000);
//  });