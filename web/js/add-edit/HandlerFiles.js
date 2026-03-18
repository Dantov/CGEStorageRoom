function HandlerFiles( dropArea, button, filterTypes )
{
    this.modelID = document.querySelector('#modelID').value;

    this.dropArea = dropArea;
    this.button = button;

    this.fileTypes = Array.isArray(filterTypes) ? filterTypes : [];
    this.fileBuffer = []; //здесь хранятся все загруженные файлы. это массив файлов, не FileList

    this.imageFilesBuffer = [];
    this.stlFilesBuffer = [];
    this.rhinoFilesBuffer = [];
    this.aiFilesBuffer = [];

    this.stlOveralSize = 0;
    this.rhinoOveralSize = 0;

    this.init();
}

HandlerFiles.prototype.init = function() {

    let self = this;

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        self.dropArea.addEventListener(eventName, function (e) {
            e.preventDefault();
            e.stopPropagation();
        }, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        self.dropArea.addEventListener(eventName, function () {
            self.highlight(this);
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        self.dropArea.addEventListener(eventName, function () {
            self.unhighlight(this);
        }, false);
    });

    this.dropArea.addEventListener('drop', function(e) {
        let dt = e.dataTransfer;
        let files = dt.files; // FileList
        self.handleFiles(files);
    }, false);

    this.button.addEventListener('click', function() {

        let typesStr = self.fileTypes.join(',');
        let fileInput = document.createElement('input');

        fileInput.setAttribute('type','file');
        fileInput.setAttribute('multiple','');
        fileInput.setAttribute('accept',typesStr);

        fileInput.addEventListener('change',function () {
            self.handleFiles(this.files);
        });

        fileInput.click();
    }, false);

    let removeDataFiles = document.querySelectorAll('.remove3dfile');
    $.each(removeDataFiles, function (i, button) {
        button.addEventListener('click',function () {
            //let dataType = this.getAttribute('data-filetype');
            let rowID = this.getAttribute('data-rowid');
            let fName = this.parentElement.parentElement.previousElementSibling.children[0].innerHTML;
            let fsize = this.parentElement.parentElement.previousElementSibling.children[0].nextElementSibling.innerHTML;
            if ( confirm('Удалить файл ' + ':  << ' + fName + ' >>  ' + '(' + fsize + ') ?') )
                self.removeFile(this, rowID, 'data');
        },false);
    });
    let removeIMGFiles = document.querySelectorAll('.img_dell');
    $.each(removeIMGFiles, function (i, button) {
        button.addEventListener('click',function () {
            //let dataType = this.getAttribute('data-type');
            let rowID = this.getAttribute('data-rowid');
            if ( confirm('Удалить картинку?') )
                self.removeFile(this,rowID,'picture');
        },false);
    });
    let radioInputs = document.querySelectorAll('input[name="imgMainRadioOption"]');
    $.each(radioInputs, function (i, input) {

        input.addEventListener('click',function () {
            //let dataType = this.getAttribute('data-type');
            //let rowID = this.getAttribute('data-rowid');
            self.setMainImgTag(this, this.getAttribute('data-rowid'));
        },false);

        let header = input.parentElement.parentElement;
        header.addEventListener('click',function () {
            input.click();
        },false);
    });

    debug('HandlerFiles Init(ok)');
};

HandlerFiles.prototype.highlight = function(dropArea)
{
    dropArea.classList.remove('border-secondary');
    dropArea.classList.add('bg-primary','text-white', 'border-dark');
};

HandlerFiles.prototype.unhighlight = function(dropArea)
{
    dropArea.classList.remove('bg-primary','text-white', 'border-dark');
    dropArea.classList.add('border-secondary');
};

HandlerFiles.prototype.handleFiles = function(files)
{
    let self = this;
    Array.prototype.push.apply( this.fileBuffer, files );
    files = [...files];
    //debug( self.fileTypes,'fileTypes');
    let swfileType = '';
    files.forEach(function (file)
    {
        let arr_split = file.name.split('.');
        let fileExtension = arr_split[arr_split.length-1].toLowerCase();
        if ( self.fileTypes.includes(file.type) )
        {   
            //*** картинки здесь ***//
            swfileType = 'img';
        } else if ( self.fileTypes.includes('.' + fileExtension) ) {
            
            //*** Остальные ***//
            swfileType = 'others';
            //self.addDataFile(file, fileExtension);
        }

        self.pushFileToServ(file, fileExtension, swfileType);
    });
    this.fileBuffer = [];
};

HandlerFiles.prototype.pushFileToServ = function(file, fileExtension, swfileType)
{
    //debug(file,'pushImgFileToServ file');
    //debug(this,'pushImgFileToServ THIS');
    let self = this;
    let preLoadRow; // Prototype
    let tempRow; // already inserted row

    let formData = new FormData();
        formData.append('modelID', this.modelID );
    switch ( swfileType )
    {
        case "img":
            formData.append('UploadImage',file);

            preLoadRow = document.getElementById('proto-pre-load-img').cloneNode(true);
            preLoadRow.removeAttribute('id');
            preLoadRow.classList.remove('d-none');
            //preLoadDataRow.classList.add('d-flex');
            break;
        case "others":
            formData.append('Upload3DFile',file);
            formData.append('fileExtension',fileExtension);

            preLoadRow = document.getElementById('proto-pre-load-data').cloneNode(true);
            preLoadRow.removeAttribute('id');
            preLoadRow.classList.remove('d-none');
            //preLoadRow.classList.add('d-flex');
            break;
    }

    let xhr;
    xhr = $.ajax({
        url: '/site/edit?v=pushfiles',
        type: 'POST',
        //dataType: "html", //формат данных
        //dataType: "json", // не работает с new FormData object
        //data: $("#addform").serialize(),
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function()
        {
            if ( swfileType == 'others' )
                tempRow = document.getElementById('d3-files-area').appendChild(preLoadRow);
            if ( swfileType == 'img' )
            {
                let target = document.getElementById('picts').parentElement;
                tempRow = target.insertBefore(preLoadRow, document.getElementById('picts'));
            }
                //tempRow = document.getElementById('picts').appendChild(preLoadRow);
        },
        xhr: function() {
            let xhr = $.ajaxSettings.xhr(); // получаем объект XMLHttpRequest

            // добавляем обработчик события progress (onprogress)
            xhr.upload.addEventListener('progress', function(evt) {
                //debug(evt);

                if(evt.lengthComputable)
                { 
                    // если известно количество байт высчитываем процент загруженного
                    let percentComplete = Math.ceil(evt.loaded / evt.total * 100);
                    // устанавливаем значение в атрибут value тега <progress>
                    // и это же значение альтернативным текстом для браузеров, не поддерживающих <progress>
                    // progressUpload.val(percentComplete).text('Загружено ' + percentComplete + '%');
                    if ( swfileType == 'others' )
                        self.preLoadDataFile(percentComplete, tempRow, file.name);
                    if ( swfileType == 'img' )
                        self.preLoadImgFile(percentComplete, tempRow);
                }

            }, false);

            return xhr;
        },
        success:function( resp )
        {
            resp = JSON.parse(resp);
            if ( !resp['upload'] ) {
                AR.warning( resp['txt'], 505 );
                tempRow.remove();
                return;
            }

            if ( resp['type'] === 'picture' ) {
                //self.imageFilesBuffer.push(file);
                self.previewIMGFile( file, resp['id'], tempRow );
            }
            if ( resp['type'] === 'data' ) {
                self.preview3DFile( file, fileExtension, resp['id'], tempRow );
            }

            debug(resp['id'],'Row id');
        },
        error: function(error) { 
            //AR.serverError( error.status, error.responseText );
            // modal.iziModal('setTitle', 'Ошибка отправки! Попробуйте снова.');
            // modal.iziModal('setHeaderColor', '#FF5733');
        }
    });
};

/**
 * PREVIEW Data Files
 */
HandlerFiles.prototype.preLoadDataFile = function(percentComplete, tempDataRow, filename)
{
    let progressUpload = tempDataRow.querySelector('.prog-bar-data-files');
        progressUpload.setAttribute('aria-valuenow', percentComplete+'');
        progressUpload.style.width = percentComplete + "%";
        progressUpload.innerHTML = percentComplete + "%";

    if ( percentComplete == 100 )
    {
        let text = tempDataRow.querySelector('.data-file-text-info');
        text.innerHTML = "Данные получены сервером.<br/>";
        text.innerHTML += '<i class="fa-regular fa-floppy-disk"></i> Сохранение... ' + filename + ' ждите.';
    }
};
HandlerFiles.prototype.preview3DFile = function(file,type,rowID,tempDataRow)
{
    let self = this;
    let areaID = 'd3-files-area';
    let mb = 'Мб';

    let size = ( (file.size / 1024) / 1024 ).toFixed(2);

    let fileBlock = document.getElementById('proto_3d_row').cloneNode(true);
        fileBlock.removeAttribute('id');
        fileBlock.classList.remove('d-none');
        fileBlock.classList.add('d-flex');
        fileBlock.querySelector('.imglable3dfile').src = '/web/pictAssets/icon_' + type + '.png';
        fileBlock.querySelector('.d3filename').innerHTML =  file.name;
        fileBlock.querySelector('.overallSize').innerHTML =  'Size: ' + '(' + size + ' ' + mb +')';

    let removeBtn = fileBlock.querySelector('.remove3dfile');
        removeBtn.setAttribute('data-rowid',rowID);
        removeBtn.onclick = function() {
            if ( confirm('Удалить файл ' + type + ':  << ' + file.name + ' >>  ' + '(' + size + ' ' + mb +')') )
                self.removeFile( this, rowID, 'data' );
        };
    let res = document.getElementById(areaID).appendChild(fileBlock);
    if ( res )
        tempDataRow.remove();
};


/**
 *  PREVIEW Image Files
 */
HandlerFiles.prototype.preLoadImgFile = function(percentComplete, tempImgRow)
{
    let progressUpload = tempImgRow.querySelector('.prog-bar-img-files');
        progressUpload.setAttribute('aria-valuenow', percentComplete+'');
        progressUpload.style.width = percentComplete + "%";
        progressUpload.innerHTML = percentComplete + "%";

    if ( percentComplete == 100 )
    {
        progressUpload.innerHTML = '<i class="fa-regular fa-floppy-disk"></i> Сохранение... ';
    }
};
HandlerFiles.prototype.previewIMGFile = function(file, imgRowID, tempImgRow) 
{
    debug(file, 'file prew img');

    let self = this;

    let reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = function() {

        let imgRow = document.getElementById('proto_image_row').cloneNode(true);
            imgRow.removeAttribute('id');
            imgRow.setAttribute('fileId',file.lastModified);
            imgRow.classList.add('image_row');
            imgRow.classList.remove('d-none');

        let dellButton = imgRow.querySelector('.img_dell');
            dellButton.removeAttribute('onclick');
            dellButton.setAttribute('data-rowid',imgRowID);
            dellButton.setAttribute('data-table','tableIMG');
            dellButton.addEventListener('click',function () {
                if ( confirm('Удалить картинку?') )
                    self.removeFile(this, imgRowID, 'picture');
            });

        let radioInput = imgRow.querySelector('input[name="imgMainRadioOption"]');
            radioInput.setAttribute('data-rowid',imgRowID);
            radioInput.setAttribute('data-table','tableIMG');
            radioInput.addEventListener('change',function () {
                self.setMainImgTag(this, imgRowID); //setMainImg
            });
        imgRow.firstElementChild.addEventListener('click',function () {
                radioInput.click();
            });

        imgRow.querySelector('.img_name_show').innerHTML = file.name;
        imgRow.querySelector('input').setAttribute('id',file.lastModified);
        imgRow.querySelector('input').setAttribute('data-rowID',imgRowID);
        imgRow.querySelector('input').setAttribute('data-table','tableIMG');
        imgRow.querySelector('label').setAttribute('for',file.lastModified);

        /*
        imgRow.querySelector('select').addEventListener('change',function () {
            self.onSelect(this);
        });
        */

        let img = imgRow.getElementsByTagName('img');
            img[0].src = reader.result;

        //document.getElementById('picts').insertBefore(imgRow, self.dropArea.parentElement);
        let res = document.getElementById('picts').appendChild(imgRow);
        if ( res )
        tempImgRow.remove();
    }
};

HandlerFiles.prototype.removeFile = function(self, imgRowID, fileType)
{
    let obj = {
        rowID   : imgRowID,
        modelID : this.modelID,
        fileType : fileType,
    };
    $.ajax({
        url: "/site/edit?v=dellFile",
        type: 'POST',
        data: obj,
        dataType:"json",
        success:function(resp) {
            console.log(resp);
            // here need to implement OK check for every input
            switch ( resp['type'] )
            {
                case "picture":
                    if ( resp['file'] && resp['row'] )
                        self.parentElement.parentElement.parentElement.remove();
                break;
                case "data":
                    if ( resp['file'] && resp['row'] )
                        self.parentElement.parentElement.parentElement.parentElement.remove();
                break;
            }
        }
    });
};

HandlerFiles.prototype.setMainImgTag = function(self, imgRowID)
{
    //debug(imgRowID, 'imgRowID setMainImg');
    let obj = {
            imgRowID : imgRowID,
            modelID  : this.modelID,
    };
    $.ajax({
        url: "/site/edit?v=setMainImg",
        type: 'POST',
        data: obj,
        dataType:"json",
        success:function(resp) {
            // here need to implement OK check for every input
            console.log(resp);
            if ( resp )
            {
                let allCards = document.getElementById('picts').querySelectorAll('.mainCard');
                allCards.forEach(card=>{
                    card.firstElementChild.classList.remove('bg-success');    
                    card.firstElementChild.classList.add('bg-dark');
                    card.querySelector('.form-check-label').innerHTML = "";
                });
                self.parentElement.parentElement.classList.remove('bg-dark');
                self.parentElement.parentElement.classList.add('bg-success');
                self.nextElementSibling.innerHTML = "Главная";   
            }
        }
    });
}

let fileTypes = ["image/jpeg", "image/png", "image/gif","image/webp",'.3dm','.stl','.mgx','.ai','.dxf','.obj','.rar','.zip'];
window.addEventListener('load',function() {
  handlerFiles = new HandlerFiles( document.getElementById('drop-area'), document.getElementById('addImageFiles'),fileTypes);
},false);