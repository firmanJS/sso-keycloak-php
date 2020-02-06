<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <title>Service PHP APP</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <h2 style="margin-left:20px;">Example PHP</h2>
            </div>
            <p>
                <button type="button" id="read" class="btn btn-info">Read</button>
                <button type="button" id="edit" class="btn btn-primary">Edit</button>
                <button type="button" id="create" class="btn btn-success">Create</button>
                <button type="button" id="delete" class="btn btn-danger">Delete</button>
            </p>
            <div class="list-group">
                <div class="list-group-item">
                      <div class="media">
                          <div class="media-body" style="border-bottom: 0.5px solid #9999;">
                              <p><code id="test"></code></p>
                          </div>
                      </div>
                    <br>
                    <button onclick="logout()" class="btn btn-danger">Logout</button>
                </div>
            </div>
        </div>
        <script src="http://localhost:2080/auth/js/keycloak.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
        <script type="text/javascript">
            const keycloak = Keycloak('http://localhost:3000/keycloak.json')
            const initOptions = {
                responseMode: 'fragment',
                flow: 'standard',
                onLoad: 'login-required'
            };
            function logout(){
                Cookies.remove('token');
                Cookies.remove('callback');
                keycloak.logout();
            }
            keycloak.init(initOptions).success(function(authenticated) {
                Cookies.set('token', keycloak.token);
                Cookies.set('callback',JSON.stringify(keycloak.tokenParsed.resource_access.php_service.permission));
                var arr = JSON.parse(Cookies.get('callback'));
                arr = arr.reduce((index,value) => (index[value] = true, index), {});
                (arr.access_create === true ? document.getElementById("create").disabled = false : document.getElementById("create").disabled = true);
                (arr.access_edit === true ? document.getElementById("edit").disabled = false : document.getElementById("edit").disabled = true);
                (arr.access_delete === true ? document.getElementById("delete").disabled = false : document.getElementById("delete").disabled = true);
                (arr.access_view === true ? document.getElementById("read").disabled = false : document.getElementById("read").disabled = true);
                document.getElementById("test").innerHTML = Cookies.get('token');
                // console.log('Init Success (' + (authenticated ? 'Authenticated token : '+JSON.stringify(keycloak) : 'Not Authenticated') + ')');
            }).error(function() {
                console.log('Init Error');
            });
        </script>
    </body>
</html>
