"use strict";
function AddEdit( content )
{
	if ( !content ) return;
	this.content = content;
	
	this.modelID = this.content.querySelector('#modelID').value;

	this.inputs = this.content.querySelectorAll('[editable]');

	this.validator = new Validator();

  this.init();
}

AddEdit.prototype.init = function()
{

  this.clickHandler();
  
  //applyEvents
  this.applyEventsChange(this.inputs);
  this.hashTagsCheckApply();
  this.pubExclDellModel();

  let self = this;
  let cloneButton = document.getElementById('clone-position');
	if (cloneButton) {
		cloneButton.addEventListener('click',function(e){
			self.clonePosition();
		},false);
	}

  let buttonDellF = this.content.querySelector('.fullydell');
	if ( buttonDellF ) 
	{
		buttonDellF.onclick = function()
		{
			self.deleteFull();
		};
	}

  debug( 'AddEdit init ok' );
};

AddEdit.prototype.applyEventsChange = function(inputs)
{
	let self = this;
	for( let i = 0; i < inputs.length; i++ ) {
		inputs[i].addEventListener('change', function(event){ 
			self.changeInpt( this, self, event );
		}, false);
	}
};

AddEdit.prototype.changeInpt = function(input, self, event)
{
	if (event) {
		event.stopPropagation();
  	event.preventDefault();	
	}

	let name = input.getAttribute('name');
	let value = input.value;
	let rowID = input.getAttribute('data-rowID');
	let tableName = input.getAttribute('data-table');
	let modID = self.modelID;

	let obj = {
			name : name,
			value  : value,
			modelID : modID,
	};
	let url = "inputrow";
	if ( rowID && tableName ) {
		url = "editLinkedRow";
		obj.tableName = tableName;
		obj.id = rowID;
	}
	
	$.ajax({
		//url: "/site/edit/",
		url: "/site/edit?v=" + url,
		type: 'POST',
		data: obj,
		dataType:"json",
		success:function(resp) {
			console.log(resp);

			if ( resp ) {
				self.checkToggler(input, 'ok');
				setTimeout(function() {
					self.checkToggler(input, 'ok', true);
				}, 1500);
			} else {
				self.checkToggler(input, 'err');
				setTimeout(function() {
					self.checkToggler(input, 'err', true);
				}, 2500);
			}
		}
		
	});
	
};

AddEdit.prototype.checkToggler = function( input, togg, back )
{
		let bg = input.previousElementSibling.children[0];
		let svg = input.previousElementSibling.children[0].children[0];

		function okToggle( back )
		{
			if ( back ) {
				bg.classList.replace('badge-success','badge-light');
				svg.remove();
				bg.innerHTML = '<i class="fa-regular fa-square-full"></i>';
	
			} else {
				bg.classList.replace('badge-light','badge-success');
				bg.innerHTML = '<i class="fa-regular fa-square-check"></i>';
			}
		}

		function errToggle( back )
		{
			if ( back ) {
				bg.classList.replace('badge-danger','badge-light');
				svg.remove();
				bg.innerHTML = '<i class="fa-regular fa-square-full"></i>';
			} else {
				bg.classList.replace('badge-light','badge-danger');
				bg.innerHTML = '<i class="fa-regular fa-circle-xmark"></i>';
			}
		}

		switch ( togg )
		{
			case "ok":
				return okToggle( back );
			break;
			case "err":
				return errToggle( back );
			break;
		}
};

AddEdit.prototype.clickHandler = function()
{
	let self = this;

	this.content.addEventListener('click', function(event) {
		let click = event.target;
		if ( !click.hasAttribute('elemToAdd') ) return;

		let target = click.parentElement.parentElement.previousElementSibling;

		//target.setAttribute('value', click.innerHTML );
		target.value = click.innerHTML;
		self.changeInpt(target,self);
	});


	let addMats = document.getElementById('addMats');
	if ( addMats ) 
	{
		addMats.addEventListener('click', function(event){
		    event.preventDefault();
		    self.addRowNew('matsProtoRow','tableMats');
		}, false );
	}

	let addGem = document.getElementById('addGems');
	if ( addGem )
	{
	    addGem.addEventListener('click', function(event){
	        event.preventDefault();
	        self.addRowNew('gemsProtoRow','tableGems');
	    }, false );
	}	
};

