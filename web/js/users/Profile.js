"use strict";
class Profile
{

	constructor( content )
	{
		if ( !content ) return;
		this.content = content;
		this.userID = this.content.querySelector('#userid').value;
		this.inputs = this.content.querySelectorAll('[editable]');

		this.userAvatar = this.content.querySelector('.userAvatar');

		this.fileTypes = ["image/jpeg", "image/png", "image/gif","image/webp"];

		this.uplbtn = this.content.querySelector('#addImageFiles');
		this.uploadButton();
	  this.applyEventsChange(this.inputs);

	  debug( 'Profle init ok' );
	}

	uploadButton()
	{
		if ( !this.uplbtn ) return;
		let self = this;
		this.uplbtn.addEventListener('click', function() {

        let typesStr = self.fileTypes.join(',');
        let fileInput = document.createElement('input');

        fileInput.setAttribute('type','file');
        fileInput.setAttribute('accept',typesStr);

        fileInput.addEventListener('change',function () {
            self.handleFiles(this.files);
        });

        fileInput.click();
    }, false);
	}

	handleFiles (files)
	{
	  let self = this;

	  files = [...files];
	  files.forEach(function (file) {
	      let arr_split = file.name.split('.');
	      let fileExtension = arr_split[arr_split.length-1].toLowerCase();

	      if ( self.fileTypes.includes(file.type) )
	      {   
	          self.pushFile(file, fileExtension);
	      } 
	      
	  });
	  
	}

	pushFile(file, fileExtension)
	{
	    let self = this;

	    let formData = new FormData();
	        formData.append('uid', this.userID );
	        formData.append('UploadImage',file);

	    let xhr;
	    xhr = $.ajax({
	        url: '/site/profile?edit=picture',
	        type: 'POST',
	        data: formData,
	        processData: false,
	        contentType: false,
	        beforeSend: function() {},
	        xhr: function() {
	            let xhr = $.ajaxSettings.xhr(); // получаем объект XMLHttpRequest
	            // добавляем обработчик события progress (onprogress)
	            xhr.upload.addEventListener('progress', function(evt) {
	                //debug(evt);

	                if(evt.lengthComputable)
	                { 
	                    // если известно количество байт высчитываем процент загруженного
	                    //let percentComplete = Math.ceil(evt.loaded / evt.total * 100);
	                    // устанавливаем значение в атрибут value тега <progress>
	                    // и это же значение альтернативным текстом для браузеров, не поддерживающих <progress>
	                    // progressUpload.val(percentComplete).text('Загружено ' + percentComplete + '%');
	                    //self.preLoadImgFile(percentComplete, tempRow);
	                }
	            }, false);

	            return xhr;
	        },
	        success:function( resp )
	        {
	            resp = JSON.parse(resp);
	            if ( !resp['upload'] ) {
	                AR.warning( resp['txt'], 505 );
	                return;
	            }

	            if ( resp['filename'] ) {
	                self.userAvatar.src = "/images/users/" + resp['filename'];
	            }
	           
	        },
	        error: function(error) { 
	            //AR.serverError( error.status, error.responseText );
	            // modal.iziModal('setTitle', 'Ошибка отправки! Попробуйте снова.');
	            // modal.iziModal('setHeaderColor', '#FF5733');
	        }
	    });
	};

	applyEventsChange(inputs)
	{
		let self = this;
		for( let i = 0; i < inputs.length; i++ ) {
			inputs[i].addEventListener('change', function(event){ 
				self.changeInpt( this, self, event );
			}, false);
		}
	}

	changeInpt(input, self, event)
	{
		if (event) {
			event.stopPropagation();
	  	event.preventDefault();	
		}
		let name = input.getAttribute('name');
		let obj = {
				name : name,
				value  : input.value,
				uid : self.userID,
		};
		
		$.ajax({
			url: "/site/profile?edit=text",
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
	}

	checkToggler( input, togg, back )
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
	}
	
} //END class Profile


let profile = new Profile( document.querySelector('.content') );