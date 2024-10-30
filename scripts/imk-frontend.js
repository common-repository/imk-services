'use strict';

(function($){

    function serialize (obj) {
        var str = [];
        for (var p in obj)
            if (obj.hasOwnProperty(p)) {
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
            }
        return str.join("&");
    }

    function QueryStringToJSON() {
        var pairs = location.search.slice(1).split('&');

        var result = {};
        pairs.forEach(function(pair) {
            pair = pair.split('=');
            result[pair[0]] = decodeURIComponent(pair[1] || '');
        });

        return JSON.parse(JSON.stringify(result));
    }

    function getInventory( elem ){
        var queryParam = QueryStringToJSON();
        var ClickedElem =  elem;
        var limit = ClickedElem.data('postLimit');
        var totalCount = ClickedElem.data('postTotalCount');
        var presentCount = $('.imk-single-post-for-count').length;
        //
        var queryString = serialize( Object.assign( {'limit': limit, 'skip': presentCount} , queryParam ) );
        var $dataContainer = ClickedElem.data('postContainer');
        var $url = IMK_OBJECT.WEBSITE_API+ "/getInventory?"+queryString ;
        $.ajax({
            type: "GET",
            url: $url,
            success: function(response){
                if( response ){
                    if(response.list){
                        var UIHTML = renderInventoryUI( response.list );
                        $( $dataContainer ).append( UIHTML );
                        refreshMatchHeight();
                        var NewpresentCount = $('.imk-single-post-for-count').length;
                        if( totalCount <= NewpresentCount ){
                            return;
                        } else {
                            scrollLoad = true;
                        }

                    }
                }
            },
            error: function(){
                console.error("something went wrong");
            }
        });
    }

    function renderInventoryUI( inventories ){
        var listStr = "";
        inventories.forEach( function( inventory ) {

            var $title = inventory.year + " " + inventory.make + " " + inventory.model;
            var $price = 0;
            var $specialPrice = 0;
            if (inventory.otherPrice && inventory.otherPrice != inventory.price) {
                $price = '$' + inventory.otherPrice;
                $specialPrice = '$' + inventory.price;
            } else {
                $price = '$' + inventory.price;
            }
            var CarImage = IMK_OBJECT.IMK_PLUGIN_URL+"images/car-placeholder.jpg";
            var imageCount = 0;
            if( inventory.images && inventory.images.images.length ){
                CarImage = inventory.images.images[0];
                imageCount = inventory.images.images.length;
            }

            listStr += `
                    <div class="col-sm-6 col-md-4">
                        <div class="imk-listing-car-item-inner match-height imk-single-post-for-count">
                            <a href="${IMK_OBJECT.HOME_URL}/inventory-detail/${$title}-${inventory._id}" class="rmv_txt_drctn" title='${$title}'>
                                <div class="text-center inventory-image-container">
                                    <div class="imk-inventory-image">
                                        <img src="${CarImage}" data-retina="${CarImage}" class="img-responsive">
                                    </div>
                                    <div class="image-counter">
                                        <img src="${IMK_OBJECT.IMK_PLUGIN_URL}images/photo-camera.png" alt="total images" width="18px"><b> ${imageCount}</b>
                                    </div>`;

            if ($specialPrice) {
                listStr += `<div class="special-price">
                Offer  <b> ${$specialPrice}</b>
                </div>`;
            }

            listStr += `</div>
                                <div class="imk-listing-car-item-meta">
                                    <div class="imk-car-meta-top">
                                        <div class="inventory-price">
                                            <div class="imk-normal-price"> ${$price}</div>
                                        </div>
                                        <div class="imk-car-title"> ${$title} </div>
                
                                    </div>
                                    <div class="imk-car-meta-bottom form-group">
                                        <ul>
                
                                            <li title="Inventory ID">
                                                <b >#</b>
                                                <span>${ inventory.stockNumber }</span>
                                            </li>
                                            <li title="Mileage">
                                                <i class="stm-icon-road"></i>
                                                <span>${ inventory.mileage }</span>
                                            </li>
                                            <li title="VIN">
                                                <b>VIN</b>
                                                <span>${inventory.VIN}</span>
                                            </li>
                                            <li title="Exterior Color">
                                                <b>Ext Color</b>
                                                <span> ${inventory.exteriorColor} </span>
                                            </li>
                                            <li title="Interior Color">
                                                <b>Int Color</b>
                                                <span> ${inventory.interiorColor} </span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="third-party-services">`;
            if (inventory.VIN){

                listStr += `<a href="https://www.carfax.com/cfm/ccc_DisplayHistoryRpt.cfm?vin=${inventory.VIN}" target="_blank" class="carfax service-container">
                            <img src="${IMK_OBJECT.IMK_PLUGIN_URL}images/download.png" />
                        </a>`;
            }

            listStr+=`
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

             `;
        });

        return listStr;
    }


    var scrollLoad = true;
    $(window).scroll(function () {
        if (scrollLoad && $(window).scrollTop() >= $(document).height() - $(window).height() - 300) {
            scrollLoad = false;
            console.log("scroll");
            var elemBtn = $("#load_imk_inventory_btn");
            if( elemBtn.length ){
                getInventory( elemBtn );
            }

        }
    });


    function refreshMatchHeight(){
        $('.match-height').matchHeight();
    }
    refreshMatchHeight();


    function _alert(alertContainer){
        this.elem = alertContainer ? $(alertContainer) : $('.contact-message');
        this.reset = function(){
            this.elem.removeClass(' alert-danger alert-success alert-info hide');
        }
        this.error = function(message){
            this.reset();
            this.elem.addClass( 'alert-danger' ).html(message);
            this.hide();
        }
        this.success = function(message){
            this.reset();
            this.elem.addClass( 'alert-success' ).html(message);
            this.hide();
        }
        this.info = function(message){
            console.log("message", message);
            this.reset();
            this.elem.addClass( 'alert-info' ).html(message);
            this.hide();
        }
        var that = this;
        this.hide = function(){
            setTimeout(function(){
                that.elem.addClass(' hide');
                that.elem.html('');
            }, 8000)
        }
    }

    $.validate({
        form : '#financeForm',
        onSuccess : function($form) {
            var alrt = new _alert('.finance-message');
            financeForm($form, alrt);
            return false; // Will stop the submission of the form
        }
    });

    $.validate({
        form : '#imk_trade_appraisal',
        onSuccess : function($form) {
            var alrt = new _alert('.imk-appraisal-messag');
            appraisalMessagForm($form, alrt);
            return false; // Will stop the submission of the form
        }
    });

    $.validate({
        form : '#locateCar',
        onSuccess : function($form) {
            var alrt = new _alert('.locateCar-message');
            LocateCarForm($form, alrt);
            return false; // Will stop the submission of the form
        }
    });

    $.validate({
        form : '#schedule_test_drive_form',
        onSuccess : function($form) {
            var alrt = new _alert('.schedule_test_drive_form_message');
            scheduleTestDrive($form, alrt);
            return false; // Will stop the submission of the form
        }
    });
    $.validate({
        form : '#imk_contact',
        onSuccess : function($form) {
            var alrt = new _alert('.imk-contact-message');
            contactForm($form, alrt);
            return false; // Will stop the submission of the form
        }
    });

    function contactForm( $form,  $alert ){
        $alert.info("Message Sending...");
        event.preventDefault();
        var params = $form.serializeArray();
        var data = {
            meta:{},
            subject: ' Contact-Us form submitted ',
            message: ''
        };

        params.forEach(function( item){
            data.meta[ item.name ] = item.value;
            if(data.meta.emailId){
                data.message = "Contact-us form submitted by "+ data.meta.emailId
            }
        });

        http_request( $form, $alert, data, "contact-form" );
    }

    function scheduleTestDrive( $form,  $alert ){
        $alert.info("Message Sending...");
        event.preventDefault();
        var params = $form.serializeArray();
        var data = {
            meta:{},
            subject: 'Mail for schedule a test drive ',
            message: ''
        };

        params.forEach(function( item){
            data.meta[ item.name ] = item.value;
            if(data.meta.emailId){
                data.message = "Schedule a test drive request received by "+ data.meta.emailId
            }
        });

        http_request( $form, $alert, data, "schedule-a-test-drive" );
    }

    function appraisalMessagForm( $form,  $alert ){
        $alert.info("Message Sending...");
        event.preventDefault();
        var params = $form.serializeArray();
        var data = {
            meta:{},
            subject: 'Mail for Trade Appraisal ',
            message: ''
        };

        params.forEach(function( item){
            data.meta[ item.name ] = item.value;
            if(data.meta.emailId){
                data.message = "Trade Appraisal form submitted by "+ data.meta.emailId
            }
        });

        http_request( $form, $alert, data, "trade-appraisal" );
    }


    function LocateCarForm($form, $alert){
        $alert.info("Message Sending...");
        event.preventDefault();
        var params = $form.serializeArray();
        var data = {
            meta:{},
            subject: 'Mail from Locate a Car Page ',
            message: ''
        };

        var checkboxes = document.getElementsByName('feature[]');
        var features = "";
        for (var i=0, n=checkboxes.length;i<n;i++)
        {
            if (checkboxes[i].checked)
            {
                features += ","+checkboxes[i].value;
            }
        }
        if (features) features = features.substring(1);

        params.forEach(function( item){
            data.meta[ item.name ] = item.value;
            if(data.meta.emailId){
                data.message = "Received request for Locate a car by "+ data.meta.emailId
            }
            if( data.meta['feature[]'] ){
                delete  data.meta['feature[]'] ;
                data.meta['features'] = features;
            }
        });
        http_request( $form, $alert, data, "locateCar" );
    }

    function financeForm($form, $alert){
        $alert.info("Message Sending...");
        event.preventDefault();
        var params = $form.serializeArray();
        var data = {
            meta:{},
            subject: 'Mail from Finance Page ',
            message: ''
        };

        params.forEach(function( item){
            data.meta[ item.name ] = item.value;
            if(data.meta.emailId){
                data.message = "Finance form submitted by "+ data.meta.emailId
            }
            if(data.meta.applicationType != "Joint Application"){
                delete data.meta.relationshipCode;
                delete data.meta.otherRelationship;
            }
        });

        http_request( $form, $alert, data, "finance" );
    }

    function http_request($form, $alert, $data, $type){
        var $url = IMK_OBJECT.IMK_API_URL + '/api/v1/cta/' + IMK_OBJECT.IMK_USER_ID + '/' + IMK_OBJECT.IMK_GROUP_ID +'?type='+ $type;
        console.log( $url, $data, $type);
        $.ajax({
            type: "post",
            url: $url,
            data: $data,
            success: function(response){
                $alert.success("Mail Sent!");
                $form.get(0).reset();
            },
            error: function(){
                $alert.error("Something went wrong!");
            }
        });
    }

    $(document).ready(function(){

        var imageLoader = $(".image-loader");
        if( imageLoader.length ){
            var laderImage = IMK_OBJECT.IMK_PLUGIN_URL+"images/imageloader.gif";
            var noImage = "data:image/png;base64,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";
            imageLoader.each(function(index, elemment){
                var NewImage = new Image();
                var oldSrc ;
                switch (elemment.tagName) {
                    case 'IMG':
                        oldSrc = elemment.src;
                        elemment.setAttribute("src", laderImage);
                        NewImage.src = oldSrc;
                        NewImage.onload = function(){
                            elemment.src = this.src;
                        };
                        NewImage.onerror = function(){
                            elemment.src = noImage;
                        };

                        break;
                    case 'DIV':
                        var oldBG = elemment.style.backgroundImage;
                        oldSrc = oldBG.replace('url(','').replace(')','').replace(/\"/gi, "");
                        elemment.style.backgroundImage =  "url('"+ laderImage +"')";
                        NewImage.src = oldSrc;
                        NewImage.onload = function(){
                            elemment.style.backgroundImage = "url('"+ this.src +"')";
                        };
                        NewImage.onerror = function(){
                            elemment.style.backgroundImage = "url('"+ noImage +"')";
                        };
                        break;
                }

            })
        }
        var queryParams = QueryStringToJSON();
        if( queryParams.makeName ){
            $('.on-chnage-get-models').trigger("change");
        }

        var big = $('.stm-big-car-gallery');
        if( big.length ){
            var small = $('.stm-thumbs-car-gallery');
            var flag = false;
            var duration = 800;

            var owlRtl = false;
            if( $('body').hasClass('rtl') ) {
                owlRtl = true;
            }

            big.owlCarousel({
                items: 1,
                rtl: owlRtl,
                smartSpeed: 800,
                dots: false,
                nav: true,
                navText: ['', ''],
                navClass: ['owl-nav-btn owl-left-btn', 'owl-nav-btn owl-right-btn'],
                margin:0,
                autoplay: false,
                loop: false,
                responsiveRefreshRate: 1000
            }).on('changed.owl.carousel', function (e) {
                $('.stm-thumbs-car-gallery .owl-item').removeClass('current');
                $('.stm-thumbs-car-gallery .owl-item').eq(e.item.index).addClass('current');
                if (!flag) {
                    flag = true;
                    small.trigger('to.owl.carousel', [e.item.index, duration, true]);
                    flag = false;
                }
            });

            small.owlCarousel({
                items: 5,
                rtl: owlRtl,
                smartSpeed: 800,
                dots: false,
                margin: 22,
                autoplay: false,
                nav: true,
                loop: false,
                navText: [],
                responsiveRefreshRate: 1000,
                responsive:{
                    0:{
                        items:2
                    },
                    500:{
                        items:4
                    },
                    768:{
                        items:5
                    },
                    1000:{
                        items:5
                    }
                }
            }).on('click', '.owl-item', function(event) {
                big.trigger('to.owl.carousel', [$(this).index(), 400, true]);
            }).on('changed.owl.carousel', function (e) {
                if (!flag) {
                    flag = true;
                    big.trigger('to.owl.carousel', [e.item.index, duration, true]);
                    flag = false;
                }
            });

            $(document).keydown( function(eventObject) {
                if(eventObject.which==37) {//left arrow
                    $('.owl-left-btn').click();//emulates click on prev button
                } else if(eventObject.which==39) {//right arrow
                    $('.owl-right-btn').click();//emulates click on next button
                }
            } );

            // if($('.stm-thumbs-car-gallery .stm-single-image').length < 6) {
            //     $('.stm-single-car-page .owl-controls').hide();
            //     $('.stm-thumbs-car-gallery').css({'margin-top': '22px'});
            // }
        }

        var priceSlider = $('#price-slider');

        if( priceSlider.length ){
            var minValue = queryParams.minPrice ? queryParams.minPrice : 5000;
            var maxValue = queryParams.maxPrice ? queryParams.maxPrice : 50000;
            var minPrice = _c("INPUT", {type:'hidden', value:0, name:'minPrice' });
            var maxPrice = _c("INPUT", {type:'hidden', value:0, name:'maxPrice' });
            priceSlider.append(minPrice);
            priceSlider.append(maxPrice);

            priceSlider.slider({
                range: true,
                min: 5000,
                max: 100000,
                step: 1000,
                values: [ minValue, maxValue ],
                slide: function( event, ui ) {
                    // console.log(ui.values);
                    updatePrice( priceSlider,  ui.values );
                }
            });

            updatePrice(priceSlider, priceSlider.slider('values'));


            // noinspection JSAnnotator
            function updatePrice( slider, values ){

                if(minPrice){
                    minPrice.value = values[0];
                }
                if(maxPrice){
                    maxPrice.value = values[1];
                }
                var showPrice = $(slider.data('showPrice'));
                showPrice.html(
                    "<b>$"+values[0] + " - " + "$"+values[1] + "</b>"
                );
            }
        }



    });

    $('.on-chnage-get-models').on('change', function(){
        var elem = $(this);
        var makeName = elem.val();
        var dataContainer = $(elem.data('container'));
        dataContainer.attr('disabled', true);
        var $url = IMK_OBJECT.WEBSITE_API+ "/modelList?make="+ makeName;
        $.ajax({
            type: "GET",
            url: $url,
            success: function(response){
                if( response ){
                    if(response.list){

                        dataContainer.html('');
                        var _default_option = _c("OPTION", { value: '' , innerText: "Any Model" });
                        dataContainer.append( _default_option );
                        for ( var l in response.list ){
                            var _option = _c("OPTION", { value: response.list[l], innerText: response.list[l] });
                            dataContainer.append(_option);
                        }
                        dataContainer.attr('disabled', false);

                    }
                }
            },
            error: function(){
                console.error("something went wrong");
            }
        });


    })

    function getModels( makeName ){
        console.log("makeName", makeName);
    }
    function _c(elem, properties, child){
        var elemObj = document.createElement(elem);
        for( var propName in properties ) {
            if( propName == 'innerText' ){
                elemObj.innerHTML = properties[propName]
            } else {
                elemObj.setAttribute( propName, properties[propName] );
            }

        }
        return elemObj;
    }

	$("[name='applicationType']").on('change', function (e){
		if( e.target.value == "Joint Application" ){
			$("#relationship").show();
		} else {
			$("#relationship").hide();
		}
	});



})(jQuery)