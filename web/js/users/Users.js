"use strict";
function Users( content, appliedRights )
{
	if ( !content ) return;
	this.content = content;
	this.userid = document.getElementById('uid').value;

	this.appliedRights = appliedRights;
	
	this.allrights = this.content.querySelector('#allrights');
	this.rightsOpt = this.content.querySelectorAll('.rightOpt');
	this.rightsAppr = this.content.querySelector('.appliedPermAll');

  this.init();
}

Users.prototype.init = function()
{
	this.content.querySelector('.dellscript').remove();
  //this.clickHandler();
  
  this.applyEvents();
  //this.hashTagsCheckApply();
  //this.pubExclDellModel();

  debug( 'Users init ok' );
};

Users.prototype.applyEvents = function()
{
	let that = this;
	$.each(this.rightsOpt, function(i, opt) {
		opt.addEventListener('click', that.selectRights.bind(that, opt, event ), false);
	});

	let xBtns = this.rightsAppr.querySelectorAll('.upid');
	$.each(xBtns, function(i, xBtn) {
		//xBtn.addEventListener('click', that.removeRightXBtn.bind(that, xBtn, event ), false);
	});
};

Users.prototype.selectRights = function(option, event)
{
	let self = this;

	let rightData = {
			description : option.innerHTML,
			permid : option.getAttribute('data-permid'),
			name  : option.getAttribute('value'),
			uid : this.userid,
	};

	if ( this.hasRight(rightData) )
	{
		this.removeRight(option, rightData);
	} else {
		this.applyRight(option, rightData);
	}
};
Users.prototype.hasRight = function( rightData )
{
		for ( let i = 0; i < this.appliedRights.length; i++ )
		{
			if ( +rightData.permid === +this.appliedRights[i].permid )
				return true; 
		}
		return false;	
};
Users.prototype.removeRight = function( option, rightData, RowOpt )
{
		let self = this;
		for ( let i=0; i < this.appliedRights.length; i++ )
		{
			let aplR = this.appliedRights[i];
			if ( +rightData.permid === +aplR.permid )
			{
				query(option,rightData);
				this.appliedRights.splice(i, 1);

				debug(self.appliedRights);
				return true;
			}
		}

		function query(option,rightData){
			$.ajax({
			url: "/users/edit-user/?removeright=1",
			type: 'POST',
			data: rightData,
			dataType:"json",
			success:function(resp) {
					console.log(resp);
					if (resp){
						//option.innerHTML = rightData.description;
						option.lastElementChild.remove();
						removeRow(rightData.permid);
					}
				}
			});
		}

		function removeRow(permID) {
			let xBtns = self.rightsAppr.querySelectorAll('.upid');
				$.each(xBtns, function(i, xBtn) {
					let upid = xBtn.getAttribute('data-upid');
					if ( +rightData.permid === +upid ) {
						xBtn.parentElement.remove();
					}
				});
		};

		return false;
};
Users.prototype.applyRight = function( option, rightData )
{
		let self = this;
		$.ajax({
		url: "/users/edit-user/?applyright=1",
		type: 'POST',
		data: rightData,
		dataType:"json",
		success:function(resp) {
				console.log(resp);
				if (resp) {

						let text = option.innerHTML;
						let span = document.createElement('span');
								span.setAttribute('class','right-in-use');
					  		span.innerHTML = " - &#10094;&#10094;applied&#10095;&#10095;"; 
					  option.appendChild(span);

					  self.rightsAppr.appendChild( self.alertRowCreate('secondary', rightData ) );

					  self.appliedRights.push({
				      permid: rightData.permid,
				      name: rightData.name,
				      description: rightData.description,
				    });

					debug(self.appliedRights);
				}
			}
		});
};

Users.prototype.alertRowCreate = function( classcolor, rightData )
{
		let div = document.createElement('div');
		let strong = document.createElement('strong');
		let button = document.createElement('button');
		let span = document.createElement('span');
		let i = document.createElement('i');

		div.setAttribute('class','alert alert-'+classcolor+' alert-dismissible fade show');
		div.setAttribute('role','alert');
		button.setAttribute('class','close upid'); 
		button.setAttribute('data-dismiss','alert'); 
		button.setAttribute('data-upid',rightData.permid); 
		button.setAttribute('aria-label','close');

		let that = this;
		button.addEventListener('click',function(e){

		},false);
		span.setAttribute('aria-hidden','true');
		span.innerHTML = "&times;";
		strong.innerHTML = '"' + rightData.name + '"';
		i.innerHTML = ' &nbsp;' + rightData.description;

		div.appendChild(strong);
		div.appendChild(i);
		button.appendChild(span);
		div.appendChild(button);

		return div;
};


window.addEventListener('load',function() {
	let ae = new Users( document.querySelector('.content'), appliedRights );
},false);