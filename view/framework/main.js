//This is the main Javascript file to access the global functions of the Framework

//SET the IPINFO REQUEST
// executeRequestAsync('http://ipinfo.io/201.191.95.57?token=bce7e4c61e3e38', function(data){
//   console.log(data)
// });






let xhr_processing_pending_request = false;




//Find Class or Repeatable Element
function findall(elem){
  return document.querySelectorAll(elem);
};

//Find Single ID
function findone(elem){
  return document.querySelector(elem);
}


function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}


//return the value of the get variable selected
function retrieveGET(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}




//function to add an Event Listener to an element
function trigger(elem, listen, action){
  const array = findall(elem);
  for (var i = 0; i < array.length; i++) {
    array[i].removeEventListener(listen, action);
    array[i].addEventListener(listen, action, false);
  }
};



// active: "3"
// birth_date: "2014-07-01"
// breeder_data: "{\"name\":\"Gary Douglas\",\"identification\":\"1111111111111111\"}"
// encaste: "Pure"
// first_owner_data: ""
// horse_name: "GD Destinado"
// id_current_owner: "2"
// id_encaste: "1"
// id_horse: "60"
// id_horse_sex: "1"
// inscription_date: "2015-10-15"
// inspector_reference: "NONE"
// microchip_no: "981098104903886"
// name: "Sergio"
// observations: "Some information needs to be checked before live deployment."
// other_information: "{\"purity\":\"1\",\"sex\":\"1\",\"size\":\"12\",\"typesize\":\"hand\"}"
// sex: "Male"



//Function to load the data into a Select
function LoadSelect(elem, array, label, value, load = true, sep = ' - '){
  let html = ``;

  let labels = (Array.isArray(label)) ? label : [label];

  for (var i = 0; i < array.length; i++) {
    let sel_text = '';
    for (var j = 0; j < labels.length; j++) {
      let innsep = ( j > 0 ) ? sep : '';
      sel_text += innsep+array[i][labels[j]]
    }

    let isSelected = ( array[i][value] == elem ) ? 'selected' : '';
    html += `<option ${isSelected} value="${array[i][value]}">${sel_text}</option>`;
  }

  if (load) findone(elem).innerHTML = html;
  return html;
}

//returns current date in format dd/mm/yyyy
function getDate(){
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    return yyyy + '-' + mm + '-' + dd;
};



//returns a past date with an offset.
function getPastDate(days = 0){
    var date = new Date();
    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
    var day =last.getDate();
    var month=last.getMonth()+1;
    var year=last.getFullYear();
    return year+'-'+month+'-'+day;
};




//function to format the number with commas
function formatNum(n) {
    return parseFloat(n).format(2,3,'.', ',');
};




//returns number rounded to 2 decimals
function rnd(n, decimals = 2){
    var r = null;
    if (typeof n === 'string') {
        r = +parseFloat(n).toFixed(decimals);
    }else{
        r = +n.toFixed(decimals);
    }
    return r;
};




function tokenExpired(){
  let current = String(new Date().getTime()).substring(0, 10);
  let expire = localStorage.getItem("JWT_expire");
  return ( current > expire );
}



function generateToken( url, callback, config, type = 'request' ){

  let xhr = new  XMLHttpRequest();
  xhr.open('post', '<?=PATH_API?>token/generate.php');
  const data = {
    general:'a41737c7b85c8e99ba013e1bce739502',
    specific:'98200bb918c81136fe57c60bc488a94f'
  };
  xhr.send(JSON.stringify(data));
  xhr.onreadystatechange = function () {
      //&& xhr.status === 200
      if(xhr.readyState === XMLHttpRequest.DONE) {

          let response = JSON.parse(xhr.responseText);
          //console.log(xhr);

          if (response.code == 0) {
            alert('There has been an error connecting to the application, please come back later.');
          }else{
            const token = response.document.access_token;
            const expire = response.document.expires_in;
            localStorage.setItem("JWT_token", token);
            localStorage.setItem("JWT_expire", expire);
            if (type == 'request') {
              executeRequest(url, callback, config);
            }else{
              executeRequestAsync(url, callback, config);
            }
          }
      }
  };

}


function request(url, callback, config){
  if ( !localStorage.getItem("JWT_token") || tokenExpired() ) {
    generateToken(url, callback, config);
    return;
  }
  executeRequest(url, callback, config);
}




