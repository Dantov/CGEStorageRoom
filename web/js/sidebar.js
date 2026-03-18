"use strict";

function Sidebar() {}

Sidebar.prototype.toggleIcon = function()
{
    //Rotate arrow icons on Sidebar menu
    let target = event.target;

    if ( target.tagName != "A" ) target = target.parentElement;
    if ( !target.classList.contains("sidebarMenuA") ) return;

    if ( target.getAttribute("aria-expanded") == "false" )
    {
        target.lastElementChild.classList.remove("fa-angle-left");
        target.lastElementChild.classList.add("fa-angle-down");
    }

    if ( target.getAttribute("aria-expanded") == "true" )
    {
        target.lastElementChild.classList.remove("fa-angle-down");
        target.lastElementChild.classList.add("fa-angle-left");
    }
};

let sidebar = new Sidebar();
document.getElementById("sidebar").addEventListener("click",sidebar.toggleIcon,true);