AddEdit.prototype.addRowNew = function( protoName, targetTableName)
{
	let self = this;
	let tName = '';

	if (targetTableName == 'tableMats') tName = 'materials';
	if (targetTableName == 'tableGems') tName = 'gems';

	let obj = {
				tableName : tName,
				modelID : this.modelID,
			};
	$.ajax({
		url: "/site/edit?v=linktable",
		type: 'POST',
		data: obj,
		dataType:"json",
		success:function(newRowID) {
			debug(newRowID,'addRow on '+ targetTableName +': ');
			
			if (newRowID) {
				let table = self.content.querySelector('.' + targetTableName);
				let protoRow = '';
				let newRow = self.content.querySelector('.' + protoName).cloneNode(true);
					  newRow.classList.remove('d-none',protoName);

				let inputs = newRow.querySelectorAll('input');
						inputs.forEach(input => {
				    	input.setAttribute('data-table',targetTableName);
				    	input.setAttribute('data-rowID',newRowID);
						});
						
				let buttons = newRow.querySelectorAll('button');
						buttons.forEach(btn => {
				    	btn.setAttribute('data-table',targetTableName);
				    	btn.setAttribute('data-rowID',newRowID);
						});
						
				self.applyEventsChange(inputs);

				/*
				$.foreach(inputs, function(i,input){
				});
				*/

				table.appendChild(newRow);
			}
		}
	});
	  
};

AddEdit.prototype.duplicateRowNew = function( self ) 
{

	let tName = self.getAttribute('data-table');
	let rowID = self.getAttribute('data-rowID');

	let obj = {
				tableName : tName,
				rowID : rowID,
				modelID : this.modelID,
	};

	let that = this;
	
	$.ajax({
		url: "/site/edit?v=duplicate",
		type: 'POST',
		data: obj,
		dataType:"json",
		success:function( newRowID ) {
			debug(newRowID,'addRow on '+ tName +': ');
			
			if (newRowID) {
				let table = that.content.querySelector('.' + tName);
				let thisRow = self.parentElement.parentElement.parentElement.parentElement;
				let newRow = thisRow.cloneNode(true);

				let inputs = newRow.querySelectorAll('input');
						inputs.forEach(input => {
				    	input.setAttribute('data-rowID',newRowID);
						});
				that.applyEventsChange(inputs);

				table.insertBefore(newRow, thisRow);
			}
		}
	});
};

AddEdit.prototype.deleteRowNew = function( self ) 
{
	let tName = self.getAttribute('data-table');
	let rowID = self.getAttribute('data-rowID');

	let obj = {
				tableName : tName,
				rowID : rowID,
				modelID : this.modelID,
			};
		debug(obj);

	$.ajax({
		url: "/site/edit?v=dellrow",
		type: 'POST',
		data: obj,
		dataType:"json",
		success:function( dellwRowID ) {
			debug(dellwRowID,'dellRow on '+ tName +': ');
			let row = self.parentElement.parentElement.parentElement.parentElement;
			row.remove();
		}
	});
	
};

AddEdit.prototype.hashTagsCheckApply = function() 
{
	let hashtags = document.querySelector('#hashtags').querySelectorAll('input');
	let self = this;

	$.each(hashtags, function(i, input) {

		self.singleHashtagCheck(input);

	});

//method hashTagCheck
};

AddEdit.prototype.singleHashtagCheck = function(input)
{
	let self = this;

	input.addEventListener('click', function () {

		let selfInpt = this;
		let url = "hashtagcheck";
		let obj = {
						name    : this.getAttribute('name'),
						//dell    : 1,
						value   : this.getAttribute('value'),
						modelID : self.modelID,
					};
		if ( this.hasAttribute('checked') ){
			obj.dell = 1;
			url = 'hashtagdell';
		}
			
		$.ajax({
					url: "/site/edit?v=" + url,
					type: 'POST',
					data: obj,
					dataType:"json",
					success:function(resp) {
						console.log(resp);
						if (resp == true) selfInpt.removeAttribute('checked')

						if (resp == true) selfInpt.setAttribute('checked','')
					}
				});
		
	});
};

AddEdit.prototype.hashtagByText = function(textarea)
{
	let that = this;

	let obj = {
			hashtagByText : 1,
			name    : textarea.getAttribute('name'),
			value   : textarea.value,
			modelID : that.modelID,
	};
	$.ajax({
		url: "/site/edit?v=hashtagByText",
		type: 'POST',
		data: obj,
		dataType:"json",
		success:function(resp) {
			console.log(resp);
			if (resp == true) {
				let hashtags = that.content.querySelector('#hashtags');
				let newtag = hashtags.firstElementChild.cloneNode(true);
						newtag.classList.add('active');
						newtag.firstElementChild.setAttribute('value',textarea.value);
						newtag.firstElementChild.setAttribute('checked','');
						newtag.firstElementChild.nextElementSibling.innerHTML = textarea.value;
						that.singleHashtagCheck(newtag.firstElementChild);
						
				if (hashtags.appendChild(newtag)) 
				{
					textarea.value = '';
					that.checkToggler(textarea, 'ok');
					setTimeout(function() {
						that.checkToggler( textarea, 'ok', true );
					}, 1500);
				}

			} else {
				that.checkToggler(textarea, 'err');
				setTimeout(function() {
					that.checkToggler(textarea, 'err', true);
				}, 2500);
			}
		}
	});
};

