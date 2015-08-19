function O(obj)
{
    if (typeof obj == 'object') return obj;
    else return document.getElementById(obj);
}

function S(obj)
{
    return O(obj).style;
}

function C(name)
{
    var elements = document.getElementsByTagName('*');
    var objects = [];
    for (var i = 0 ; i < elements.length ; ++i) {
        if (elements[i].className == name) {
            objects.push(elements[i]);
        }        
    }        
    return objects;
}

/*
 Using Ajax to check the availability of a username.
*/
function checkUser(user) {
    if (user.value == '') {
        O('info').innerHTML = '&nbsp;';
        return;
    }
    params = 'user=' + user.value;
    request = ajaxRequest();
    request.open('POST', '../includes/checkuser.php', true);
    request.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    //request.setRequestHeader('Content-length', params.length);
    //request.setRequestHeader('Connection', 'close');
    
    request.onreadystatechange = function() {
        if (this.readyState = 4) {
            if (this.state = 200) {
                if (this.responseText != null) {
                    O('info').innerHTML = this.responseText;
                }
            }
        }
    }
    request.send(params);
}

/*
 Using Ajax to check the existence of a course.
*/
function checkCourse(course) {
    if (course.value == '') {
        O('info').innerHTML = '';
        return;
    }
    params = 'course=' + course.value;
    request = ajaxRequest();
    request.open('POST', '../includes/checkcourse.php', true);
    request.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    //request.setRequestHeader('Content-length', params.length);
    //request.setRequestHeader('Connection', 'close');
    
    request.onreadystatechange = function() {
        if (this.readyState = 4) {
            if (this.state = 200) {
                if (this.responseText != null) {
                    O('info').innerHTML = this.responseText;
                }
            }
        }
    }
    request.send(params);
}
/*
  using Ajax to add or remove friends.
*/
function addFriend(user, add) {
    if (add.value == '') {
        return;
    }
    params = 'user=' + user + "&add=" + add;
    console.log(params);
    request = ajaxRequest();
    request.open('POST', '../includes/addremovefriends.php', true);
    request.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
        if (this.readyState = 4) {
            if (this.state = 200) {
                if (this.responseText != null) {
                    var oFriend = O('friend');
                    oFriend.innerHTML = this.responseText;
                }
            }
        }
    }
    request.send(params);
}
function removeFriend(user, remove) {
    if (remove == '') {
        return;
    }
    
    params = 'user=' + user + "&remove=" + remove;
    console.log(params);
    request = ajaxRequest();
    request.open('POST', '../includes/addremovefriends.php', true);
    request.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
        if (this.readyState = 4) {
            if (this.state = 200) {
                if (this.responseText != null) {
                    var oFriend = O('friend');
                    oFriend.innerHTML = this.responseText;                    
                }
            }
        }
    }
    request.send(params);
}


function ajaxRequest() {
    try { var request = new XMLHttpRequest(); } // Non IE Browser
    catch(e1) {
        try { request = new ActiveXObject("Msxml2.XMLHTTP"); } //IE 6+
        catch(e2) {
            try { request = new ActiveXObject("Microsoft.XMLHTTP"); } //IE 5
            catch(e3) {
                request = false;
            }
        }
    }
    return request;
}

function conformPass(oPassc) {
    var pass1 = O('pass1').value;
    var pass2 = oPassc.value;
    if (pass1 != pass2) {
        O('pass_info').innerHTML = "<span class='taken'>&nbsp;&#x2718; The passwords don't match.</span>"             
    }
    else O('pass_info').innerHTML = "&nbsp;";
}

// event listener for menu items
var menu = C("menu")[0];
//if (menu) clickMenu();
function clickMenu() {
    var menuLis = menu.getElementsByTagName("li");
    
    for (var i = 0; i < menuLis.length; i++) {
        menuLis[i].onclick = function() {
            
            for (var i = 0; i < menuLis.length; i++) {
                menuLis[i].className = "";
            }
            
            this.className = "current";
            alert(this.className);
        }
    }    
}


   