//function to execute Requests to the backend API
function executeRequest(url, callback, config){

  let token = localStorage.getItem('JWT_token');

  const c = {
    json_data:true,
    log_response:false,
    f_data: new FormData(),
    headers:[ { name:'Authorization', value: 'Bearer ' + token } ],
    type:'post',
    data:{}
  };

  Object.assign(c, config);

  //add the definition of the type of app
  if (c.json_data) {
    c.headers.push({name:'Content-Type', value:'application/json'});
  }

  //Validate if there is an open request already
  if (!xhr_processing_pending_request) {

    Fnon.Wait.Init({clickToClose:false, background:'rgba(255,255,255, 0.9)'});
    Fnon.Wait.LineDots();
    Fnon.Wait.Change('Loading...');
    xhr_processing_pending_request = true;

    let xhr = new  XMLHttpRequest();


    xhr.open(c.type, url);
    for (var i = 0; i < c.headers.length; i++) {
      let header = c.headers[i];
      xhr.setRequestHeader(header.name, header.value);
    }

    if (c.json_data) {
      xhr.send(JSON.stringify(c.data));
    }else{
      xhr.send(c.f_data);
    }

    xhr.onreadystatechange = function () {

        //&& xhr.status === 200
        if(xhr.readyState === XMLHttpRequest.DONE) {
            if (c.log_response){console.log(xhr);}
            callback(JSON.parse(xhr.responseText));
        }
        Fnon.Wait.Remove(500);
        xhr_processing_pending_request = false;
    };

  }else{
    alert('We are currently processing your previous request, please wait.');
  }


};






function requestAsync(url, callback, config){
  if ( !localStorage.getItem("JWT_token") || tokenExpired() ) {
    generateToken(url, callback, config, 'requestAsync');
    return;
  }
  executeRequestAsync(url, callback, config, 'requestAsync');
}



function executeRequestAsync(url, callback, config){

  let token = localStorage.getItem('JWT_token');

  const c = {
    json_data:true,
    log_response:false,
    f_data: new FormData(),
    headers:[ { name:'Authorization', value: 'Bearer ' + token } ],
    type:'post',
    data:{}
  };

  Object.assign(c, config);

  //add the definition of the type of app
  if (c.json_data) {
    c.headers.push({name:'Content-Type', value:'application/json'});
  }

  let xhr = new  XMLHttpRequest();


  xhr.open(c.type, url);
  for (var i = 0; i < c.headers.length; i++) {
    let header = c.headers[i];
    xhr.setRequestHeader(header.name, header.value);
  }

  if (c.json_data) {
    xhr.send(JSON.stringify(c.data));
  }else{
    xhr.send(c.f_data);
  }

  xhr.onreadystatechange = function () {
      //&& xhr.status === 200
      if(xhr.readyState === XMLHttpRequest.DONE) {
          if (c.log_response){console.log(xhr);}
          callback(JSON.parse(xhr.responseText));
      }
      Loader.hide();
  };
};



// Uso:  fireEvent('#IdElemento', 'tipoEvento');
// Esta función simula el evento enviado por parametro, ej: click, scroll, change, etc...
function fireEvent(el, etype){
  el = findone(el);
  if (el.fireEvent) {
    el.fireEvent('on' + etype);
  } else {
    var evObj = document.createEvent('Events');
    evObj.initEvent(etype, true, false);
    el.dispatchEvent(evObj);
  }
}




const Loader = {
  show:function(){
    Fnon.Wait.Init({clickToClose:false, background:this.bg});
    Fnon.Wait[this.style]();
    Fnon.Wait.Change(this.text);
  },
  hide:function(time = 500){
    Fnon.Wait.Remove(time);
  },
  style:'Interwind',
  text:'Loading...',
  bg:'rgba(255,255,255, 0.9)'
};






//Global Definitons
Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));
    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};



Date.isLeapYear = function (year) {
    return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0));
};

Date.getDaysInMonth = function (year, month) {
    return [31, (Date.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
};

Date.prototype.isLeapYear = function () {
    return Date.isLeapYear(this.getFullYear());
};

Date.prototype.getDaysInMonth = function () {
    return Date.getDaysInMonth(this.getFullYear(), this.getMonth());
};

Date.prototype.addMonths = function (value) {
    var n = this.getDate();
    this.setDate(1);
    this.setMonth(this.getMonth() + value);
    this.setDate(Math.min(n, this.getDaysInMonth()));
    return this;
};