AddEdit.prototype.pubExclDellModel = function()
{
		let buttons = document.getElementById('publishRow').querySelectorAll('button');
		let reqest = {
					url : '',
					modelID : this.modelID,
				};
		let self = this;
		$.each(buttons, function(i, button) {
			button.addEventListener('click', function(event) {
				self.submitButtons(this, reqest);
			});
		});
};

AddEdit.prototype.submitButtons = function( button, reqest )
{
	switch ( button.getAttribute('data-publish') )
	{
		case "pub":
			if (!confirm('Все данные верны? Опубликлвать модель?'))
				return;
			reqest.url = "publish";
			if ( !this.validator.validate() ) return;
		break;
		case "excl":
			if (!confirm('Исключить модель из поиска?'))
				return;
			reqest.url = "exclude";
		break;
		case "del":
			if (!confirm('Удалить модель?'))
				return;
			reqest.url = "deletemodel";
		break;
		case "fullyRestore":
			return this.fullyRestore();
		break;
		case "fullyRestore":
			return this.fullyRestore();
		break;
	}
	$.ajax({
			url: "/site/approver-position?v=" + reqest.url,
			type: 'POST',
			data: reqest,
			dataType:"json",
			success:function( resp ) {
				switch ( resp ) {
					case "publish":
						alert('Model is published successfully!');
						reload(true);
					break;
					case "exclude":
						alert('Model is exclude from search successfully!');
						reload(true);
					break;
					case "restored":
						alert('Model is restored!');
						reload(true);
					break;
					case "delete":
						alert('Model is deleted!');
						redirect('/site');
					break;
				}
			}
		});
};

AddEdit.prototype.clonePosition = function()
{
		let conf = confirm("Clone this model?");
		if (!conf) return;

		let self = this;
		$.ajax({
			url: "/site/approver-position?v=clone",
			type: 'POST',
			data: {
				modelID: self.modelID,
			},
			dataType:"json",
			beforeSend:function() {
			},
			success:function( resp ) {
				if ( (resp['result'] == true) && resp['newid'] )
				{
					debug(resp);
					alert('Model was cloned successfully!');
					redirect('/site/edits?model=' + resp['newid']);
					return;
				}
				/*
				let modal = self.content.querySelector('#delete-pos-modal');
				if (modal) {
					if (resp)
						modal.querySelector('.gems').children[0].innerHTML = resp;
				}
				$('#delete-pos-modal').modal('show');
				*/
			}
		});

};

AddEdit.prototype.fullyRestore = function()
{
	let conf = confirm("Restore this model?");
		if (!conf) return;

		let self = this;
		$.ajax({
			url: "/site/approver-position?v=restore",
			type: 'POST',
			data: {
				modelID: self.modelID,
			},
			dataType:"json",
			success:function( resp ) {

				let modal = self.content.querySelector('#delete-pos-modal');
				if (modal) {
					if (resp)
						modal.querySelector('.gems').children[0].innerHTML = resp;
				}

				$('#delete-pos-modal').modal('show');
			}
		});
};
AddEdit.prototype.deleteFull = function()
{
		let conf = confirm("Delete model totally? This action can't be undone!!!");
		if (!conf) return;
		
		let self = this;
		$.ajax({
			url: "/site/approver-position?v=deletefull",
			type: 'POST',
			data: {
				modelID: self.modelID,
			},
			dataType:"json",
			success:function( resp ) {

				let modal = self.content.querySelector('#delete-pos-modal');
				if (modal) {
					if (resp['gems'])
						modal.querySelector('.gems').children[0].innerHTML = "Gems: Deleted!";
					if (resp['materials'])
						modal.querySelector('.materials').children[0].innerHTML = "Materials: Deleted!";
					if (resp['images'])
						modal.querySelector('.images').children[0].innerHTML = "Images: Deleted!";
					if (resp['data'])
						modal.querySelector('.data').children[0].innerHTML = "Data: Deleted!";
					if (resp['files'])
						modal.querySelector('.files').children[0].innerHTML = "Files: Deleted!";
				}

				$('#delete-pos-modal').modal('show');
			}
		});
}

let ae = new AddEdit( document.querySelector('.content') );

function duplicateRow(self) { ae.duplicateRowNew(self); }
function deleteRow(self) { ae.deleteRowNew(self); }
function hashtagByText(self) { ae.hashtagByText(self); }