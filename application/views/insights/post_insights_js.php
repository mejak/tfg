<div id="fb-root"></div>
<script type="text/javascript">
  var base_url = '<?php echo base_url() ?>index.php/';
  var graph_data = [];
  //angular code    
  var angapp = angular.module('angapp', []);
  angapp.controller('pageController', function($scope, $http, $sce){
    $scope.page_id = '<?php echo $post->page_id ?>';
    $scope.fb_app_id = '<?php echo $post_app_id ?>';
    $scope.post_fb_id = '<?php echo strpos($post->post_fb_id, '_') === FALSE ? $post->page_fb_id.'_'.$post->post_fb_id : $post->post_fb_id ?>';
    $scope.token_loaded = false;
    $scope.sdk_loaded = false;
    $scope.insights = [];
    $scope.metrics_loaded = false;
    $scope.token = '';
    $scope.alert = "";
    //Get Page token
    $http.get(base_url + 'api/page/token/' + $scope.page_id ).then(function(result) {
      $scope.token = result.data.token;
      if($scope.sdk_loaded)
        $scope.load_insights();
      else
        $scope.token_loaded = true;
    });
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
        $scope.load_insights();
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

    $scope.load_insights = function()
    {
      $scope.token_loaded = true;
      $scope.sdk_loaded = true;
      $scope.$apply();
      //return;
      var params = {
        access_token : $scope.token
      }
      FB.api('/'+$scope.post_fb_id+'/insights', 'GET', params, function(response){
        if(response.error)
          $scope.show_alert("<div class='alert alert-danger'>"+response.error.message+"</div>");
        $scope.insights = response.data;
        $scope.insights_loaded = true;
        $scope.$apply();
      }); 
    }

    $scope.print_values = function(values)
    {
      var text = '';
      if(values.length == 0)
        return '';
      if(values.length == 1)
      {
        value = values[0].value;
        if(typeof value === 'object')
        {
          if(jQuery.isEmptyObject(value))
            return "Value: 0";
          for(var key in value)
          {
            if(value.hasOwnProperty(key)){
              if(typeof value[key] === 'object')
                text += key + ": " + objToString(value[key]) + "  ";
              else
                text += key + ": " + value[key] + "  ";
            }
          }
          return text;
        }
        else{
          var v = value == 0 ? '0' : value; 
          return "Value: "+ v;
        } 
          
      }else{
        //console.log(values);
        for(var i = 0; i < values.length; i++)
          text += values[i].end_time.substring(0, 10) + ': '+ values[i].value + "  ";
        return text;
      }
    }
  });

  function objToString (obj) {
      var str = '';
      for (var p in obj) {
          if (obj.hasOwnProperty(p)) {
              str += p + '::' + obj[p] + ' ';
          }
      }
      return str;
  }
</script>



