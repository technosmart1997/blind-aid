var app=angular.module('myApp',['ngRoute','ngCookies']);

app.config(function($routeProvider){
    $routeProvider
    .when('/',{
        resolve : {
          check : function(user_info,$location){
            if(user_info.getloginstatus())
                {
                    $location.path('/dashboard');
                }else{
                    $location.path('/');
                }
          },  
        },
        templateUrl : 'login.html',
        controller : 'LoginCtrl'
    })
    .when('/dashboard',{
        resolve : {
          check : function($location,user_info){
              if(!user_info.getloginstatus())
                  {
                      $location.path('/');
                  }else{
                      $location.path('/dashboard');
                  }
          },  
        }, 
        templateUrl : 'dashboard.html',
        controller : 'DashCtrl'
    })
    .otherwise({
        redirectTo : '/'
    })
});


app.factory('user_info',function(){
    var email;
    var loggedin=false;
    var userid;
    var data;
    var fname;
    var user_id;
    return {
         getloginstatus : function(){
             if(!localStorage.getItem('login'))
                 {
                     loggedin = false;
                     return loggedin;
                 }
             else{
                 var data= JSON.parse(localStorage.getItem('login'));
                 email = data.email;
                 userid = data.id;
                 fname = data.fname;
                 loggedin = true;
                 return loggedin;
             }
        },
        setuserinfo : function(data){
            loggedin = true,
            user_id = data.userid;
            fname = data.fname;
            email = data.email;
            userid = data.session_id;
            localStorage.setItem('login',JSON.stringify({
                email : email,
                id : userid,
                fname : fname, 
                loginstatus : loggedin
            }));
        },
        getusername : function(){
            return email;
        },
        getname : function(){
          return fname;  
        },
        cleardata : function(){
            localStorage.removeItem('login');
            loggedin = false;
            
            email= "";
            id= "";
            fname = "";
        },
        getuserid : function(){
            return user_id;
        }
        
}
});

app.controller('LoginCtrl',function($scope,$http,$location,user_info){

    $scope.alert_box = false;
    
    $scope.login=function(){
             $http.post('loginvalidation.php',{ 'emailid' : $scope.emailid , 'password' : $scope.password}).then(function(response){
               if(response.data.status == 'loggedin')
               {
                   user_info.setuserinfo(response.data);
                   $location.path('/dashboard');
               }
               })
     }
                                                                                                            

    
    
    
     
  
    
     $scope.register=function(){
         
         var firstname=$scope.fname;
         var lastname=$scope.lname;
         var reg_pass=$scope.reg_pass;
         var reg_email=$scope.reg_email;
         var gender=$scope.gender;
         var contact=$scope.contact_no;
         var hcity =$scope.hcity;
         var hcountry =$scope.hcountry;
         var pcity =$scope.pcity;
         var pcountry =$scope.pcountry;
        
        $http.post('register.php',{'fname' : firstname,'lname' : lastname,'gen' : gender, 'rpass': reg_pass,'remail' : reg_email,'homecity' : hcity,'presentcity' : pcity, 'con' : contact  ,'homecountry':hcountry,'presentcountry':pcountry}).then(function(response){
                    $scope.alert_box = true;
                  if(response.data.status == 'Sorry, Email Id Already Exists'){
                      $scope.register_error = 'Sorry, Email Id Already Exists'; 
                  }else{
                      $scope.register_error = 'Successfully Registered';
                  }
            });
      }
s
     
     $scope.checkusername = function(){
         $http.post('usernamevalidation.php',{'username' : $scope.username}).then(function(response){
            var message= response.data.message;
             
             if(message == "Username Exists")
                 {
                     $scope.usernameerror=message;
                     $scope.errstyle= {
                         'color': 'red',
                         'font-size' : '15px',
                         'padding' : '10px'
                     }
                 }else{
                     $scope.usernameerror=message;
                     $scope.errstyle= {
                         'color': 'green',
                         'font-size' : '15px',
                         'padding' : '10px'
                     }
                 }
            });
     }
   
});

app.controller('DashCtrl',function($scope,user_info,$location,$http){
    var emailid=user_info.getusername();
    var name = user_info.getname();

    $scope.friends_div = false;
    $scope.jobs_div = false;
    $scope.apart_div = false;
    
    $scope.email = emailid;
    $scope.name= name;
    
    $http.post('info.php',{'email' : emailid}).then(function(response){
        
    })
    $scope.logout = function(){
        user_info.cleardata();
        $location.path('/');
    }
    
   $scope.face_change = function(id){
       $scope.face= id;
   }
    $scope.find_hobby = function(h1,h2,h3){
     
        $http.post('get_hobby_data.php',{'email' : emailid, 'hb1' : h1, 'hb2' : h2,'hb3' : h3}).then(function(response){
           $scope.friends_div = true;
                    if(response.data.res.res='Done')
                        {
                            $scope.friends_data = response.data.output;
                        }
        })
    }
    
    
    $scope.job = function(cname,dept){
        $http.post('jobdata.php',{'email': emailid,'company' : cname , 'department' : dept}).then(function(response){
            $scope.jobs_div = true;
            if(response.data.res.error == 'Done'){
                $scope.jobinfo = response.data.info;
            }else{
                $scope.jobinfo = 'Sorry No Match Found';
            }   
            })
    }
  
    $scope.apartment = function(apartmentno,block)
    {
        var apt = apartmentno;
        var block = block;
        
        $http.post('getapartmentdetails.php',{'apartment' : apt ,'blk' : block}).then(function(response){
            $scope.apart_div = true;
            if(response.data.res.res == 'Done'){
                $scope.apart_members = response.data.output;
            }else{
                $scope.apart_members = 'Sorry No Match Found';
            }   
        })
    }
});


