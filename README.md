[![Maintainability](https://api.codeclimate.com/v1/badges/3b4d9bda0531bdccf536/maintainability)](https://codeclimate.com/github/firmanJS/sso-keycloak-php/maintainability)

# Docker Keycloak Example With PHP

how to set keycloak by using the docker and an example of application implmentation with php languages

## Test Running Application

copy environment
```sh
cp .env-sample .env
```

test with logging docker
```sh
docker-compose up --build
```

test with logging docker in background
```sh
docker-compose up --build -d
```

## Step 1: Keycloak Setup

The first step is to use the keycloak admin console to manage client registration and set role permissions.

Open with your favorite browser like Chrome or Mozilla

* http://localhost:2080/

login with username and password :
```txt
username: admin
password: password
```

### Create New Realm in Keycloak

Follow steps below:

1. Click `Add realm` button on the top left of the admin dashboard. Create a new realm with this data:
   * Name = `demo-realm`
1. Click `Create`
1. Click `Login` tab, then configure this value:
   * User registration = `ON`
1. Click `Save`

### Create New Client in Keycloak

Follow steps below:

1. Click on `Clients` in the left menu
1. Click on "Create", then configure these values:
   * Client ID = `demo_client`
1. Click `Save`
1. Edit this field:
   * Access Type = `public`
   * Valid Redirect URIs = `http://localhost:3000/*`

### Create Permission to Client

Follow steps below:

1. Click on `Clients` in the left menu
1. Click `Edit` button next to `demo_client`
1. Click `Roles` tab and click button `Add Role` example Role Name = access_view
1. Click `Mappers` on tab and click button `Add Builtin` checklist `client roles` and click save
1. Click `edit` `client roles` in `Token Claim Name` change roles to `permission` and click save

Now you have successfully finished the keycloak configuration for the new client application.

### Roles and Permission

Follow steps below:

1. Click on `Roles` in the left menu
1. Click `Add Role` Example :
  * Name = `Administrator`
  * Set Composite Roles = `ON`  
  * in Composite Roles Select Client Roles `demo_client`
  * in Alvailable Roles select permission `access_view` and click `Add selected` 
  * click tab `Default Roles` in top `Roles` page
  * in `Realm Roles` select `Available Roles` `Administrator` example for default roles user register app

### Get Keycloak JSON setup for app

Follow steps below:

1. Click on `Clients` in the left menu
2. Click `demo_client` 
3. Click on `Installation` in top menu
4. in `Format Option` select a format `Keycloak OIDC JSON` and click Download
5. move `keycloak.json` in the root folder app

### Setup keycloak.json in app PHP

move keycloak.json to root app directory PHP create file index.php and add code like this

```javascript
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
```

### Testing User Self-Registration

Please start in Firefox or chrome a "New Private Window" and connect to the following URL

http://localhost:2080/auth/realms/demo-realm/account


Follow steps below:

1. Click `register` in the bottom login page

<!-- ### User Role Mappings

Follow steps below:

1. Click on "User" in the left menu
1. Click tab `Role Mappings`
1. in `Realm Roles` Select Available Roles `Administrator` and click `Add selected` -->


### Accees to Keycloak Dabatabse

Use this credentials to access

* Host: `keycloak_db_sso`
* Port: 5432
* Database: `keycloak_sso`
* User: `keycloak_sso`
* Password: `password`
