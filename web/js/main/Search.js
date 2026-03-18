"use strict";

function Search()
{
    this.sInput = document.getElementById('search_row'); 
    this.sButton = document.getElementById('search_button'); 
    this.pButton = document.getElementById('purge_button'); 
    this.fromButton = document.getElementById('createdatefrom'); 
    this.toButton = document.getElementById('createdateto'); 
    this.controlRange = document.getElementById('tilesControlRange'); 
	this.init();
}

/** INITIALIZATION **/
Search.prototype.init = function()
{
    let self = this;
    if ( this.sInput ) {
        this.sInput.onchange = function()
        {
            let obj = {
                search_for : self.sInput.value,
            };
            self.request("set", obj);
        }
    }
    if ( this.sButton ) {
        this.sButton.onclick = function()
        {
            let obj = {
                search_for : self.sInput.value,
            };
            self.request("set",obj);
        }
    }
    if ( this.pButton ) {
        this.pButton.onclick = function()
        {
            let obj = {
                clean : 1,
            };
            self.request("purge",obj);
        }
    }

    if ( this.fromButton ) {
        this.fromButton.onchange = function()
        {
            let obj = {
                date : this.value,
            };
            self.request("from-date",obj);
        }
    }
    if ( this.toButton ) {
        this.toButton.onchange = function()
        {
            let obj = {
                date : this.value,
            };
            self.request("to-date",obj);
        }
    }

    if ( this.controlRange ) {
        this.controlRange.onchange = function()
        {
            let obj = {
                size : this.value,
            };
            self.tilesControlSize(obj);
        }
    }

    debug('Search init ok');
};

Search.prototype.request = function( url, obj )
{    
    $.ajax({
        url: "/search/"+ url +"/",
        type: 'POST',
        data: obj,
        dataType:"json",
        success:function(resp) {
            if (resp) reload(true);
        }
    });
};
Search.prototype.tilesControlSize = function( obj )
{    
    $.ajax({
        url: "/search/control-size/",
        type: 'POST',
        data: obj,
        dataType:"json",
        success:function(resp) {
            if (resp['done']) {
                
                let cardsDoc = document.getElementById('cards');
                let cards = cardsDoc.querySelectorAll('.mainCard');
                
                cards.forEach(card => {
                    card.style.width = resp.size + "rem";
                });
            }
        }
    });
};

let search_for = new Search();