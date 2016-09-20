window.fbAsyncInit = function() {
	FB.init({
	appId      : '1441395522552665',
	cookie     : true,  // enable cookies to allow the server to access 
	                    // the session
	xfbml      : true,  // parse social plugins on this page
	version    : 'v2.5' // use any version
  });	
};

// Load the SDK asynchronously
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/pt_BR/all.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


function statusChangeCallback(response) 
{
  console.log('statusChangeCallback');
    console.log(response);

    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      conectarMusicalizze();
    } else if (response.status === 'not_authorized') {
              // The person is logged into Facebook, but not your app.
              FB.login(function(response){
                  if (response.status === 'connected') {
                    // Logged into your app and Facebook.
                    conectarMusicalizze();
                  }
              });
          } else {
          // The person is not logged into Facebook, so we're not sure if
          // they are logged into this app or not.
          FB.login(function(response){

              if (response.status === 'connected') {
                // Logged into your app and Facebook.
                conectarMusicalizze();
              }
          });
        }
  }
  if(!response.status){
    alert('...SEM CONEXÃO...');
  }

function checkLoginState() {
  if(FB.getLoginStatus);
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
}

//--------------------------PERSONALIZADO
function facebookLogin() 
  {
     checkLoginState();
  }


