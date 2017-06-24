<script type="text/javascript" src="<?php echo base_url() ?>assets/amcharts/amcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/amcharts/serial.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/amcharts/pie.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/amcharts/themes/light.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/amcharts/plugins/export/export.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/amcharts/plugins/responsive/responsive.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/country_codes.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/amcharts/plugins/export/export.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/jquery-ui-1.8.21.custom.css">
<div id="fb-root"></div>
<script type="text/javascript">
  var base_url = '<?php echo base_url() ?>index.php/';
  var graph_data = [];
  //angular code    
  var angapp = angular.module('angapp', []);
  angapp.controller('pageController', function($scope, $http, $sce){
    $scope.page_id = '<?php echo $page->page_id ?>';
    $scope.fb_app_id = '<?php echo $page_app_id ?>';
    $scope.page_fb_id = '<?php echo $page->page_fb_id ?>';
    $scope.token_loaded = false;
    $scope.sdk_loaded = false;
    $scope.metrics = [];
    $scope.periods = [];
    $scope.metrics_loaded = false;
    $scope.token = '';
    $scope.alert = "";
    $scope.selected_metric = "";
    $scope.selected_period = "";
    $scope.metric_since = "";
    $scope.metric_until = "";
    $scope.graph_preloader = false;
    $scope.thirty_days_ago = '<?php echo date('Y-m-d', strtotime('-30 days')) ?>';
    //Get Page token
    $http.get(base_url + 'api/page/token/' + $scope.page_id ).then(function(result) {
      $scope.token = result.data.token;
      if($scope.sdk_loaded)
        $scope.load_stories();
      else
        $scope.token_loaded = true;
      return $http.get(base_url + 'api/analytics/all' );
    }).then(function(result){
      $scope.metrics = result.data;
      $scope.metrics_loaded = true;
    });

    $scope.load_period = function() // advanced tab metric period dropdown
    {
      if($scope.selected_metric == "")
      {
        $scope.periods = [];
        $scope.selected_period = ""
      }
      for(var i=0; i < $scope.metrics.length; i++)
      {
        if($scope.metrics[i].metric_name == $scope.selected_metric)
        {
          $scope.periods = $scope.metrics[i].metric_period;
          $scope.selected_period = $scope.metrics[i].metric_period[0];
        }
      }
    };

    $scope.load_graph = function()
    {
      $scope.graph_preloader = true;
      var params = {access_token : $scope.token};
      edge = '/' + $scope.page_fb_id + '/insights/' + $scope.selected_metric + '/' + $scope.selected_period;
      if($scope.metric_since != "")
        params.since = $scope.metric_since;
      if($scope.metric_until != "")
        params.until = $scope.metric_until;
      $scope.call_api(edge, params);
    }
    //Load FB JS SDK
    window.fbAsyncInit = function() {
      FB.init({
        appId: $scope.fb_app_id,
        status: false,
        cookie: false,
        xfbml: false,
        version : 'v2.6'
      });
      if($scope.token_loaded)
        $scope.load_stories();
      else
        $scope.sdk_loaded = true;
    };
    (function(d, s, id){
       var js, fjs = d.getElementsByTagName(s)[0];
       if (d.getElementById(id)) {return;}
       js = d.createElement(s); js.id = id;
       js.src = "//connect.facebook.net/en_US/sdk.js";
       fjs.parentNode.insertBefore(js, fjs);
     }(document, 'script', 'facebook-jssdk'));

    $scope.show_alert = function(alert){
      $scope.alert = $sce.trustAsHtml(alert);
    };

    $scope.call_api = function(edge, params)
    {
      FB.api(edge, 'get', params, function(response){
        if(response.error){
          remove_chart();
          $scope.show_alert("<div class='alert alert-danger'>"+response.error.message+"</div>");
        }
        else if(response.data.length == 0){
          remove_chart();
          $scope.show_alert("<div class='alert alert-danger'><?php echo $this->lang->line('error_no_data_returnend') ?></div>");
        }
        else{
          $scope.show_alert('');
          render_chart(response);
        }
        $scope.graph_preloader = false;
        $scope.$digest();
      });      
    }

    $scope.load_stories = function()
    {
      $scope.token_loaded = true;
      $scope.sdk_loaded = true;
      $scope.$apply();
      var params = {
        batch : [
            {
              method : 'GET',
              relative_url : '/me/insights/page_stories_by_story_type/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_storytellers/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_storytellers_by_age_gender/days_28'
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_storytellers_by_country/days_28'
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_storytellers_by_city/days_28'
            }

        ],
        access_token : $scope.token
      }
      FB.api('/', 'POST', params, function(response){
        if(response.error)
          $scope.show_alert("<div class='alert alert-danger'>"+response.error.message+"</div>");
        render_stories(response);
        $scope.stories_loaded = true;
        $scope.$apply();
        $scope.load_reach();
      }); 
    }

    $scope.load_reach = function()
    {
      var params = {
        batch : [
            {
              method : 'GET',
              relative_url : '/me/insights/page_impressions_unique/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_impressions_by_story_type_unique/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_impressions_by_age_gender_unique/days_28'
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_impressions_by_country_unique/days_28'
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_impressions_by_city_unique/days_28'
            }

        ],
        access_token : $scope.token
      }
      FB.api('/', 'POST', params, function(response){
        if(response.error)
          $scope.show_alert("<div class='alert alert-danger'>"+response.error.message+"</div>");
        render_reach(response);
        $scope.reach_loaded = true;
        $scope.$apply();
        $scope.load_engagement();
      }); 
    }

    $scope.load_engagement = function()
    {
      var params = {
        batch : [
            {
              method : 'GET',
              relative_url : '/me/insights/page_engaged_users/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_consumptions_by_consumption_type_unique/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_positive_feedback_by_type_unique/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_negative_feedback_by_type_unique/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_fans_online_per_day/day?since=' + $scope.thirty_days_ago
            }
        ],
        access_token : $scope.token
      }
      FB.api('/', 'POST', params, function(response){
        if(response.error)
          $scope.show_alert("<div class='alert alert-danger'>"+response.error.message+"</div>");
        render_engagement(response);
        $scope.engagement_loaded = true;
        $scope.$apply();
        $scope.load_reactions();
      }); 
    }
    $scope.load_reactions = function()
    {
      var params = {
        batch : [
            {
              method : 'GET',
              relative_url : '/me/insights/page_actions_post_reactions_total/day?since=' + $scope.thirty_days_ago
            }
        ],
        access_token : $scope.token
      }
      FB.api('/', 'POST', params, function(response){
        if(response.error)
          $scope.show_alert("<div class='alert alert-danger'>"+response.error.message+"</div>");
        render_reactions(response);
        $scope.reactions_loaded = true;
        $scope.$apply();
        $scope.load_cta_clicks();
      }); 
    }
    $scope.load_cta_clicks = function()
    {
      var params = {
        batch : [
            {
              method : 'GET',
              relative_url : '/me/insights/page_cta_clicks_logged_in_unique/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_cta_clicks_by_site_logged_in_unique/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_cta_clicks_by_age_gender_logged_in_unique/days_28'
            }
        ],
        access_token : $scope.token
      }
      FB.api('/', 'POST', params, function(response){
        if(response.error)
          $scope.show_alert("<div class='alert alert-danger'>"+response.error.message+"</div>");
        render_cta_clicks(response);
        $scope.cta_clicks_loaded = true;
        $scope.$apply();
        $scope.load_fans();
      }); 
    }
    $scope.load_fans = function()
    {
      var params = {
        batch : [
            {
              method : 'GET',
              relative_url : '/me/insights/page_fans_by_like_source_unique/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_fans_by_unlike_source_unique/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_fans_gender_age/lifetime'
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_fans_country/lifetime'
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_fans_city/lifetime'
            }
        ],
        access_token : $scope.token
      }
      FB.api('/', 'POST', params, function(response){
        if(response.error)
          $scope.show_alert("<div class='alert alert-danger'>"+response.error.message+"</div>");
        render_fans(response);
        $scope.fans_loaded = true;
        $scope.$apply();
        $scope.load_views();
      }); 
    }
    $scope.load_views = function()
    {
      var params = {
        batch : [
            {
              method : 'GET',
              relative_url : '/me/insights/page_views_total/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_views_external_referrals/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_views_by_profile_tab_total/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_views_by_site_logged_in_unique/day?since=' + $scope.thirty_days_ago
            }
        ],
        access_token : $scope.token
      }
      FB.api('/', 'POST', params, function(response){
        if(response.error)
          $scope.show_alert("<div class='alert alert-danger'>"+response.error.message+"</div>");
        render_views(response);
        $scope.views_loaded = true;
        $scope.$apply();
        $scope.load_videos();
      }); 
    }
    $scope.load_videos = function()
    {
      var params = {
        batch : [
            {
              method : 'GET',
              relative_url : '/me/insights/page_video_views/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_video_complete_views_30s/day?since=' + $scope.thirty_days_ago
            }
        ],
        access_token : $scope.token
      }
      FB.api('/', 'POST', params, function(response){
        if(response.error)
          $scope.show_alert("<div class='alert alert-danger'>"+response.error.message+"</div>");
        render_videos(response);
        $scope.videos_loaded = true;
        $scope.$apply();
        $scope.load_posts();
      }); 
    }
    $scope.load_posts = function()
    {
      var params = {
        batch : [
            {
              method : 'GET',
              relative_url : '/me/insights/page_posts_impressions_viral/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_posts_impressions_viral_unique/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_posts_impressions_by_paid_non_paid/day?since=' + $scope.thirty_days_ago
            },
            {
              method : 'GET',
              relative_url : '/me/insights/page_posts_impressions_by_paid_non_paid_unique/day?since=' + $scope.thirty_days_ago
            }
        ],
        access_token : $scope.token
      }
      FB.api('/', 'POST', params, function(response){
        if(response.error)
          $scope.show_alert("<div class='alert alert-danger'>"+response.error.message+"</div>");
        render_posts(response);
        $scope.posts_loaded = true;
        $scope.$apply();
      }); 
    }
  });


