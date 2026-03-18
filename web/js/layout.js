"use strict";
$(document).ready(function () {

	//Sidebar-nav Js
	$('#sidebarCollapse').on('click', function () {
	    $('#sidebar').toggleClass('active');
	});
	//Tooltip
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	});
	//hideURLbar
	window.scrollTo(0, 1);

	//localStorage.clear();

	let sidemenu = document.querySelector('.components');
	let allLinks = sidemenu.querySelectorAll('a[data-toggle="collapse"]');

	//Will open sidemenu that was remembered 
	if ( localStorage.getItem('listNames') )
	{
		let listnames = JSON.parse(localStorage.getItem('listNames'));
		$.each(listnames, function(listname, collapsed) 
		{
			$.each(allLinks, function(i, singleLink) 
			{
				if ( singleLink.getAttribute('href') == listname ) 
				{
					if ( collapsed ) { //need to open
						singleLink.setAttribute('data-closed','false');
						singleLink.lastElementChild.classList.toggle('fa-angle-left');
						singleLink.lastElementChild.classList.toggle('fa-angle-down');
						singleLink.click();
					}
				}
			});
		});
	}
	
	sidemenu.addEventListener('click',function(e){
		let click = e.target;
		let b,collapsed,listName;
		let isListClosed;

		b = (click.hasAttribute('data-toggle') || click.classList.contains('fa-angle-down') || click.classList.contains('fa-angle-left'));
		if ( !b ) return;

		if ( click.hasAttribute('data-toggle') ) { // was click by text 

			// Remember opened sidemenu 
			if (click.getAttribute('data-closed') == "true"){
				click.setAttribute('data-closed','false')
				collapsed = true;
			} else if (click.getAttribute('data-closed') == "false") {
				//close
				click.setAttribute('data-closed','true');
				collapsed = false;
			} 
			listName  = click.getAttribute('href');

			click.lastElementChild.classList.toggle('fa-angle-down');
			click.lastElementChild.classList.toggle('fa-angle-left');    
		} else {
			//was click by right arrow
			// Remember opened sidemenu 
			if (click.parentElement.getAttribute('data-closed') == "true"){
				click.parentElement.setAttribute('data-closed','false')
				collapsed = true;
			} else if (click.parentElement.getAttribute('data-closed') == "false") {
				//close
				click.parentElement.setAttribute('data-closed','true');
				collapsed = false;
			}
			listName = click.parentElement.getAttribute('href');

			click.classList.toggle('fa-angle-down');
			click.classList.toggle('fa-angle-left');
		}

		let listnames = JSON.parse(localStorage.getItem('listNames')) ?? {};
		listnames[listName] = collapsed;
		localStorage.setItem('listNames', JSON.stringify(listnames) );
		
		//debug(localStorage.getItem('listNames'), 'listnames');

	},false);

	let publishall = sidemenu.querySelector('.publishall');
	if (publishall)
	{
		publishall.onclick = function(event){
			event.stopPropagation();
  			event.preventDefault();	

			let conf = confirm("Sure to publish all models?");
			if (!conf) return;
			
			$.ajax({
				url: "/site/approver-position?v=publishall",
				type: 'POST',
				data: {
					modelID: 1,
				},
				dataType:"json",
				success:function( resp ) {
					if (resp == 'true')
						reload(true);
				}
			});
		}
	}

	let jewelbox = new JewelBox();
 });