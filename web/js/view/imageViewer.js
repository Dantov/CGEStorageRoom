"use strict";
function ImageViewer()
{
    this.nW = null; //natural Width (оригинальная ширина картинки)
    this.nH = null; //natural Height (оригинальная высота картинки)

    /*
    * реальная( такая на данный момент ) Ширина и высота в пикселях
    * после увеличения картинки (клик по +)
    * */
    this.realW = null;
    this.realH = null;
    this.percent = 0; // процент на который увеличена картинка

    this.timerResizeId = null; // переменная Интервала
    this.imgSrc = null;
    this.activeImage = null;

    /*
    * переменные для передвижения картинки
    * */
    this.plusActive = false; // когла true - картинка увеличена
    this.savedMouseX = null;
    this.savedMouseY = null;

    //Позиция главной фоновой картинки
    this.bgPosX = null;
    this.bgPosY = null;

    this.imageViewer = document.querySelector('.imageViewer');
    this.mainImage = document.querySelector(".mainImage");
    this.closeImageViewer = this.imageViewer.querySelector('.closeImageViewer');
    this.plusSize = this.imageViewer.querySelector('.sizePlus');
    this.minusSize = this.imageViewer.querySelector('.sizeMinus');
}

ImageViewer.prototype.init = function() // вешает обработчики
{
    let that = this;

    /*  thatObj
        добавляем ссылку на этот объект,
        для доступа к его свойствам из функций добавленых addEventListener
     */
    this.imageViewer.thatObj = this;

    this.mainImage.addEventListener('click', function()
    {
        that.start();
    }, false);

    this.closeImageViewer.addEventListener('click', function(event)
    {
        event.preventDefault();
        that.close();
    }, false);

    this.plusSize.addEventListener('click',function (event)
    {
        event.preventDefault();
        that.sizePlus();
    }, false);

    this.minusSize.addEventListener('click', function (event)
    {
        event.preventDefault();
        that.sizeMinus();
    }, false);

};
ImageViewer.prototype.start = function() // запускаем просмотр картинок при клике на главную
{
    let imgSrc = this.mainImage.style.backgroundImage;

    this.setBackgroundImage(imgSrc);
    this.bottomImages();

    document.body.style.overflow = 'hidden'; // убираем полосу прокрутки

    $('#imageViewer').modal('show');
};
ImageViewer.prototype.bottomImages = function() // ставим доп картинки внизу слева
{
    let that = this;
    let dopImages = document.querySelector('#bottomDopImages').querySelectorAll('img');
    let bottomImgRow = this.imageViewer.querySelector('.bottomImgRow');

    // удаляем старые, если они были
    let oldRow = bottomImgRow.querySelectorAll('div');
    if ( oldRow.length )
    {
        oldRow.forEach(div => {
            div.remove();
        });
    }

    // формируем новые
    let flagActive = false;
    for ( let i = 0; i < dopImages.length; i++ )
    {
        let src = dopImages[i].src.split('hufdbnew.local')[1];
        let div = document.createElement('div');
        div.classList.add('p-2');

        //debug(src.split('/')[4]);
        //debug(this.imgSrc);
        if ( src.split('/')[4] == this.imgSrc.split('/')[2] ) // сравниваем имена файлов имя файлов, а не путь
        {
            div.classList.add('border-bottomImgRowActive','activeImage');
            flagActive = true;
        } else {
            div.classList.add('border-bottomImgRow');
        }

        div.style.backgroundImage = "url("+ src  +")";
        div.style.width = 4+"rem";
        div.style.height = 4+"rem";

        let appended = bottomImgRow.appendChild(div);
        if ( flagActive )
        {
            this.activeImage = appended;
            flagActive = false;
        }

        appended.onclick = function()
        {
            //that.imageViewer.style.backgroundImage = this.style.backgroundImage;

            that.setBackgroundImage(this.style.backgroundImage);

            that.activeImage.classList.remove('activeImage','border-bottomImgRowActive');
            that.activeImage.classList.add('border-bottomImgRow');

            this.classList.remove('border-bottomImgRow');
            this.classList.add('activeImage','border-bottomImgRowActive');

            that.activeImage = this;

            that.percent = 0;
            that.clearEventsListenersFromIMG();

        };
    }

};
ImageViewer.prototype.setInterval = function() // ставим доп картинки внизу слева
{
    let that = this;
    clearInterval(this.timerResizeId);
    this.timerResizeId = setInterval(function () {
        that.setBgSize();
    }, 500);
};
ImageViewer.prototype.setBackgroundImage = function(imgSrc) // ставим доп картинки внизу слева
{
    let src = imgSrc.split("\"")[1]; // выделим чистый путь к файлу
    this.imgSrc = src;

    let that = this;
    let img = new Image(); // создаем картинку
    img.src = src;
    img.onload = function() {

        that.nW = this.naturalWidth;
        that.nH = this.naturalHeight;

        // мзначально реальные размеры равны натуральным
        that.realW = this.naturalWidth;
        that.realH = this.naturalHeight;

        //debug(this.width + 'x' + this.height);
        //debug(this.naturalWidth + 'x' + this.naturalHeight);

        that.imageViewer.style.backgroundImage = imgSrc;
        that.setBgSize();
        that.setInterval();
    };

};
ImageViewer.prototype.setBgSize = function() // ставит backgroundSize на просмотре
{
    let screenW = document.documentElement.clientWidth;
    let screenH = document.documentElement.clientHeight;


    if ( this.nH > screenH || this.nW > screenW ) {
        //if ( this.imageViewer.style.backgroundSize == "contain" ) return;
        this.imageViewer.style.backgroundSize = "contain";

        debug('setBgSize used = ' +  this.imageViewer.style.backgroundSize);
    } else {
        //if ( this.imageViewer.style.backgroundSize == "auto" ) return;
        this.imageViewer.style.backgroundSize = "auto";

        debug('setBgSize used = ' + this.imageViewer.style.backgroundSize);
    }

    this.imageViewer.style.backgroundPositionX = "";
    this.imageViewer.style.backgroundPositionY = "";
    //debug('setBgSize used');
};