var chart;
  // AM CHARTS code
  function get_am_graphs(keys)
  {
    if(keys.length == 0) return;
    var graphs = [];
    for(var i = 0; i < keys.length; i ++)
    {
      graphs.push({
          "id": "g" + i + 1,
          "bullet": "round",
          "bulletBorderAlpha": 1,
          "bulletColor": "#FFFFFF",
          "bulletSize": 5,
          "hideBulletsCount": 50,
          "lineThickness": 2,
          "title": parse_code(keys[i]),
          "useLineColorForBulletBorder": true,
          "valueField": keys[i],
          "balloonText" : "<span style='font-size:14px; color:#000000;'><b>" + parse_code(keys[i]) + " : [[" + keys[i] + "]]</b></span>"
      });
    }
    return graphs;
  }

  function get_graph_title(title)
  {
    var titles =  [
        {
          "text": title,
          "size": 15
        }
      ];     
    return titles;
  }

  function get_graph_description(desc)
  {
    var description =  [
        {
          "text": desc,
          "size": 12,
          "align" : 'center',
          "y" : 35
        }
      ];
    return description;     
  }

  function get_chart_config(){
    return {
      "type": "serial",
      "theme": "light",
      "marginRight": 40,
      "marginLeft": 60,
      "autoMarginOffset": 20,
      "mouseWheelZoomEnabled":true,
      "dataDateFormat": "YYYY-MM-DD",
       "responsive": { "enabled": true },
      "valueAxes": [{
          "id": "g1",
          "axisAlpha": 0,
          "position": "left",
          "ignoreAxisWidth":true
      }],  
      "chartScrollbar": {
          "graph": "g1",
          "oppositeAxis":false,
          "offset":30,
          "scrollbarHeight": 80,
          "backgroundAlpha": 0,
          "selectedBackgroundAlpha": 0.1,
          "selectedBackgroundColor": "#888888",
          "graphFillAlpha": 0,
          "graphLineAlpha": 0.5,
          "selectedGraphFillAlpha": 0,
          "selectedGraphLineAlpha": 1,
          "autoGridCount":true,
          "color":"#AAAAAA"
      },
      "chartCursor": {
        "bulletsEnabled" : true,
        "enabled" : true,
        "animationDuration" : 0.1
      },
      "valueScrollbar":{
        "oppositeAxis":false,
        "offset":50,
        "scrollbarHeight":10
      },
      "categoryField": "date",
      "categoryAxis": {
          "parseDates": true,
          "dashLength": 1,
          "minorGridEnabled": true
      },
      "export": {
          "enabled": true
      },
      "legend": {
            "useGraphSettings": true,
            "align" : 'center',
            "periodValueText" : "[[value.sum]]"
      }
    };
  }

  function remove_chart()
  {
    if(typeof chart !== 'undefined' &&  chart != null)
    {
      chart.clear();
      chart = null;
    }
  }

  function render_chart(response)
  {
    remove_chart();
    var keys = [];
    var key_counts = [];
    var graph_title = response.data[0].title;
    var graph_description = response.data[0].description;
    var edge_name = response.data[0].name;
    
    if(typeof response.data[0].values[0].value === 'object') // multi graphs
    {  
      for(var i = 0; i< response.data[0].values.length; i++)
      {
        for (var key in response.data[0].values[i].value) {
          if (response.data[0].values[i].value.hasOwnProperty(key)) {
            response.data[0].values[i][key] = response.data[0].values[i].value[key];
            if(keys.indexOf(key) === -1){
              keys.push(key);
              key_counts[key] = parseInt(response.data[0].values[i].value[key]);
            }else
              key_counts[key] += parseInt(response.data[0].values[i].value[key]);
          }
        }
        response.data[0].values[i]['date'] = response.data[0].values[i].end_time;
        delete response.data[0].values[i].value;
        delete response.data[0].values[i].end_time;
      }
      if((edge_name.indexOf('country') > -1 || edge_name.indexOf('city') > - 1))
      {
        var tuples = [];
        for (var key in key_counts) tuples.push([key, key_counts[key]]);
        tuples.sort(function(a, b) {
            a = a[1];
            b = b[1];
            return a > b ? -1 : (a < b ? 1 : 0);
        });
        var length = tuples.length > 10 ? 10 : tuples.length;
        keys = [];
        for (var i = 0; i < length; i++) keys.push(tuples[i][0]);
      }
    }
    else{ //single graph
      for(var i = 0; i< response.data[0].values.length; i++)
      {
        response.data[0].values[i]['date'] = response.data[0].values[i].end_time;
        response.data[0].values[i][graph_title] = response.data[0].values[i].value;
        delete response.data[0].values[i].end_time;
        delete response.data[0].values[i].value;
      }
      keys.push(graph_title);
    }
    var chart_config = get_chart_config();
    chart_config.graphs = get_am_graphs(keys);
    chart_config.titles = get_graph_title(graph_title);
    chart_config.allLabels = get_graph_description(graph_description);
    chart_config.dataProvider = response.data[0].values;
    //console.log(chart_config.dataProvider);
    chart = AmCharts.makeChart("chart_advanced", chart_config);
    
    return chart;
    //chart.validateData();
  }
  function render_stories(response)
  {
    var div;
    var container = $('#stories .x_content');
    var graphs = [];
    var stories = JSON.parse(response[0].body);
    var storytellers = JSON.parse(response[1].body);
    var storytellers_age = JSON.parse(response[2].body);
    var storytellers_country = JSON.parse(response[3].body);
    var storytellers_city = JSON.parse(response[4].body);
    if(stories.data.length)
    {
      graphs = parse_serial_graph_data(stories);
      div = $('<div></div>');
      div.attr('id', 'page_stories_by_story_type');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_stories_by_story_type', graphs, stories.data[0].title, stories.data[0].description, stories.data[0].values);
    }
    if(storytellers.data.length)
    {
      graphs = parse_serial_graph_data(storytellers);
      div = $('<div></div>');
      div.attr('id', 'page_storytellers');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_storytellers', graphs, storytellers.data[0].title, storytellers.data[0].description, storytellers.data[0].values);
    }
    if(storytellers_age.data.length)
    {
      var age_data = parse_age_graph_data(storytellers_age);
      graphs = ['male', 'female'];
      div = $('<div></div>');
      div.attr('id', 'page_storytellers_by_age_gender');
      div.css('height', '500px');
      container.append(div);
      render_age_chart('page_storytellers_by_age_gender', graphs, storytellers_age.data[0].title, storytellers_age.data[0].description, age_data);
    }
    if(storytellers_country.data.length)
    {
      var chart_data = parse_pie_graph_data(storytellers_country);
      div = $('<div></div>');
      div.attr('id', 'page_storytellers_by_country');
      div.css('height', '500px');
      container.append(div);
      render_pie_chart('page_storytellers_by_country', storytellers_country.data[0].title, storytellers_country.data[0].description, chart_data);
    }
    if(storytellers_city.data.length)
    {
      var chart_data = parse_pie_graph_data(storytellers_city);
      div = $('<div></div>');
      div.attr('id', 'page_storytellers_by_city');
      div.css('height', '500px');
      container.append(div);
      render_pie_chart('page_storytellers_by_city', storytellers_city.data[0].title, storytellers_city.data[0].description, chart_data);
    }
    return;
  }
  function render_reach(response)
  {
    var div;
    var container = $('#reach .x_content');
    var graphs = [];
    var reach = JSON.parse(response[0].body);
    var reach_story_type = JSON.parse(response[1].body);
    var reach_by_age = JSON.parse(response[2].body);
    var reach_by_country = JSON.parse(response[3].body);
    var reach_by_city = JSON.parse(response[4].body);
    if(reach.data.length)
    {
      graphs = parse_serial_graph_data(reach);
      div = $('<div></div>');
      div.attr('id', 'page_impressions_unique');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_impressions_unique', graphs, reach.data[0].title, reach.data[0].description, reach.data[0].values);
    }
    if(reach_story_type.data.length)
    {
      graphs = parse_serial_graph_data(reach_story_type);
      div = $('<div></div>');
      div.attr('id', 'page_impressions_by_story_type_unique');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_impressions_by_story_type_unique', graphs, reach_story_type.data[0].title, reach_story_type.data[0].description, reach_story_type.data[0].values);
    }
    if(reach_by_age.data.length)
    {
      var age_data = parse_age_graph_data(reach_by_age);
      graphs = ['male', 'female'];
      div = $('<div></div>');
      div.attr('id', 'page_impressions_by_age_gender_unique');
      div.css('height', '500px');
      container.append(div);
      render_age_chart('page_impressions_by_age_gender_unique', graphs, reach_by_age.data[0].title, reach_by_age.data[0].description, age_data);
    }
    if(reach_by_country.data.length)
    {
      var chart_data = parse_pie_graph_data(reach_by_country);
      div = $('<div></div>');
      div.attr('id', 'page_impressions_by_country_unique');
      div.css('height', '500px');
      container.append(div);
      render_pie_chart('page_impressions_by_country_unique', reach_by_country.data[0].title, reach_by_country.data[0].description, chart_data);
    }
    if(reach_by_city.data.length)
    {
      var chart_data = parse_pie_graph_data(reach_by_city);
      div = $('<div></div>');
      div.attr('id', 'page_impressions_by_city_unique');
      div.css('height', '500px');
      container.append(div);
      render_pie_chart('page_impressions_by_city_unique', reach_by_city.data[0].title, reach_by_city.data[0].description, chart_data);
    }
  }
  function render_engagement(response)
  {
    var div;
    var container = $('#engagement .x_content');
    var graphs = [];
    var engaged_user = JSON.parse(response[0].body);
    var page_consumptions = JSON.parse(response[1].body);
    var positive_feedback = JSON.parse(response[2].body);
    var negative_feedback = JSON.parse(response[3].body);
    var fans_online = JSON.parse(response[4].body);
    if(engaged_user.data.length)
    {
      graphs = parse_serial_graph_data(engaged_user);
      div = $('<div></div>');
      div.attr('id', 'page_engaged_users');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_engaged_users', graphs, engaged_user.data[0].title, engaged_user.data[0].description, engaged_user.data[0].values);
    }
    if(page_consumptions.data.length)
    {
      graphs = parse_serial_graph_data(page_consumptions);
      div = $('<div></div>');
      div.attr('id', 'page_consumptions_by_consumption_type_unique');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_consumptions_by_consumption_type_unique', graphs, page_consumptions.data[0].title, page_consumptions.data[0].description, page_consumptions.data[0].values);
    }
    if(positive_feedback.data.length)
    {
      graphs = parse_serial_graph_data(positive_feedback);
      div = $('<div></div>');
      div.attr('id', 'page_positive_feedback_by_type_unique');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_positive_feedback_by_type_unique', graphs, positive_feedback.data[0].title, positive_feedback.data[0].description, positive_feedback.data[0].values);
    }
    if(negative_feedback.data.length)
    {
      graphs = parse_serial_graph_data(negative_feedback);
      div = $('<div></div>');
      div.attr('id', 'page_negative_feedback_by_type_unique');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_negative_feedback_by_type_unique', graphs, negative_feedback.data[0].title, negative_feedback.data[0].description, negative_feedback.data[0].values);
    }
    if(fans_online.data.length)
    {
      graphs = parse_serial_graph_data(fans_online);
      div = $('<div></div>');
      div.attr('id', 'page_fans_online_per_day');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_fans_online_per_day', graphs, fans_online.data[0].title, fans_online.data[0].description, fans_online.data[0].values);
    }
  }
  function render_reactions(response)
  {
    var div;
    var container = $('#reactions .x_content');
    var graphs = [];
    var reactions_total = JSON.parse(response[0].body);
    if(reactions_total.data.length)
    {
      graphs = parse_serial_graph_data(reactions_total);
      div = $('<div></div>');
      div.attr('id', 'page_actions_post_reactions_total');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_actions_post_reactions_total', graphs, reactions_total.data[0].title, reactions_total.data[0].description, reactions_total.data[0].values);
    }
  }
  function render_cta_clicks(response)
  {
    var div;
    var container = $('#cta_clicks .x_content');
    var graphs = [];
    var cta_clicks = JSON.parse(response[0].body);
    //console.log(cta_clicks); return;
    var cta_clicks_type = JSON.parse(response[1].body);
    var cta_clicks_age = JSON.parse(response[2].body);
    if(cta_clicks.data.length)
    {
      graphs = parse_serial_graph_data(cta_clicks);
      div = $('<div></div>');
      div.attr('id', 'page_cta_clicks_logged_in_unique');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_cta_clicks_logged_in_unique', graphs, cta_clicks.data[0].title, cta_clicks.data[0].description, cta_clicks.data[0].values);
    }
    if(cta_clicks_type.data.length)
    {
      graphs = parse_serial_graph_data(cta_clicks_type);
      div = $('<div></div>');
      div.attr('id', 'page_cta_clicks_by_site_logged_in_unique');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_cta_clicks_by_site_logged_in_unique', graphs, cta_clicks_type.data[0].title, cta_clicks_type.data[0].description, cta_clicks_type.data[0].values);
    }
    if(cta_clicks_age.data.length)
    {
      var age_data = parse_age_graph_data(cta_clicks_age);
      graphs = ['male', 'female'];
      div = $('<div></div>');
      div.attr('id', 'page_cta_clicks_by_age_gender_logged_in_unique');
      div.css('height', '500px');
      container.append(div);
      render_age_chart('page_cta_clicks_by_age_gender_logged_in_unique', graphs, cta_clicks_age.data[0].title, cta_clicks_age.data[0].description, age_data);
    }
  }
  function render_fans(response)
  {
    var div;
    var container = $('#fans .x_content');
    var graphs = [];
    var like_sources = JSON.parse(response[0].body);
    var unlike_sources = JSON.parse(response[1].body);
    var likes_by_age = JSON.parse(response[2].body);
    var likes_by_country = JSON.parse(response[3].body);
    var likes_by_city = JSON.parse(response[4].body);
    if(like_sources.data.length)
    {
      graphs = parse_serial_graph_data(like_sources);
      div = $('<div></div>');
      div.attr('id', 'page_fans_by_like_source_unique');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_fans_by_like_source_unique', graphs, like_sources.data[0].title, like_sources.data[0].description, like_sources.data[0].values);
    }
    if(unlike_sources.data.length)
    {
      graphs = parse_serial_graph_data(unlike_sources);
      div = $('<div></div>');
      div.attr('id', 'page_fans_by_unlike_source_unique');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_fans_by_unlike_source_unique', graphs, unlike_sources.data[0].title, unlike_sources.data[0].description, unlike_sources.data[0].values);
    }
    if(likes_by_age.data.length)
    {
      var age_data = parse_age_graph_data(likes_by_age);
      graphs = ['male', 'female'];
      div = $('<div></div>');
      div.attr('id', 'page_fans_gender_age');
      div.css('height', '500px');
      container.append(div);
      render_age_chart('page_fans_gender_age', graphs, likes_by_age.data[0].title, likes_by_age.data[0].description, age_data);
    }
    if(likes_by_country.data.length)
    {
      var chart_data = parse_pie_graph_data(likes_by_country);
      div = $('<div></div>');
      div.attr('id', 'page_fans_country');
      div.css('height', '500px');
      container.append(div);
      render_pie_chart('page_fans_country', likes_by_country.data[0].title, likes_by_country.data[0].description, chart_data);
    }
    if(likes_by_city.data.length)
    {
      var chart_data = parse_pie_graph_data(likes_by_city);
      div = $('<div></div>');
      div.attr('id', 'page_fans_city');
      div.css('height', '500px');
      container.append(div);
      render_pie_chart('page_fans_city', likes_by_city.data[0].title, likes_by_city.data[0].description, chart_data);
    }
  }
  function render_views(response)
  {
    var div;
    var container = $('#page_views .x_content');
    var graphs = [];
    var views = JSON.parse(response[0].body);
    var views_referrals = JSON.parse(response[1].body);
    var views_tabs = JSON.parse(response[2].body);
    var views_devices = JSON.parse(response[3].body);
    if(views.data.length)
    {
      graphs = parse_serial_graph_data(views);
      div = $('<div></div>');
      div.attr('id', 'page_views_total');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_views_total', graphs, views.data[0].title, views.data[0].description, views.data[0].values);
    }
    if(views_referrals.data.length)
    {
      graphs = parse_serial_graph_data(views_referrals);
      div = $('<div></div>');
      div.attr('id', 'page_views_external_referrals');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_views_external_referrals', graphs, views_referrals.data[0].title, views_referrals.data[0].description, views_referrals.data[0].values);
    }
    if(views_tabs.data.length)
    {
      graphs = parse_serial_graph_data(views_tabs);
      div = $('<div></div>');
      div.attr('id', 'page_views_by_profile_tab_total');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_views_by_profile_tab_total', graphs, views_tabs.data[0].title, views_tabs.data[0].description, views_tabs.data[0].values);
    }
    if(views_devices.data.length)
    {
      graphs = parse_serial_graph_data(views_devices);
      div = $('<div></div>');
      div.attr('id', 'page_views_by_site_logged_in_unique');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_views_by_site_logged_in_unique', graphs, views_devices.data[0].title, views_devices.data[0].description, views_devices.data[0].values);
    }
  }
  function render_videos(response)
  {
    var div;
    var container = $('#videos .x_content');
    var graphs = [];
    var views = JSON.parse(response[0].body);
    var views_complete = JSON.parse(response[1].body);
    if(views.data.length)
    {
      graphs = parse_serial_graph_data(views);
      div = $('<div></div>');
      div.attr('id', 'page_video_views');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_video_views', graphs, views.data[0].title, views.data[0].description, views.data[0].values);
    }
    if(views_complete.data.length)
    {
      graphs = parse_serial_graph_data(views_complete);
      div = $('<div></div>');
      div.attr('id', 'page_video_complete_views_30s');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_video_complete_views_30s', graphs, views_complete.data[0].title, views_complete.data[0].description, views_complete.data[0].values);
    }
  }
  function render_posts(response)
  {
    var div;
    var container = $('#posts .x_content');
    var graphs = [];
    var viral_impressions = JSON.parse(response[0].body);
    var viral_unique = JSON.parse(response[1].body);
    var impressions_type = JSON.parse(response[2].body);
    var reach_type = JSON.parse(response[3].body);
    if(viral_impressions.data.length)
    {
      graphs = parse_serial_graph_data(viral_impressions);
      div = $('<div></div>');
      div.attr('id', 'page_posts_impressions_viral');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_posts_impressions_viral', graphs, viral_impressions.data[0].title, viral_impressions.data[0].description, viral_impressions.data[0].values);
    }
    if(viral_unique.data.length)
    {
      graphs = parse_serial_graph_data(viral_unique);
      div = $('<div></div>');
      div.attr('id', 'page_posts_impressions_viral_unique');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_posts_impressions_viral_unique', graphs, viral_unique.data[0].title, viral_unique.data[0].description, viral_unique.data[0].values);
    }
    if(impressions_type.data.length)
    {
      graphs = parse_serial_graph_data(impressions_type);
      div = $('<div></div>');
      div.attr('id', 'page_posts_impressions_by_paid_non_paid');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_posts_impressions_by_paid_non_paid', graphs, impressions_type.data[0].title, impressions_type.data[0].description, impressions_type.data[0].values);
    }
    if(reach_type.data.length)
    {
      graphs = parse_serial_graph_data(reach_type);
      div = $('<div></div>');
      div.attr('id', 'page_posts_impressions_by_paid_non_paid_unique');
      div.css('height', '500px');
      container.append(div);
      render_serial_chart('page_posts_impressions_by_paid_non_paid_unique', graphs, reach_type.data[0].title, reach_type.data[0].description, reach_type.data[0].values);
    }
  }
  function render_serial_chart(div_id, graphs, title, description, data)
  {
    var chart_config = get_chart_config();
    delete chart_config.chartScrollbar;
    delete chart_config.valueScrollbar;
    chart_config.graphs = get_am_graphs(graphs);
    chart_config.titles = get_graph_title(title);
    chart_config.allLabels = get_graph_description(description);
    chart_config.dataProvider = data;
    //console.log(chart_config.dataProvider);
    var serial_chart = AmCharts.makeChart(div_id, chart_config);
    
    return serial_chart;
  }
  function render_age_chart(div_id, graphs, title, description, data)
  {
    var chart_config = {
      "type": "serial",
      "theme": "light",
      "marginRight": 40,
      "marginLeft": 40,
      "autoMarginOffset": 20,
      "responsive": { "enabled": true },
      "valueAxes": [{
          "axisAlpha": 0,
          "stackType": "regular",
          "gridAlpha":0.1
      }],  
      "categoryField": "range",
      "categoryAxis": {
          "gridAlpha": 0.1,
          "axisAlpha": 0,
          "gridPosition": "start"
      },
      "export": {
          "enabled": true
      },
      "legend": {
            "useGraphSettings": true,
            "align" : 'center',
            "periodValueText" : "[[value.sum]]",
            "horizontalGap" : 10
      },
      "graphs" : [
        {
         "title" : "Male", 
         "labelText" : "[[male]]", 
         "valueField" : "male",
         "type" : "column", 
         "lineAlpha" : 0, 
         "fillAlphas" : 1, 
         "lineColor" : "#3C6399",
         "balloonText" : "<span style='color:#555555;'>[[category]]</span><br><span style='font-size:14px'>[[title]]:<b>[[male]]</b></span>" 
        },
        {
         "title" : "Female", 
         "labelText" : "[[female]]",
         "valueField" : "female", 
         "type" : "column", 
         "lineAlpha" : 0, 
         "fillAlphas" : 1, 
         "lineColor" : "#899BC1",
         "balloonText" : "<span style='color:#555555;'>[[category]]</span><br><span style='font-size:14px'>[[title]]:<b>[[female]]</b></span>"
        }  
      ]
    };
    chart_config.titles = get_graph_title(title);
    chart_config.allLabels = get_graph_description(description);

    chart_config.dataProvider = data;
    chart_config.plotAreaBorderAlpha = 0.2;

    
    age_chart = AmCharts.makeChart(div_id, chart_config);
    return age_chart;
  }

  function render_pie_chart(div_id, title, description, data)
  {
    var pie_chart = new AmCharts.AmPieChart();
    // title of the chart
    pie_chart.addTitle(title, 16);
    pie_chart.addLabel(0, 35, description, 'center', 12);
    pie_chart.dataProvider = data;
    pie_chart.titleField = "location";
    pie_chart.valueField = "value";
    pie_chart.innerRadius = "30%";
    pie_chart.sequencedAnimation = false;
    pie_chart.startDuration = 0;
    pie_chart.labelRadius = 15;
    pie_chart.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>";
    pie_chart.depth3D = 10;
    pie_chart.angle = 15;
    pie_chart.responsive = { "enabled": true };
    pie_chart.export = { "enabled": true };
    pie_chart.write(div_id);
    return pie_chart;
  }
  
  function parse_serial_graph_data(response)
  {
    var keys = [];
    var graph_title = response.data[0].title;
    if(typeof response.data[0].values[0].value === 'object') // multi graphs
    {
      for(var i = 0; i< response.data[0].values.length; i++)
      {
        if(!jQuery.isEmptyObject(response.data[0].values[i].value))
          for (var key in response.data[0].values[i].value) {
            if (response.data[0].values[i].value.hasOwnProperty(key)) {
              response.data[0].values[i][key] = response.data[0].values[i].value[key];
              if(keys.indexOf(key) === -1)
                keys.push(key);
            }
          }
        else{
          response.data[0].values[i][graph_title] = 0;
        }
        response.data[0].values[i]['date'] = response.data[0].values[i].end_time;
        delete response.data[0].values[i].value;
        delete response.data[0].values[i].end_time;
      }
      if(keys.length == 0) keys.push(graph_title);
    }else // single graph
    {
      for(var i = 0; i< response.data[0].values.length; i++)
      {
        response.data[0].values[i]['date'] = response.data[0].values[i].end_time;
        response.data[0].values[i][graph_title] = response.data[0].values[i].value;
        delete response.data[0].values[i].end_time;
        delete response.data[0].values[i].value;
      }
      keys.push(graph_title);
    }
    return keys;
  }
  function parse_age_graph_data(response)
  {
    var data = [];
    var data_set = response.data[0].values[response.data[0].values.length - 1].value;
    data.push({
      'range' : '13-17',
      'male'  : data_set.hasOwnProperty('M.13-17') ? data_set['M.13-17'] : 0 , 
      'female'  : data_set.hasOwnProperty('F.13-17') ? data_set['F.13-17'] : 0 
    });
    data.push({
      'range' : '18-24',
      'male'  : data_set.hasOwnProperty('M.18-24') ? data_set['M.18-24'] : 0 , 
      'female'  : data_set.hasOwnProperty('F.18-24') ? data_set['F.18-24'] : 0 
    });
    data.push({
      'range' : '25-34',
      'male'  : data_set.hasOwnProperty('M.25-34') ? data_set['M.25-34'] : 0 , 
      'female'  : data_set.hasOwnProperty('F.25-34') ? data_set['F.25-34'] : 0 
    });
    data.push({
      'range' : '35-44',
      'male'  : data_set.hasOwnProperty('M.35-44') ? data_set['M.35-44'] : 0 , 
      'female'  : data_set.hasOwnProperty('F.35-44') ? data_set['F.35-44'] : 0 
    });
    data.push({
      'range' : '45-54',
      'male'  : data_set.hasOwnProperty('M.45-54') ? data_set['M.45-54'] : 0 , 
      'female'  : data_set.hasOwnProperty('F.45-54') ? data_set['F.45-54'] : 0 
    });
    data.push({
      'range' : '55-64',
      'male'  : data_set.hasOwnProperty('M.55-64') ? data_set['M.55-64'] : 0 , 
      'female'  : data_set.hasOwnProperty('F.55-64') ? data_set['F.55-64'] : 0 
    });
    data.push({
      'range' : '65+',
      'male'  : data_set.hasOwnProperty('M.65+') ? data_set['M.65+'] : 0 , 
      'female'  : data_set.hasOwnProperty('F.65+') ? data_set['F.65+'] : 0 
    });
    return data;
  }

  function parse_pie_graph_data(response)
  {
    var data = [];
    var data_set = response.data[0].values[response.data[0].values.length - 1].value;
    data_set = get_top_x_objs(data_set, 10);
    for(var key in data_set){
      if(data_set.hasOwnProperty(key))
        data.push({
          'location' : parse_code(key),
          'value' : data_set[key]
        });
    }
    return data;
  }

  function get_top_x_objs(object, len)
  {
    var tuples = [];
    for (var key in object) tuples.push([key, object[key]]);
    tuples.sort(function(a, b) {
        a = a[1];
        b = b[1];
        return a > b ? -1 : (a < b ? 1 : 0);
    });
    var length = tuples.length > len ? len : tuples.length;
    sorted_set = {};
    for (var i = 0; i < length; i++)
      sorted_set[tuples[i][0]] = tuples[i][1];
    return sorted_set;
  }

jQuery(function(){
  $('#since').datepicker({dateFormat : 'yy-mm-dd'});
  $('#until').datepicker({dateFormat : 'yy-mm-dd'});
});
</script>



