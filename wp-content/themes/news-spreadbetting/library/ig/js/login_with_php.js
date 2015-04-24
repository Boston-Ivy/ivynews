
//set these variables with a valid watchlist name and times for update frequency and delay
//var watchlistName = "5 Minute Binaries";
var bidOfferUpdateFrequency = 0.2; // type 1 for 1 second, 0.5 for 2 seconds, 0.2 for 5 seconds, 0.1 for 10 seconds
var marketChangeTime = 10000; //in miliseconds

//global variables
var urlRoot = connectionData.urlRoot;
var apiKey = connectionData.apiKey;
var account_token = connectionData.account_token;
var client_token = connectionData.client_token;
var watchlist_id = connectionData.watchlist_id;
var accountId = connectionData.accountId;
var lsEndpoint = connectionData.lsEndpoint;

var instrumentItemNames = new Array();
var instrumentEpicNames = new Array();
var appended = false;
var addCurentClass = false;
var nrMarket = 0;
var currentMarketId = 0;

alert(urlRoot);
// var removed_name_spinner = false;
// var removed_byd_spinner = false;
// var removed_offer_spinner = false;

// document.getElementById("count_byd").style.visibility="hidden";
// document.getElementById("count_offer").style.visibility="hidden";



function watchlist_market_bid_offer_values() {

   var watchlist = encodeURIComponent(watchlist_id);
   var req = new Request();
   req.method = "GET";
   req.url = urlRoot + "/watchlists/" + watchlist;

   req.headers = {
      "X-IG-API-KEY": apiKey,
      "X-SECURITY-TOKEN": account_token,
      "CST": client_token,
      "Content-Type": "application/json; charset=UTF-8",
      "Accept": "application/json; charset=UTF-8"
   };

   // Send the request via a Javascript AJAX call
   try {
      $.ajax({
         type: req.method,
         url: req.url,
         data: req.body,
         headers: req.headers,
         async: false,
         mimeType: req.binary ? 'text/plain; charset=x-user-defined' : null,
         error: function (response, status, error) {
            // An unexpected error occurred
            handleHTTPError(response);
         },
         success: function (response, status, data) {
/*
         console.log("This is the response: ");
         console.log(response);*/

            var epicsItems = new Array(response.markets.length);
            /*console.log(response.markets);*/
            nrMarket = response.markets.length;
            var epicsItemsArrayIndex = 0;

            $(response.markets).each(function (index) {
               //console.log("--------> " + index);
               var responseData = response.markets[index];
               var epic = responseData.epic;
               var canSubscribe = responseData.streamingPricesAvailable;


               if (canSubscribe) {
                  /*console.log("---------------------------- " + epicsItemsArrayIndex);*/
                  var div = new Array (
                     // '<div class="well well-lg ' + epicsItemsArrayIndex + '" style="visibility:hidden" id="market' + epicsItemsArrayIndex + '">',
                     //    '<div class="counters">',
                           '<div class="row" id="market' + epicsItemsArrayIndex + '">',
                              '<div class="col-xs-3 col-xs-offset-1">',
                                    '<div class="counter_byd" id="counter_byd' + epicsItemsArrayIndex + '">',
                                       '<span class="title">BYD</span>',
                                          '<div class="spinner" id="spinner-byd' + epicsItemsArrayIndex + '">',
                                             '<div class="double-bounce1"></div>',
                                             '<div class="double-bounce2"></div>',
                                          '</div>',
                                          '<div class="hidden odometer numberholder count_byd" id="count_byd' + epicsItemsArrayIndex + '">',
                                          '</div>',
                                          '<!-- .numberholder -->',
                                    '</div>',
                                    '<!-- #counter_byd -->',
                              '</div>',
                              '<!-- col-sm-2 -->',
                              '<div class="col-xs-4">',
                                    '<div class="counter_market" id="counter_market' + epicsItemsArrayIndex + '">',
                                       '<span class="title">Market</span>',
                                          '<div class="spinner" id="spinner-market' + epicsItemsArrayIndex + '">',
                                             '<div class="double-bounce1"></div>',
                                             '<div class="double-bounce2"></div>',
                                          '</div>',
                                          '<div class="hidden numberholder market_name" id="market_name' + epicsItemsArrayIndex + '">',
                                             //responseData.instrumentName,
                                          '</div>',
                                          '<!-- .numberholder -->',
                                    '</div>',
                                    '<!-- #counter_byd -->',
                              '</div>',
                              '<!-- col-sm-4 -->',
                              '<div class="col-xs-3">',
                                    '<div class="counter_offer" id="counter_offer' + epicsItemsArrayIndex + '">',
                                       '<span class="title">Offer</span>',
                                          '<div class="spinner" id="spinner-offer' + epicsItemsArrayIndex + '">',
                                             '<div class="double-bounce1"></div>',
                                             '<div class="double-bounce2"></div>',
                                          '</div>',
                                          '<div class="hidden odometer numberholder count_offer" id="count_offer' + epicsItemsArrayIndex + '">',
                                          '</div>',
                                        '<!-- .numberholder -->',
                                    '</div>',
                                  '<!-- #counter_byd -->',
                              '</div>',
                              '<!-- col-md-2 -->',
                           '</div>',
                          '<!-- row -->'
                     //    '</div>',
                     //  '<!-- #counters -->',
                     // '</div>'
                     );
                  var new_div = div.join("");

/*                  if(!appended) {
                     $(new_div).appendTo("#counters");
                     appended = true;
                  } else { */
                     $(new_div).appendTo("#counters");
                  /* } */

                  instrumentItemNames[epicsItemsArrayIndex] = responseData.instrumentName;
                  instrumentEpicNames[epicsItemsArrayIndex] = responseData.epic;
                  epicsItems[epicsItemsArrayIndex] = "MARKET:" + responseData.epic;
                  /*console.log("epicsItems at Index " + epicsItemsArrayIndex + ": " + epicsItems[epicsItemsArrayIndex]);*/
                  epicsItemsArrayIndex += 1;
               }
               else if (!canSubscribe) {
                  console.log("Subscription denied for market: " + responseData.instrumentName);
               }
            });

            /*console.log(instrumentEpicNames);*/

            // Now subscribe to the BID and OFFER prices for each position market
            if (epicsItemsArrayIndex > 0) {
               epicsItems.length = epicsItemsArrayIndex;

               require(["Subscription"], function (Subscription) {

                  var subscription = new Subscription(
                     "MERGE",
                     epicsItems,
                     [
                        "BID",
                        "OFFER"
                     ]
                  );

                  // Set up Lightstreamer event listener
                  subscription.addListener({
                     onSubscription: function () {
                        console.log('subscribed');
                     },
                     onSubscriptionError: function (code, message) {
                        console.log('subscription failure: ' + code + " message: " + message);
                     },
                     onItemUpdate: function (updateInfo) {
                        var epic = updateInfo.getItemName().split(":")[1];
                        $(instrumentEpicNames).each(function (index) {

                           if (epic == instrumentEpicNames[index]) {
                              /*console.log("***************************");
                              console.log(instrumentEpicNames[index]);
                              console.log(epic);
                              console.log(instrumentItemNames[index]);
                              console.log(instrumentItemNames[index] + ": ");*/

                              $('#market_name' + index).text(instrumentItemNames[index]);
                              $('#spinner-market' + index).remove();
                              $('#market_name' + index).removeClass('hidden');


                              updateInfo.forEachField(function (fieldName, position, value) {

                                 if (fieldName == 'BID') {
                                    $('#count_byd' + index).text(value);
                                    $('#spinner-byd' + index).remove();
                                    $('#count_byd' + index).removeClass('hidden');

                                 }
                                 else if (fieldName == 'OFFER') {
                                    $('#count_offer' + index).text(value);
                                    $('#spinner-offer' + index).remove();
                                    $('#count_offer' + index).removeClass('hidden');
                                 }
                                 /*console.log(fieldName + " " + value);*/
                                 $('#counters #placeholder').remove();
                                 if(addCurentClass == false)
                                 {
                                    $("#market0").addClass("currentMarket");
                                    addCurentClass = true;
                                 }

                              });
                              /*console.log("***************************");*/
                           }
                        });
                     }
                  });
                  subscription.setRequestedMaxFrequency(bidOfferUpdateFrequency);

                  // Subscribe to Lightstreamer
                  lsClient.subscribe(subscription);

                  numberOfDivs = instrumentItemNames.length;
                  // var visibleMarket = $("#counters .row").attr("id");
                  // console.log("-------> " + visibleMarket);
                  // var invisibleMarket = $("#hidden_counters").find("#market2");
                  // $(invisibleMarket).appendTo("#counters");
                  var nextId = 1;
                  if (numberOfDivs > 1) {
                     setInterval(function () {
                           //var visibleDivId = $(".visible").attr("id");
                           var visibleMarket = $("#counters .row").attr("id");
                           var visibleMarketId = parseInt(visibleMarket.match(/(\d+)/g));
                           nextId = visibleMarketId + 1;
                           var invisibleMarket = $("#market" + nextId);

                           /*console.log("-----------------------");
                           console.log("currentMarketId: " + currentMarketId);
                           console.log("nrMarket: " + nrMarket);
                           console.log("-----------------------");*/
                           $(".currentMarket").removeClass('currentMarket');
                           currentMarketId++;
                           if(currentMarketId == nrMarket)
                           {
                                 currentMarketId = 0;
                           }
                           $("#market"+currentMarketId).addClass("currentMarket");
                           /*$("#" + visibleMarket).removeClass('currentMarket');

                           if (visibleMarketId === numberOfDivs-1) {
                              nextId = 0;
                              invisibleMarket = $("#market0");
                              $(invisibleMarket).addClass('currentMarket');
                           } else {
                              visibleMarketId++;
                              $(invisibleMarket).addClass('currentMarket');
                              //$(invisibleMarket).appendTo("#counters").fadeIn();
                           }*/


                     }, marketChangeTime);
                  }
               //subscription end
               });
            }
         }
      });
   } catch (e) {
      handleException(e);
   }
}




