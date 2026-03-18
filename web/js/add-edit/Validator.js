//-------- ВАЛИДАЦИЯ ФОРМЫ ---------//

function Validator() 
{
    this.number_3d = document.getElementById('number_3d');
    this.modeller3d = document.getElementById('modeller3d');
    this.model_type = document.getElementById('model_type');
    this.model_weight = document.getElementById('model_weight');
    this.client = document.getElementById('client');
    this.material_rows = document.getElementById('tableAllMats').querySelectorAll('.form-row');
    this.picts = document.getElementById('picts').querySelectorAll('.mainCard');

    this.fields = {};

    this.init();
}

Validator.prototype.init = function()
{
    let self = this;
    this.fields = {
        number3d : {
            input : self.number_3d,
            text : 'Номер 3D модели',
            valid : false,
        },
        modeller3d : {
            input : self.modeller3d,
            text : '3D моделлера',
            valid : false,
        },
        model_type : {
            input : self.model_type,
            text : 'Тип модели',
            valid : false,
        },
        model_weight : {
            input : self.model_weight,
            text : 'Вес модели',
            valid : false,
        },
        client : {
            input : self.client,
            text : 'Заказчика',
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
    if ( !this.validate_Material() ) {
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
        AR.warning('Нужно указать '+ field.text + '!',0);
        return field.valid = false;
    }
    if ( field.input === this.model_weight )
    {
        if ( field.input.value <= 0 || field.input.value > 2000 )
        {
            field.input.scrollIntoView();
            AR.warning('Вес модели указан не верно!',0);
            return field.valid = false;
        }
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
        AR.warning('Нужно внести хоть одну картинку!',0);
        return false;  
    }
    return true;
};
Validator.prototype.validate_Material = function()
{
    this.material_rows = document.getElementById('tableAllMats').querySelectorAll('.form-row');
    if ( !this.material_rows.length )
    {
        document.getElementById('tableAllMats').scrollIntoView();
        AR.warning('Нужно внести, как минимум, один материал изделия!',0);
        return false;
    }
    this.material_rows.forEach(row=>{
        let inputs = row.querySelectorAll('input');
        inputs.forEach(input=>{
            if ( !input.value ) {
                input.scrollIntoView();
                AR.warning('Все поля материала должны быть заполнены!',0);
                return false;
            }
        });
    });
    return true;
};