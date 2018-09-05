   <script type="text/javascript">
      //All js functions for slider,get tweets,verify user
            var mainSlider;

    
            $(document).ready(function(){
                                 var mainSlider;

                                  $('#txtflwdwn').blur(function(event) {
                                     $.ajax({
                                        url:"controller.php?flwdwn="+$(this).val(),
                                        type:"post",
                                        dataType: "json",
                                        success:function(response){

                                         if(response.success.length == 0){ 
                                          console.log('Empty JSON Data');
                                          //alert(response)

                                         }else{
                                           // alert("else case")
                                          if(response.success == true)
                                            {
                                                //$("#flwdwnbtn").removeClass("disabled");
                                            }
                                            else
                                            {
                                               // $("#flwdwnbtn").addClass("disabled");
                                                alert("Not a valid screen name,Unable to find this twitter profile");
                                                $('#txtflwdwn').focus();
                                            }
                                            //alert(response);
                                         }
                                        },
                                        failure:function(response){
                                            console.log(response);
                                        }
                                    });   

                                  });
                                });

          

            $(document).ready(function(){         
                mainSlider = $('.slider4').bxSlider({
                    slideWidth: 300,
                    minSlides: 2,
                    maxSlides: 3,
                    moveSlides: 1,
                    slideMargin: 10,
                    auto: true
                  });
            });

            

            function tmp(){


                var follower_name, text;
                    follower_name = document.getElementById("txtflwdwn").value;

                    if ( follower_name < 1 ) {
                       alert("Please Enter user handler");
                    }


                var e = document.getElementById("dropbox");
                var optionSelIndex = e.options[e.selectedIndex].value;
                var optionSelectedText = e.options[e.selectedIndex].text;
                if (optionSelIndex == 0) {
                    alert("Please select any one format");
                }
                else
                {
                       var e = document.getElementById("dropbox");
                                var format = e.options[e.selectedIndex].value;

                            var person = prompt("All the Follower Data will be mailed on the given Email Address","Enter Email Address");
                                    validate=false;
                                    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(person))
                                      {
                                        validate=true;

                                      }
                                      else
                                      {
                                        alert("You have entered an invalid email address, Please try again")
                                         validate=false;

                                      }
                                        
                                    if (validate) {
                                        $.ajax({
                                            url:"controller.php?format="+format+"&email="+person,
                                            type:"post",
                                            success:function(response){
                                                alert("Request Accepted Successfully , You will receive email with in a day")
                                                document.getElementById("down_follow_data").reset();
                                                console.log();
                                            },
                                            failure:function(response){
                                                alert("Something went wrong, please try again later")
                                                console.log(response);
                                            }
                                        });         
                                    }
                }
                
            }
            
                
            function getUserTweet(screen_name){
                if(screen_name != ''){
                    $.ajax({
                        url: "ajax-php/ajax-get-user-tweets.php",
                        type: "POST",
                        data: "screen_name="+screen_name,
                        dataType: "json",
                        async:false,
                        success: function(resp){                
                             if(resp.ErrorCode == 0){
                                $('#slider_tweet_content').html('Loading...');
                                $('#slider_tweet_content').html(resp.Content);
                                mainSlider.destroySlider();
                                mainSlider = $('.slider4').bxSlider({
                                    slideWidth: 300,
                                    minSlides: 2,
                                    maxSlides: 3,
                                    moveSlides: 1,
                                    slideMargin: 10,
                                    auto: true
                                });                         
                             }
                        }
                    });
                }
                return false;
            }

        </script>