function market_bid_offer_values() {

   var req = new Request();
   req.method = "GET";
   req.url = urlRoot + "/positions";

   req.headers = {
      "X-IG-API-KEY": apiKey,
      "X-SECURITY-TOKEN": account_token,
      "CST": client_token,
      "Content-Type": "application/json; charset=UTF-8",
      "Accept": "application/json; charset=UTF-8"
   };

   // Send the request via a Javascript AJAX call
   try {
      $.ajax({
         type: req.method,
         url: req.url,
         data: req.body,
         headers: req.headers,
         async: false,
         mimeType: req.binary ? 'text/plain; charset=x-user-defined' : null,
         error: function (response, status, error) {
            // An unexpected error occurred
            handleHTTPError(response);
         },
         success: function (response, status, data) {

            var epicsItems = new Array(response.positions.length);
            var epicsItemsArrayIndex = 0;
            $(response.positions).each(function (index) {
               var positionData = response.positions[index];
               var epic = positionData.market.epic;
               var canSubscribe = positionData.market.streamingPricesAvailable;



               if (removed_name_spinner === false) {
                  document.getElementById("spinner-market").remove();
                  removed_name_spinner = true;
               }
               market_name.innerHTML = positionData.market.instrumentName;

               if (canSubscribe) {
                  epicsItems[epicsItemsArrayIndex] = "L1:" + positionData.market.epic;
                  /*console.log("epicsItems at Index " + epicsItemsArrayIndex + ": " + epicsItems[epicsItemsArrayIndex]);*/
                  epicsItemsArrayIndex += 1;
               }
            });

            // Now subscribe to the BID and OFFER prices for each position market
            if (epicsItemsArrayIndex > 0) {
               epicsItems.length = epicsItemsArrayIndex;
               require(["Subscription"], function (Subscription) {

                  var subscription = new Subscription(
                     "MERGE",
                     epicsItems,
                     [
                        "BID",
                        "OFFER",
                        "MARKET_STATE"
                     ]
                  );

                  // Set up Lightstreamer event listener
                  subscription.addListener({
                     onSubscription: function () {
                        console.log('subscribed');
                     },
                     onSubscriptionError: function (code, message) {
                        console.log('subscription failure: ' + code + " message: " + message);
                     },
                     onItemUpdate: function (updateInfo) {

                        var epic = updateInfo.getItemName().split(":")[1];
                        updateInfo.forEachField(function (fieldName, fieldPos, value) {
                           var fieldId = epic.replace(/\./g, "_") + "_" + fieldName;
                           var cell = $("." + fieldId);
                            setTimeout(function(){
                                if (fieldName == "BID") {
                                    if (removed_byd_spinner === false) {
                                       document.getElementById("spinner-byd").remove();
                                       document.getElementById("count_byd").style.visibility="visible";
                                       removed_byd_spinner = true;
                                    }
                                    count_byd.innerHTML = value;
                                }
                                else if (fieldName == "OFFER") {
                                    if (removed_offer_spinner === false) {
                                       document.getElementById("spinner-offer").remove();
                                       document.getElementById("count_offer").style.visibility="visible";
                                       removed_offer_spinner = true;
                                    }
                                    count_offer.innerHTML = value;
                                }
                            }, 100);
                            /*console.log(fieldName + ': ' + value);*/
                        });
                     }
                  });
                  subscription.setRequestedMaxFrequency(0.2);
                  // Subscribe to Lightstreamer
                  lsClient.subscribe(subscription);
               });
            }
         }
      });
   } catch (e) {
      handleException(e);
   }
}