ImageViewer.prototype.sizeIncrese = function() // увеличим картинку на 1/4
{
    if ( this.percent >= 100 ) return; // макс увеличение в 2 раза

    this.percent += 25;
    debug(this.percent,'this.percent');

    this.realW = this.nW + (this.nW * this.percent ) / 100;
    this.realH = this.nH + (this.nH * this.percent ) / 100;

    this.imageViewer.style.backgroundSize = this.realW + "px" + "," + this.realH + "px";

};
ImageViewer.prototype.sizeDecrese = function() // уменьшим картинку на 1/4
{
    if ( this.percent === 0 ) return;

    this.realW = this.realW - (this.nW * 25 ) / 100;
    this.realH = this.realH - (this.nH * 25 ) / 100;

    this.imageViewer.style.backgroundSize = this.realW + "px" + "," + this.realH + "px";

    this.percent -= 25;
    debug(this.percent,'this.percent');
};
ImageViewer.prototype.sizePlus = function() // увеличим картинку на полную
{
    //if ( this.plusActive === true ) return;

    clearInterval(this.timerResizeId);
    this.plusActive = true;

    let imageViewer = this.imageViewer;
    let screenW = document.documentElement.clientWidth;
    let screenH = document.documentElement.clientHeight;


    if ( this.realH < screenH && this.realW < screenW ) {
        this.sizeIncrese();
        if ( !(this.realH > screenH || this.realW > screenW) ) return;
    } else {
        imageViewer.style.backgroundSize = "auto";
    }

    let that = this;

    debug(imageViewer.style.backgroundSize);


    imageViewer.classList.add('cursorPun');
    imageViewer.addEventListener('mousedown', that.mouseDownIMG);
    imageViewer.addEventListener('mouseup', that.mouseUpIMG );

    this.bgPosY = ( (screenH - this.nH) - 1 ) / 2;
    this.bgPosX = ( (screenW - this.nW) - 17 ) / 2;

    imageViewer.style.backgroundPositionX = this.bgPosX + "px";
    imageViewer.style.backgroundPositionY = this.bgPosY + "px";
};
ImageViewer.prototype.mouseUpIMG = function(event)
{
    if ( event.target != this ) return;
    let thatObj = this.thatObj; // object ImageViewer

    this.classList.remove('cursorMove');

    thatObj.bgPosX = +this.style.backgroundPositionX.split('px')[0];
    thatObj.bgPosY = +this.style.backgroundPositionY.split('px')[0];
    debug("--------------------------");
    debug(thatObj.bgPosX,'ImageViewer bgPosX');
    debug(thatObj.bgPosY,'ImageViewer bgPosY');
    debug(this.style.backgroundPositionX,'ImageViewer backgroundPositionX');
    debug(this.style.backgroundPositionY,'ImageViewer backgroundPositionY');

    this.removeEventListener('mousemove', thatObj.moveIMG);
    debug('mousemove removed');
};
ImageViewer.prototype.mouseDownIMG = function(event)
{
    if ( event.target != this ) return;

    this.classList.add('cursorMove');

    let thatObj = this.thatObj; // object ImageViewer

    // коорд мыши в окне
    thatObj.savedMouseX = event.clientX;
    thatObj.savedMouseY = event.clientY;

    debug("--------------------------");
    debug(thatObj.savedMouseX,'MD savedMouseX');
    debug(thatObj.savedMouseY,'MD savedMouseY');
    debug(this.style.backgroundPositionX,'MD backgroundPositionX');
    debug(this.style.backgroundPositionY,'MD backgroundPositionY');

    this.addEventListener('mousemove',thatObj.moveIMG);
};
ImageViewer.prototype.moveIMG = function(event)
{
    let thatObj = this.thatObj; // object ImageViewer

    let curentMouseX = event.clientX;
    let curentMouseY = event.clientY;

    debug(curentMouseX,'MM curentMouseX');
    debug(curentMouseY,'MM curentMouseY');

    let diffX = curentMouseX - thatObj.savedMouseX;
    let diffY = curentMouseY - thatObj.savedMouseY;

    debug(diffX,'MM diffX');
    debug(diffY,'MM diffY');

    let x = diffX + thatObj.bgPosX;
    let y = diffY + thatObj.bgPosY;

    debug(x,'MM new X');
    debug(y,'MM new Y');

    this.style.backgroundPositionX = x + "px";
    this.style.backgroundPositionY = y + "px";

    debug(this.style.backgroundPositionX,'MM backgroundPositionX');
    debug(this.style.backgroundPositionY,'MM backgroundPositionY');
};
ImageViewer.prototype.clearEventsListenersFromIMG = function()
{
    let that = this;

    this.imageViewer.removeEventListener('mousedown', that.mouseDownIMG);
    this.imageViewer.removeEventListener('mouseup', that.mouseUpIMG);

    debug('Listeners removed');

    this.plusActive = false;
};

ImageViewer.prototype.sizeMinus = function() // закрываем просмотр картинок при клике на крестик
{
    if ( this.plusActive === false ) return;

    this.sizeDecrese();

    if ( this.percent === 0 )
    {
        this.imageViewer.classList.remove('cursorPun');
        this.clearEventsListenersFromIMG();
        this.setBgSize();
        this.setInterval();
    }

};



ImageViewer.prototype.close = function() // закрываем просмотр картинок при клике на крестик
{
    let that = this;
    $('#imageViewer').on('hide.bs.modal', function () {
        clearInterval(that.timerResizeId);

        that.clearEventsListenersFromIMG();

        document.body.style.overflow = 'visible'; // ставим полосу прокрутки
    });
};

let imageViewer = new ImageViewer();
imageViewer.init();