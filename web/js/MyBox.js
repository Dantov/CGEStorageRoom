"use strict";
class MyBox 
{
    constructor()
    {
        this.jbBadgeCount = 0;
        this.jbBadgeTag = document.querySelector('.jbBadge');
        if ( this.jbBadgeTag ) this.jbBadgeCount = this.jbBadgeTag.innerHTML;

        this.jbBtns = document.querySelectorAll('.jewelboxBtnMain');
        if ( this.jbBtns ) this.btnsClickApply(this.jbBtns, "add");

        this.jbBtnV = document.querySelectorAll('.jewelboxBtnView');
        if ( this.jbBtnV ) this.btnsClickApply(this.jbBtnV, "add");

        this.editBtns = document.querySelectorAll('.editbtnJewelBox');
        if ( this.editBtns ) this.btnsClickApply(this.editBtns, "return");

        this.openBtns = document.querySelectorAll('.JewelBoxOpenModel');
        if ( this.openBtns ) this.btnsClickApply(this.openBtns, "openmodel");

        this.openAllBtn = document.querySelectorAll('.JewelBoxOpenAllModels');
        if ( this.openAllBtn ) this.btnsClickApply(this.openAllBtn, "openallmodels");

        this.editstoreprice = document.querySelectorAll('input[editstoreprice]');
        if ( this.editstoreprice ) this.inputsChangeApply(this.editstoreprice);

        this.queryObj = {
            orderid: '',
            modelID: '',
            comment: '',
            price: '',
            room: '',
            shelf: '',
        };
    }

    inputsChangeApply( btns )
    {
        let self = this;
        $.each(btns, function(i, btn) {
            btn.addEventListener('change', function(e) {
                self.setModelPrice( e, btn );
            },false);    
        });
    }

    btnsClickApply( btns, condition )
    {
        let self = this;
        $.each(btns, function(i, btn) {
            btn.addEventListener('click', function(e) {
                self.jbModalShow(e, btn, condition);
            },false);    
        });
    }
    
    jbModalShow( e, btn, condition ) {
        e.preventDefault();
        e.stopPropagation();

        let modelData = btn.firstElementChild; //input

        let modelImg = modelData.getAttribute('data-img');
        let modelN3d = modelData.getAttribute('data-n3d');
        let modelType = modelData.getAttribute('data-mtype');
        let modelClient = modelData.getAttribute('data-client');

        let modal = document.getElementById('jewel-box-modal');

        if ( condition == "add" ) 
        {
            modal.querySelector('#jbModalLabel').innerHTML = "Add ";
            modal.querySelector('.mjb-img').src = modelImg;
            modal.querySelector('.mjb-mtype').innerHTML = modelN3d + " / " + modelType;
            modal.querySelector('.mjb-client').innerHTML = modelClient;
            modal.querySelector('#mjb-commenttext').innerHTML = "";
            modal.querySelector('.mjb-link').href = modelData.getAttribute('data-link');

            modal.querySelector('#roomboxlocated').value = modelData.getAttribute('data-room');
            modal.querySelector('#shelfboxlocated').value = modelData.getAttribute('data-shelf');
        }

        if ( condition == "return" )
        {
            modal.querySelector('#jbModalLabel').innerHTML = "Set shelf num and room or leave it in prev state";
            modal.querySelector('.mjb-img').src = modelImg;
            modal.querySelector('.mjb-mtype').innerHTML = modelData.getAttribute('data-name');
            modal.querySelector('.mjb-client').innerHTML = modelData.getAttribute('data-cat');
            modal.querySelector('.mjb-link').parentElement.innerHTML = modelData.getAttribute('data-prj');
            modal.querySelector('.located-in').innerHTML = "Put back to: ";

            modal.querySelector('#storageRoomsbox').value = modelData.getAttribute('data-room');
            modal.querySelector('#inputShelfBox').value = modelData.getAttribute('data-shelf');
            modal.querySelector('.storageRoomsbox').classList.remove('d-none');
            modal.querySelector('.inputShelfBox').classList.remove('d-none');

            modal.querySelector('.roomboxlocated').classList.add('d-none');
            modal.querySelector('.shelfboxlocated').classList.add('d-none');
            

            
            //if (modal.querySelector('.mjb-link')) modal.querySelector('.mjb-link').remove();

            if (modal.querySelector('#mjb-commenttext'))
                modal.querySelector('#mjb-commenttext').parentElement.remove();

            modal.querySelector('#mjb-submit').innerHTML = "Put Back";
        }

        if ( condition == "openallmodels" )
            modal.querySelector('.table-responsive').remove();

        let self = this;
        modal.querySelector('#mjb-submit').onclick = function()
        {
            if ( btn.hasAttribute('data-orderid') )
                self.queryObj.orderid = btn.getAttribute('data-orderid');
            if ( btn.hasAttribute('data-id') )
                self.queryObj.modelID = btn.getAttribute('data-id');

            let comment =  modal.querySelector('#mjb-commenttext');
            if ( comment )
                self.queryObj.comment = comment.value;

            if ( condition == "return" )
            {
                self.queryObj.room = modal.querySelector('#storageRoomsbox').value;
                self.queryObj.shelf = modal.querySelector('#inputShelfBox').value;
            }
            
            self.pushJBData(condition);
        };

        $('#jewel-box-modal').modal('show');
    }

    pushJBData(condition) {
        let self = this;
        $.ajax({
            url: "/site/my?box=" + condition,
            type: 'POST',
            data: self.queryObj,
            dataType:"json",
            success:function(resp) {
                if (resp) {
                    $('#jewel-box-modal').modal('hide');
                    reload(true);
                } 
            }
        });
    }

    setModelPrice( e, input ) 
    {
        let self = this;
        this.queryObj.modelID = input.getAttribute('data-id');
        this.queryObj.orderid = input.getAttribute('data-orderid');
        this.queryObj.price = input.value;
        $.ajax({
            url: "/site/my?box=setmodelprice",
            type: 'POST',
            data: self.queryObj,
            dataType:"json",
            success:function(resp) {
                debug(resp);
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

}