function connectToLightstreamer() {

   require(["LightstreamerClient"], function (LightstreamerClient) {

      // Instantiate Lightstreamer client instance
      /*console.log("Connecting to Lighstreamer: " + lsEndpoint);*/
      lsClient = new LightstreamerClient(lsEndpoint);

      // Set up login credentials: client
      lsClient.connectionDetails.setUser(accountId);

      var password = "";
      if (client_token) {
         password = "CST-" + client_token;
      }
      if (client_token && account_token) {
         password = password + "|";
      }
      if (account_token) {
         password = password + "XST-" + account_token;
      }
      /*console.log(" LSS login " + accountId + " - " + password);*/
      lsClient.connectionDetails.setPassword(password);

      // Add connection event listener callback functions
      lsClient.addListener({
         onListenStart: function () {
            /*console.log('Lightstreamer client - start listening');*/
         },
         onStatusChange: function (status) {
            console.log('Lightstreamer connection status:' + status);
         }
      });

      // Connect to Lightstreamer
      lsClient.connect();
   });
}


function Request(o) {
   this.headers = {"Content-Type": "application/json; charset=UTF-8", "Accept": "application/json; charset=UTF-8"};
   this.body = "";
   this.method = "";
   this.url = "";
}

function handleException(exception) {
   $("#response_data").text(exception);
   $("#alertStatusCode").text("unknown");
   try {
      var responseJson = jQuery.parseJSON(response.responseText);
      $("#alertErrorCode").text(responseJson.errorCode);
   } catch (e) {
      $("#alertErrorCode").text(exception);
   }
   $("#errorAlert").show();
}


function handleHTTPError(response) {
   $("#response_data").text(js_beautify(response.responseText));
   $("#alertStatusCode").text(response.status);
   try {
      var responseJson = jQuery.parseJSON(response.responseText);
      $("#alertErrorCode").text(responseJson.errorCode);
   } catch (e) {
      $("#alertErrorCode").text(response.responseText);
   }
   $("#errorAlert").show();
}


connectToLightstreamer();
//market_bid_offer_values();
watchlist_market_bid_offer_values();



