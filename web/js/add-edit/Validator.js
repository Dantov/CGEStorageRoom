//-------- ВАЛИДАЦИЯ ФОРМЫ ---------//

function Validator() 
{
    this.item_name = document.getElementById('item_name');
    this.project = document.getElementById('project');
    this.item_category = document.getElementById('item_category');
    this.item_quantity = document.getElementById('item_quantity');
    this.picts = document.getElementById('picts').querySelectorAll('.mainCard');

    this.fields = {};

    this.init();
}

Validator.prototype.init = function()
{
    let self = this;
    this.fields = {
        item_name : {
            input : self.item_name,
            text : 'Item Name',
            valid : false,
        },
        project : {
            input : self.project,
            text : 'Project',
            valid : false,
        },
        item_category : {
            input : self.item_category,
            text : 'Item Category',
            valid : false,
        },
        item_quantity : {
            input : self.item_quantity,
            text : 'Item Quantity',
            valid : false,
        },
    }

  debug( 'Validator init ok' );

};
Validator.prototype.validate = function()
{
    let self = this;
    let flagStop = false;
    $.each(this.fields, function (i, field) {
        //debug(field,'$.each');

        if ( !self.validate_field(field) ) {
            flagStop = true;
            return;
        }
    });
    if (flagStop) return false;
    
    if ( !this.validate_Picts() ) {
        return false;
    }

    //return false;
    return true;
};
Validator.prototype.validate_field = function( field )
{
    if ( (!field) || (!field.input.value) )
    {
        //debug(field, 'validate_field');
        field.input.scrollIntoView();
        AR.warning('You have to specify '+ field.text + '!',0);
        return field.valid = false;
    }
    
    debug(field.text,'true');
    return field.valid = true;
};

Validator.prototype.validate_Picts = function()
{
    this.picts = document.getElementById('picts').querySelectorAll('.mainCard');
    if ( !this.picts.length )
    {
        document.getElementById('picts').scrollIntoView();
        AR.warning('You need to upload atleast one picture',0);
        return false;  
    }
    return true;
};