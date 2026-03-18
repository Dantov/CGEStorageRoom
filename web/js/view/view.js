"use strict";

let mainImage = document.querySelector(".mainImage");
let bottomDopImages = document.getElementById("bottomDopImages").querySelectorAll(".ratio");

let activeImage = false;

for ( let i = 0; i < bottomDopImages.length; i++ )
{
    //clickImages(bottomDopImages[i], mainImage);
    if ( bottomDopImages[i].classList.contains('activeImage') ) activeImage = bottomDopImages[i];

    bottomDopImages[i].addEventListener("click",function (event) {

        let img = this.getElementsByTagName('img');
        let src = img[0].getAttribute('src');
        //console.log( src );
        //mainImage.children[0].setAttribute('src',src);
        mainImage.style.backgroundImage = "url("+ src +")";

        activeImage.classList.remove('activeImage','border-primary');
        activeImage.classList.add("border-light");

        this.classList.remove('border-light');
        this.classList.add('activeImage','border-primary');
        activeImage = this;
    });

}

let coords=null;
mainImage.addEventListener('mouseover',function (event) {

    coords = this.getBoundingClientRect();
    //debug(coords.left,"left");
    //debug(coords.top,"top");

    this.style.backgroundSize = 200 + "%";
    this.classList.add('loupeCursor');

    // ширина высота
    // debug(this.offsetHeight,"offsetHeight");
    // debug(this.offsetWidth,'offsetWidth');
});
mainImage.addEventListener('mouseout',function (event) {

    coords = null;
    this.style.backgroundSize = "contain";
    this.style.backgroundPosition = "center center";
    this.classList.remove('loupeCursor');

});
mainImage.addEventListener('mousemove',function (event) {
   //debug(event.clientX - coords.left,"X");
   //debug(event.clientY - coords.top,"Y");

   let x = -( (event.clientX - coords.left)-200 ) * 2;
   let y = -( (event.clientY - coords.top) ) * 2;

   this.style.backgroundPositionX = x + "px";
   this.style.backgroundPositionY = y + "px";

});



