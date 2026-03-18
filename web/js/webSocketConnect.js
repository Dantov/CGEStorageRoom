"use strict";

let tabName; // идентификатор текущей вкладки
if ( !userName ) userName = "TestUser";

let ws;
let lenSS = sessionStorage.length;


if ( sessionStorage.length > 0 )
{
    // имя вкладки уже есть, просто обновили страницу
    tabName = sessionStorage.getItem('tabName');
} else {
    //запишем вкладку в сессионное и локальное хранилище
    let timeMs = new Date().getTime();
    tabName = 'Tab_' + timeMs;

    sessionStorage.setItem('tabName', tabName);
}

console.log("sessionStorage lenght = " + lenSS);
console.log("tabName = " + tabName);
console.log("UserName = " + userName);

function wsEventHandlers()
{
    ws.onopen = function(e) {
        console.log("[open] Соединение установлено");
		console.log('wsReadyState = ' + this.readyState);
    };
    ws.onmessage = function(evt) {
        console.log(evt.data);
    };
    ws.onclose = function(event) {
        if ( event.wasClean )
        {
            console.log('[close] Соединение закрыто чисто, код=${event.code} причина=${event.reason}');
        } else {
            // например, сервер убил процесс или сеть недоступна
            // обычно в этом случае event.code 1006
            console.log('[close] Соединение прервано');
        }
    };
}

function wsConnect()
{
    ws = new WebSocket("ws://127.0.0.1:8000/?user=" + userName + "&tab=" + tabName);
    wsEventHandlers();
}

wsConnect();
//console.log('wsReadyState = ' + ws.readyState);

setInterval(function() {
    if ( ws.readyState === 1 )
    {
        console.log('Connected.');
        /*
        ws.send(
            JSON.stringify({
                'message':"Меня зовут Джон",
                'toUsers':['user1','user2'],
            })
        );
        */
    }

    if ( ws.readyState === 3 )
    {
        console.log('Trying to reconnect...');
        wsConnect();
    }

}, 5000);